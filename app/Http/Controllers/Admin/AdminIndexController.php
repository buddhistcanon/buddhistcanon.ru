<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminIndexController extends Controller
{
    public function __invoke()
    {
        return inertia('Admin/AdminIndexPage');
    }
}
