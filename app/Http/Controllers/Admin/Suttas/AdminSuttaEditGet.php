<?php

namespace App\Http\Controllers\Admin\Suttas;

use App\Http\Controllers\Controller;
use App\Models\Sutta;

class AdminSuttaEditGet extends Controller
{
    public function __invoke(Int $id)
    {
        $sutta = Sutta::query()
            ->where("id", $id)
            ->with("contents.chunks")
            ->with("contents.translator")
            ->firstOrFail();
        //dd($sutta->contents->filter(fn($c)=>$c->lang=='pali')->first()->chunks->toArray());

        return inertia("Admin/Suttas/AdminEditSuttaPage", ['sutta'=>$sutta]);
    }
}
