<?php namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\Models\User();
        $user->nickname = "admin";
        $user->email = "admin@admin.com";
        $user->password = Hash::make("123456");
        $user->email_verified_at = Carbon::now();
        $user->is_superadmin = 1;
        $user->save();
    }
}
