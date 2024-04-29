<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $result = null;
        if ($search) {

        }

        return inertia('Search', [
            'search' => $search,
            'result' => $result,
        ]);
    }
}
