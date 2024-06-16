<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddTestUsersCommand extends Command {
    protected $signature = 'lb:add_test_users';

    public function handle(): void
    {
        $user = new \App\Models\User();
        $user->nickname = 'admin';
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('123456');
        $user->email_verified_at = Carbon::now();
        $user->is_superadmin = 1;
        $user->save();

        $user = new \App\Models\User();
        $user->nickname = 'editor_rus';
        $user->email = 'editor_russian@editor.com';
        $user->password = Hash::make('123456');
        $user->email_verified_at = Carbon::now();
        $user->is_superadmin = 0;
        $user->save();

        $editor_rus_role = \App\Models\Role::where('name', 'editor_russian')->first();
        $user->roles()->attach($editor_rus_role->id);
        $user->save();

        $user = new \App\Models\User();
        $user->nickname = 'regular_user';
        $user->email = 'user@user.com';
        $user->password = Hash::make('123456');
        $user->email_verified_at = Carbon::now();
        $user->is_superadmin = 0;
        $user->save();
    }
}
