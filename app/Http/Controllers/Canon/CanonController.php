<?php

namespace App\Http\Controllers\Canon;

use App\Http\Controllers\Controller;
use App\Models\Sutta;

class CanonController extends Controller
{
    public function dn()
    {
        return redirect('/mn');
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
        $suttas = Sutta::query()
            ->where('category', 'an')
            ->with('contents')
            ->with('contents.translator')
            ->orderBy('order', 'asc')
            ->get();

        return inertia('Canon/AnPage', [
            'suttas' => $suttas,
        ]);
    }

    public function an1()
    {
        $suttas = Sutta::query()
            ->where('category', 'an')
            ->where('order', 1)
            ->get();
        $suttas = $suttas->map(function($sutta){
            $sutta->sort = (int)explode('-', $sutta->suborder)[0];
            return $sutta;
        })->sortBy("sort");
        return inertia('Canon/An/An1Page', [ 'suttas' => $suttas ]);
    }

    public function sn()
    {
        return redirect('/mn');
    }
}
