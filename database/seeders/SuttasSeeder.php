<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuttasSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/all_mn_suttas.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
