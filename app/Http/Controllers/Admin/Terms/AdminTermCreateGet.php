<?php

namespace App\Http\Controllers\Admin\Terms;

use App\Http\Controllers\Controller;
use App\Models\Term;

class AdminTermCreateGet extends Controller
{
    public function __invoke()
    {
        $term = new Term();
        $term->title = "";

        return inertia("Admin/Terms/AdminEditTermPage", ['term'=>$term]);
    }
}
