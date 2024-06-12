<?php

namespace Database\Seeders;

use App\Models\SearchToken;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(BookSeeder::class);
        $this->call(CrawlerSeeder::class);
        $this->call(EditorsSeeder::class);
        $this->call(PeopleSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(SuttasSeeder::class);
        $this->call(TermSeeder::class);
    }
}
