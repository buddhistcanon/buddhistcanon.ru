<?php

namespace App\Http\Controllers\Admin;

class AdminHelpController
{
    public function index()
    {
        return inertia('Admin/AdminHelpPage');
    }
}
