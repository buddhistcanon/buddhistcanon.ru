<?php

use App\Models\People;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peoples', function (Blueprint $table) {
            $table->string('signature');
        });

        People::query()->where('slug', 'sv')->update(['signature' => 'Сергей SV']);
        People::query()->where('slug', 'khantibalo')->update(['signature' => 'Павел Khantibalo']);
        People::query()->where('slug', 'sujato')->update(['signature' => 'Бханте Суджато']);
        People::query()->where('slug', 'bhikkhu_bodhi')->update(['signature' => 'Бханте Бодхи']);
        People::query()->where('slug', 'ajahn_budhadasa')->update(['signature' => 'Аджан Буддадаса']);
    }

    public function down(): void
    {
        Schema::table('peoples', function (Blueprint $table) {
            $table->dropColumn('signature');
        });
    }
};
