<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::getUser();
        return inertia('User/Profile', ['user' => $user]);
    }
}
