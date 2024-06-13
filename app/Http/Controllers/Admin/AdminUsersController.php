<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminUsersController extends Controller
{
    public function index()
    {
        return inertia('Admin/AdminUsersPage', [
            'usersPage' => User::query()->with('roles')->orderBy('email')->paginate(20),
            'roles' => Role::all()
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'is_superadmin' => 'required',
            'role_ids' => 'array',
        ]);

        $user = User::whereId($request->id)->first();
        $user->is_superadmin = $request->is_superadmin ? 1 : 0;
        $user->roles()->detach();
        $user->roles()->attach(Role::whereIn('id', $request->role_ids)->get());
        $user->save();
        return json_encode(['ok' => true]);
        
    }
}
