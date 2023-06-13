<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename("user_roles", "role_user");
        Schema::table('roles', function (Blueprint $table) {
            $table->text("description");
        });
        DB::table("roles")->insert([
            'name' => 'admin',
            'description' => 'Full access to admin area',
        ]);
        DB::table("roles")->insert([
            'name' => 'editor_russian',
            'description' => 'Allow to edit content on russian language',
        ]);
        DB::table("roles")->insert([
            'name' => 'editor_english',
            'description' => 'Allow to edit content on english language',
        ]);
        DB::table("roles")->insert([
            'name' => 'editor_pali',
            'description' => 'Allow to edit content on pali language',
        ]);
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn("description");
        });
        Schema::rename("role_user", "user_roles");
        DB::table("roles")
            ->whereIn("name", ['admin', 'editor_russian', 'editor_english', 'editor_pali'])
            ->delete();
    }
};
