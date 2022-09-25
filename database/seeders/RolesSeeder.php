<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $role = new Role();
        $role->id = 1;
        $role->name = "admin";
        $role->save();

        $role = new Role();
        $role->id = 2;
        $role->name = "user";
        $role->save();
    }
}
