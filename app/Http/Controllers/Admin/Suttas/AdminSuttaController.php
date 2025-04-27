<?php

namespace App\Http\Controllers\Admin\Suttas;

use App\Http\Controllers\Controller;
use App\Logger\LogData;
use App\Logger\Logger;
use App\Models\Content;
use App\Models\ContentChunk;
use App\Models\People;
use App\Models\Sutta;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSuttaController extends Controller
{
    public function edit(string $id)
    {
        if (is_numeric($id)) {
            $sutta = Sutta::query()
                ->where('id', $id)
                ->with('contents.chunks')
                ->with('contents.translator')
                ->firstOrFail();
        } else {
            $sutta = Sutta::query()
                ->where('name', strtoupper($id))
                ->with('contents.chunks')
                ->with('contents.translator')
                ->firstOrFail();
        }
        $nextSutta = Sutta::query()
            ->where('category', $sutta->category)
            ->where('order', '>', $sutta->order)
            ->orderBy('order')
            ->first();
        $prevSutta = Sutta::query()
            ->where('category', $sutta->category)
            ->where('order', '<', $sutta->order)
            ->orderByDesc('order')
            ->first();
        $translators = People::query()
            ->get()
            ->map(function (People $person) {
                return [
                    'id' => $person->id,
                    'name' => $person->signature,
                ];
            });

        return inertia('Admin/Suttas/AdminEditSuttaPage', [
            'sutta' => $sutta,
            'nextSutta' => $nextSutta,
            'prevSutta' => $prevSutta,
            'translators' => $translators,
        ]);
    }

    public function store(Request $request)
    {
        //        dd($request->json()->all());
        $suttaData = $request->json('sutta');
        $suttaId = $suttaData['id'];
        $isContentSynced = $request->json('isContentSynced') ?? [];

        // Данные сутты
        $sutta = Sutta::query()
            ->where('id', $suttaId)
            ->firstOrFail();
        $sutta->title_pali = $suttaData['title_pali'];
        $sutta->title_transcribe_ru = $suttaData['title_transcribe_ru'];
        $sutta->title_translate_ru = $suttaData['title_translate_ru'];
        $sutta->description = $suttaData['description'];
        $prevValidatedBy = $sutta->validated_by;
        if ($suttaData['is_validated'] and ! $sutta->validated_by) {
            $sutta->validated_by = auth()->id();
            Logger::log(new LogData(
                action: 'validate_sutta',
                userId: auth()->id(),
                suttaId: $sutta->id,
                before: ['validated_by' => $prevValidatedBy],
                after: ['validated_by' => $sutta->validated_by]
            ));
        }
        if ($sutta->isDirty()) {
            Logger::log(new LogData(
                action: 'update_sutta',
                userId: auth()->id(),
                suttaId: $sutta->id,
                before: $sutta->getOriginal(),
                after: $sutta->toArray()
            ));
        }
        $sutta->save();
        $originalContents = $sutta->load('contents.chunks')->contents;

        // Добавление нового контента
        $contentsWithoutChunks = $request->json('contentsWithoutChunks');
        $replacedContentIds = collect();
        foreach ($contentsWithoutChunks as $contentRow) {
            if (is_null($contentRow)) {
                continue;
            }
            // TODO добавить редактирование существующего контента

            //            if ($contentRow['id'] and ! str_contains($contentRow['id'], 'new')) {
            //                // это существующий контент, надо его просто сохранить
            //                $content = $sutta->contents->filter(fn ($content) => $content->id === $contentRow['id'])->first();
            //                $content->is_synced = $contentRow['is_synced'];
            //                $content->title = $contentRow['title'] ?? 'Контент #'.$contentRow['id'];
            //                $content->subtitle = $contentRow['subtitle'] ?? null;
            //                $content->lang = $contentRow['lang'] ?? null;
            //                $content->save();
            //            }
            if (str_contains($contentRow['id'], 'new')) {
                // это новый контент, надо его добавить.
                $translatorId = $contentRow['translator_id'];
                if (! $translatorId) {
                    // это новый переводчик, надо добавить
                    $translatorData = $contentRow['translator'];
                    $translator = new People();
                    $translator->fullname_ru = $translatorData['fullname_ru'];
                    $translator->slug = $translatorData['slug'];
                    $translator->signature = $translatorData['signature'];
                    $translator->save();
                    Logger::log(new LogData(
                        action: 'create_translator',
                        userId: auth()->id(),
                        after: $translator->toArray()
                    ));
                    $translatorId = $translator->id;
                } else {
                    try {
                        $translator = People::findOrFail($translatorId);
                    } catch (ModelNotFoundException $e) {
                        throw new Exception("Переводчик $translatorId не найден");
                        // TODO возврат ошибки на фронт
                    }
                }
                $content = new Content();
                $content->contentable_type = 'sutta';
                $content->contentable_id = $sutta->id;
                $content->is_synced = $contentRow['is_synced'];
                $content->title = $sutta->name;
                $content->subtitle = $contentRow['subtitle'] ?? null;
                $content->short_description = $contentRow['short_description'] ?? null;
                $content->link_url = $contentRow['link_url'] ?? null;
                $content->lang = $contentRow['lang'] ?? null;
                $content->translator_id = $translatorId;
                $content->translator_name = $translator->signature;
                $content->save();
                Logger::log(new LogData(
                    action: 'create_content',
                    userId: auth()->id(),
                    suttaId: $sutta->id,
                    contentId: $content->id,
                    after: $content->toArray()
                ));
                $replacedContentIds->push([
                    'old' => $contentRow['id'],
                    'new' => $content->id,
                ]);
            }
        }

        $rows = $request->json('rows');
        $chunksIdsToDelete = $request->json('chunksToDelete');

        $existingChunkIds = [];
        $editedContentIds = [];
        $modifiedContent = [];
        foreach ($rows as $chunks) {
            foreach ($chunks as $chunkRow) {
                if (is_null($chunkRow)) {
                    continue;
                }
                if ($chunkRow['id'] and ! str_contains($chunkRow['id'], 'new')) {
                    $chunk = ContentChunk::query()
                        ->where('id', $chunkRow['id'])
                        ->first();
                    $chunk->text = $chunkRow['text'] ?? '';
                    $chunk->order = $chunkRow['order'];
                    if ($chunk->getOriginal()['text'] != $chunk->text) {
                        Logger::log(new LogData(
                            action: 'update_chunk',
                            userId: auth()->id(),
                            suttaId: $sutta->id,
                            contentId: $chunk->content_id,
                            chunkId: $chunk->id,
                            before: $chunk->getOriginal(),
                            after: $chunk->toArray()
                        ));
                        $editedContentIds[] = $chunk->content_id;
                    }
                    $chunk->save();
                } else {
                    $chunk = new ContentChunk();
                    $chunk->chunkable_type = 'sutta';
                    $chunk->chunkable_id = $sutta->id;
                    $contentId = $replacedContentIds->filter(fn ($item) => $item['old'] === $chunkRow['content_id'])->first()['new'];
                    $chunk->content_id = $contentId;
                    $chunk->order = $chunkRow['order'];
                    $chunk->mark = $chunkRow['mark'] ?? Str::random(5);
                    $chunk->text = $chunkRow['text'];
                    $chunk->save();
                    Logger::log(new LogData(
                        action: 'create_chunk',
                        userId: auth()->id(),
                        suttaId: $sutta->id,
                        contentId: $chunk->content_id,
                        chunkId: $chunk->id,
                        after: $chunk->toArray()
                    ));
                    $editedContentIds[] = $chunk->content_id;
                }
            }
        }

        $chunksToDelete = ContentChunk::query()
            ->whereIn('id', $chunksIdsToDelete)
            ->get();
        //        dd($chunksToDelete->toArray());
        foreach ($chunksToDelete as $chunk) {
            Logger::log(new LogData(
                action: 'delete_chunk',
                userId: auth()->id(),
                suttaId: $sutta->id,
                contentId: $chunk->content_id,
                chunkId: $chunk->id,
                before: $chunk->toArray()
            ));
            $chunk->delete();
        }

        // проставление is_synced для контента
        foreach ($isContentSynced as $contentId => $isSynced) {
            $content = $sutta->contents->filter(fn ($content) => $content->id === $contentId)->first();
            if ($content->is_synced == $isSynced) {
                continue;
            }
            $content->is_synced = $isSynced;
            $content->save();
            $action = $isSynced ? 'make_synced' : 'make_unsynced';
            Logger::log(new LogData(
                action: $action,
                userId: auth()->id(),
                suttaId: $sutta->id,
                contentId: $contentId,
            ));
        }

        // логирование изменённого контента целиком
        $sutta = Sutta::query()
            ->where('id', $suttaId)
            ->with('contents.chunks')
            ->firstOrFail();
        $editedContentIds = array_unique($editedContentIds);
        foreach ($editedContentIds as $contentId) {
            $prevContent = $originalContents->filter(fn ($content) => $content->id === $contentId)->first();
            $newContent = $sutta->contents->filter(fn ($content) => $content->id === $contentId)->first();
            $prevContent ? $prevContentArray = $prevContent->toArray() : $prevContentArray = null;
            $newContent ? $newContentArray = $newContent->toArray() : $newContentArray = null;
            Logger::log(new LogData(
                action: 'update_content',
                userId: auth()->id(),
                suttaId: $sutta->id,
                contentId: $contentId,
                before: $prevContentArray,
                after: $newContentArray
            ));
        }

        return back()->withSuccess('Сутта и контент сохранены');
    }

    public function storeSuttaChunks(Request $request)
    {
        $rows = $request->json('rows');
        foreach ($rows as $chunks) {
            foreach ($chunks as $chunkRow) {
                if (is_null($chunkRow)) {
                    continue;
                }
                if ($chunkRow['id']) {
                    $chunk = ContentChunk::query()
                        ->where('id', $chunkRow['id'])
                        ->first();
                    $chunk->text = $chunkRow['text'];
                } else {
                    $chunk = new ContentChunk();
                    $chunk->chunkable_type = Sutta::class;
                    $chunk->chunkable_id = $chunkRow['chunkable_id'];
                    $chunk->content_id = $chunkRow['content_id'];
                    $chunk->order = $chunkRow['order'];
                    $chunk->mark = $chunkRow['mark'] ?? Str::random(5);
                    $chunk->text = $chunkRow['text'];
                }

                $chunk->save();
            }
        }

        return [
            'status' => 'success',
        ];
    }

    public function createContent(string $id)
    {
        if (is_numeric($id)) {
            $sutta = Sutta::query()
                ->where('id', $id)
                ->firstOrFail();
        } else {
            $sutta = Sutta::query()
                ->where('name', strtoupper($id))
                ->firstOrFail();
        }

        return inertia('Admin/Suttas/AdminCreateContentPage', [
            'sutta' => $sutta,
        ]);
    }
}
