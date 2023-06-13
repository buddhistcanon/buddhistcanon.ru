<?php

namespace App\Http\Controllers\Admin\Suttas;

use App\Http\Controllers\Controller;
use App\Logger\LogData;
use App\Logger\Logger;
use App\Models\ContentChunk;
use App\Models\Sutta;
use App\Services\ContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSuttaController extends Controller
{
    public function edit(string $id)
    {
        if(is_numeric($id)){
            $sutta = Sutta::query()
                ->where("id", $id)
//                ->with(["contents.chunks"=>fn($q)=>$q->orderBy("order")])
                ->with("contents.chunks")
                ->with("contents.translator")
                ->firstOrFail();
        }else{
            $sutta = Sutta::query()
                ->where("name", strtoupper($id))
//                ->with(["contents.chunks"=>fn($q)=>$q->orderBy("order")])
                ->with("contents.chunks")
                ->with("contents.translator")
                ->firstOrFail();
        }
        //dd($sutta->contents->filter(fn($c)=>$c->lang=='ru')->first()->chunks->toArray());

        return inertia("Admin/Suttas/AdminEditSuttaPage", [
            'sutta'=>$sutta
        ]);
    }

    public function store(Request $request)
    {
        $suttaData = $request->json('sutta');
        $suttaId = $suttaData['id'];

        $sutta = Sutta::query()
            ->where('id', $suttaId)
            ->firstOrFail();
        $sutta->title_pali = $suttaData['title_pali'];
        $sutta->title_transcribe_ru = $suttaData['title_transcribe_ru'];
        $sutta->title_translate_ru = $suttaData['title_translate_ru'];
        $sutta->description = $suttaData['description'];
        if($sutta->isDirty()){
            Logger::log(new LogData(
                action: "update_sutta",
                userId: auth()->id(),
                suttaId: $sutta->id,
                before: $sutta->getOriginal(),
                after: $sutta->toArray()
            ));
        }
        $sutta->save();

        $originalContents = $sutta->load("contents.chunks")->contents;

        $rows = $request->json('rows');
        $chunksIdsToDelete = $request->json('chunksToDelete');

        $existingChunkIds = [];
        $editedContentIds = [];
        foreach ($rows as $chunks) {
            foreach ($chunks as $chunkRow) {
                if (is_null($chunkRow)) {
                    continue;
                }
                if ($chunkRow['id'] AND ! str_contains($chunkRow['id'], 'new')) {
                    $chunk = ContentChunk::query()
                        ->where('id', $chunkRow['id'])
                        ->first();
                    $chunk->text = $chunkRow['text'];
                    $chunk->order = $chunkRow['order'];
                    if($chunk->getOriginal()['text']!=$chunk->text){
                        Logger::log(new LogData(
                            action: "update_chunk",
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
                    $chunk->chunkable_type = "sutta";
                    $chunk->chunkable_id = $chunkRow['chunkable_id'];
                    $chunk->content_id = $chunkRow['content_id'];
                    $chunk->order = $chunkRow['order'];
                    $chunk->mark = $chunkRow['mark'] ?? Str::random(5);
                    $chunk->text = $chunkRow['text'];
                    $chunk->save();
                    Logger::log(new LogData(
                        action: "create_chunk",
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
                action: "delete_chunk",
                userId: auth()->id(),
                suttaId: $sutta->id,
                contentId: $chunk->content_id,
                chunkId: $chunk->id,
                before: $chunk->toArray()
            ));
            $chunk->delete();
        }

        // логирование изменённого контента целиком
        $sutta = Sutta::query()
            ->where('id', $suttaId)
            ->with("contents.chunks")
            ->firstOrFail();
        $editedContentIds = array_unique($editedContentIds);
        foreach($editedContentIds as $contentId){
            $originalContent = $originalContents->filter(fn($content)=>$content->id === $contentId)->first();
            $content = $sutta->contents->filter(fn($content)=>$content->id === $contentId)->first();
            Logger::log(new LogData(
                action: "update_content",
                userId: auth()->id(),
                suttaId: $sutta->id,
                contentId: $contentId,
                before: $originalContent->toArray(),
                after: $content->toArray()
            ));
        }

        return back()->withSuccess('Сутта и контент сохранены');
    }

    public function storeSuttaChunks(Request $request)
    {
        $rows = $request->json("rows");
        foreach($rows as $chunks){
            foreach($chunks as $chunkRow){
                if(is_null($chunkRow)) continue;
                if($chunkRow['id']){
                    $chunk = ContentChunk::query()
                        ->where("id", $chunkRow['id'])
                        ->first();
                    $chunk->text = $chunkRow['text'];
                }else{
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
}
