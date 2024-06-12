<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;

class AdminUsersController extends Controller
{
    public function index()
    {
        return inertia('Admin/AdminUsersPage', [
            'usersPage' => User::query()->with('roles')->orderBy('email')->paginate(20)
        ]);
    }
}
