<?php

use App\Models\Content;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->text("short_description")->nullable()->after("link_url");
        });

        $contents = Content::query()
            ->where("contentable_type", "sutta")
            ->get();
        foreach($contents as $content){
            if($content->lang == "pali") $content->short_description = "Оригинал на пали, редакция Mahāsaṅgīti";
            if($content->lang == "en") $content->short_description = "Перевод c пали на английский, Бханте Суджато";
            if($content->lang == "ru") $content->short_description = "Перевод с английского перевода Бхикку Бодхи на русский, Сергей SV";
            $content->save();
        }
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn("short_description");
        });
    }
};
