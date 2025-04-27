<?php

namespace App\Http\Controllers;

class KangyurController extends Controller
{
    public function index()
    {
        return inertia('Kangyur', [

        ]);
    }

    public function general()
    {
        return inertia('Kangyur/GeneralSutrasPage');
    }
}
