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
        return inertia('Canon/AnPage');
    }

    public function an_x(int $x)
    {
        $suttas = Sutta::query()
            ->where('category', 'an')
            ->where('order', $x)
            ->with('contents')
            ->get();
        $suttas = $suttas->map(function ($sutta) {
            $sutta->sort = (int) explode('-', $sutta->suborder)[0];

            return $sutta;
        })->sortBy('sort')->values();

        switch ($x) {
            case 1:
                $title = 'Ekakanipāto';
                $subtitle = 'Книга единиц';
                break;
            case 2:
                $title = 'Dukanipāto';
                $subtitle = 'Книга двоек';
                break;
            case 3:
                $title = 'Tikanipāto';
                $subtitle = 'Книга троек';
                break;
            case 4:
                $title = 'Catukkanipāto';
                $subtitle = 'Книга четверок';
                break;
            case 5:
                $title = 'Pañcakanipāto';
                $subtitle = 'Книга пятерок';
                break;
            case 6:
                $title = 'Chakkanipāto';
                $subtitle = 'Книга шестерок';
                break;
            case 7:
                $title = 'Sattakanipāto';
                $subtitle = 'Книга семерок';
                break;
            case 8:
                $title = 'Aṭṭhakakanipāto';
                $subtitle = 'Книга восьмерок';
                break;
            case 9:
                $title = 'Navakanipāto';
                $subtitle = 'Книга девяток';
                break;
            case 10:
                $title = 'Dasakanipāto';
                $subtitle = 'Книга десяток';
                break;
            default:
                throw new \Exception('Unexpected nipāto number');
        }
        $title = "AN$x - $title";

        return inertia('Canon/AnXPage', ['suttas' => $suttas, 'title' => $title, 'subtitle' => $subtitle]);
    }

    public function sn()
    {
        return inertia('Canon/SnPage');
    }

    public function sn_x(int $x)
    {
        $suttas = Sutta::query()
            ->where('category', 'sn')
            ->where('order', $x)
            ->with('contents')
            ->get();
        $suttas = $suttas->map(function ($sutta) {
            $sutta->sort = (int) explode('-', $sutta->suborder)[0];

            return $sutta;
        })->sortBy('sort')->values();

        $title = "Раздел SN$x";
        $subtitle = '';

        return inertia('Canon/SnXPage', ['suttas' => $suttas, 'title' => $title, 'subtitle' => $subtitle]);
    }
}
