<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentChunk;
use App\Models\Sutta;
use Illuminate\Http\Request;
use Str;

class AdminController extends Controller
{
    public function index()
    {
        //        return "Admin index";
        return inertia('Admin/AdminIndexPage');
    }

    public function suttas($category)
    {
        $suttas = Sutta::query()
            ->where('category', $category)
            ->with('contents')
            ->with('contents.translator')
            ->orderBy('order', 'asc')
            ->get();

        return inertia('Admin/Suttas/AdminSuttasPage', [
            'suttas' => $suttas,
            'category' => $category,
        ]);
    }

    public function editSutta($id)
    {
        $sutta = Sutta::query()
            ->where('id', $id)
            ->with('contents.chunks')
            ->with('contents.translator')
            ->firstOrFail();
        //dd($sutta->contents->filter(fn($c)=>$c->lang=='pali')->first()->chunks->toArray());

        return inertia('Admin/Suttas/AdminEditSuttaPage', [
            'sutta' => $sutta,
        ]);
    }

    public function storeSutta(Request $request)
    {

    }

    public function storeChunks(Request $request)
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
}
