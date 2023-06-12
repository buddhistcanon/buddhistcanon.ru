<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("session")->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip', 15)->nullable()->index();
            $table->string('action')->index();
            $table->unsignedBigInteger('sutta_id')->nullable()->index();
            $table->unsignedBigInteger('term_id')->nullable()->index();
            $table->unsignedBigInteger('content_id')->nullable()->index();
            $table->unsignedBigInteger('chunk_id')->nullable()->index();
            $table->longText("storage")->nullable();
            $table->longText("before")->nullable();
            $table->longText("after")->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
