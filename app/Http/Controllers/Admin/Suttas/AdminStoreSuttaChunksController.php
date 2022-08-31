<?php

namespace App\Http\Controllers\Admin\Suttas;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ContentChunk;
use App\Models\Sutta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminStoreSuttaChunksController extends Controller
{
    public function __invoke(Request $request)
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
