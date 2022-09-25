<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('nickname');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('is_superadmin')->default(0);
            $table->string('password');
            $table->unsignedBigInteger('people_id')->nullable();
            $table->rememberToken();
            $table->longText('display_settings')->nullable();
            $table->timestamps();
        });

        Schema::create('invites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invite_symbols')->index();
            $table->text('note')->nullable();
            $table->integer('registered_user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invites');
        Schema::dropIfExists('users');
        //Schema::dropIfExists('roles');
        //Schema::dropIfExists('user_roles');
    }
}
