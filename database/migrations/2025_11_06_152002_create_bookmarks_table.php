<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sutta_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('sutta_id')
                ->references('id')->on('suttas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Уникальная комбинация user_id и sutta_id, чтобы нельзя было добавить одну сутту дважды
            $table->unique(['user_id', 'sutta_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['sutta_id']);
        });
        Schema::dropIfExists('bookmarks');
    }
};
