<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EditorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
