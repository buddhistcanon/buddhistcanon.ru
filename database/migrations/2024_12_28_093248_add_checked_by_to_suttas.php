<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suttas', function (Blueprint $table) {
            $table->unsignedBigInteger('validated_by')->after('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('suttas', function (Blueprint $table) {
            $table->dropColumn('validated_by');
        });
    }
};
