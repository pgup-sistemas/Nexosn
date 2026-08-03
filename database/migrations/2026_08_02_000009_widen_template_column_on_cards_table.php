<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // varchar(20) estourava com chaves como 'campaign-institucional' (22 chars).
        Schema::table('cards', function (Blueprint $table) {
            $table->string('template', 40)->default('default')->change();
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->string('template', 20)->default('default')->change();
        });
    }
};
