<?php

namespace App\Http\Controllers\Admin\Suttas;

use App\Http\Controllers\Controller;
use App\Models\Sutta;

class AdminSuttasGet extends Controller
{
    public function __invoke()
    {
        $suttas = Sutta::query()
            ->with('contents')
            ->with('contents.translator')
            ->orderBy('order', 'asc')
            ->get();

        return inertia('Admin/Suttas/AdminSuttasPage', ['suttas' => $suttas]);
    }
}
