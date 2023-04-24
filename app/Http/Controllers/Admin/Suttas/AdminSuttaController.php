<?php

namespace App\Http\Controllers\Admin\Suttas;

use App\Http\Controllers\Controller;
use App\Models\ContentChunk;
use App\Models\Sutta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSuttaController extends Controller
{
    public function edit(int $id)
    {
        $sutta = Sutta::query()
            ->where("id", $id)
            ->with("contents.chunks")
            ->with("contents.translator")
            ->firstOrFail();
        //dd($sutta->contents->filter(fn($c)=>$c->lang=='pali')->first()->chunks->toArray());

        return inertia("Admin/Suttas/AdminEditSuttaPage", [
            'sutta'=>$sutta
        ]);
    }

    public function store(Request $request)
    {
        $suttaData = $request->json('sutta');

        $sutta = Sutta::query()
            ->where('id', $suttaData['id'])
            ->firstOrFail();
        $sutta->title_pali = $suttaData['title_pali'];
        $sutta->title_transcribe_ru = $suttaData['title_transcribe_ru'];
        $sutta->title_translate_ru = $suttaData['title_translate_ru'];
        $sutta->description = $suttaData['description'];
        $sutta->save();

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
//                    $chunk = new ContentChunk();
//                    $chunk->chunkable_type = Sutta::class;
//                    $chunk->chunkable_id = $chunkRow['chunkable_id'];
//                    $chunk->content_id = $chunkRow['content_id'];
//                    $chunk->order = $chunkRow['order'];
//                    $chunk->mark = $chunkRow['mark'] ?? Str::random(5);
//                    $chunk->text = $chunkRow['text'];
                }
                $chunk->save();
            }
        }

        return back()->withSuccess('Сутта сохранена');
    }
}
