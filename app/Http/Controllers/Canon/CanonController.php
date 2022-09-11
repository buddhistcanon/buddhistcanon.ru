<?php

namespace App\Http\Controllers\Canon;

use App\Http\Controllers\Controller;
use App\Models\Sutta;

class CanonController extends Controller
{
    public function dn()
    {
    }

    public function mn()
    {
        $suttas = Sutta::query()
            ->where('category', 'mn')
            ->with('contents')
            ->with('contents.translator')
            ->orderBy('order', 'asc')
            ->get();

        return inertia('Canon/MnPage', [
            'suttas' => $suttas,
        ]);
    }

    public function an()
    {
    }

    public function sn()
    {
    }
}
