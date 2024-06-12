<?php

namespace Database\Seeders;

use App\Models\SearchToken;
use Illuminate\Database\Seeder;

class SearchTokenSeeder extends Seeder
{
    public function run()
    {
        $token = new SearchToken();
        $token->token = 'bd8-us7-lt2';
        $token->description = 'Для dhamma.gift';
        $token->save();
    }
}
