<?php

namespace App\Http\Controllers\Admin\Terms;

use App\Http\Controllers\Controller;
use App\Models\Term;

class AdminTermEditGet extends Controller
{
    public function __invoke(int $id)
    {
        $term = Term::query()
            ->where('id', $id)
            ->with(['variants' => function ($query) {
                $query->orderBy('title', 'asc');
            }])
            ->firstOrFail();

        return inertia('Admin/Terms/AdminEditTermPage', ['term' => $term]);
    }
}
