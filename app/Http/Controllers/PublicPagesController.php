<?php

namespace App\Http\Controllers;

class PublicPagesController extends Controller
{
    public function news()
    {
        return inertia('News');
    }

    public function about()
    {
        return inertia('About');
    }

    public function policy()
    {
        return inertia('Policy');
    }

    public function user_agreement()
    {
        return inertia('UserAgreement');
    }
}
