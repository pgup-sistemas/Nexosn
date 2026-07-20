<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('card_views', function (Blueprint $table) {
            // Origem normalizada: direct | whatsapp | instagram | google | facebook | outros
            $table->string('source', 40)->default('direct')->after('referer');
        });
    }

    public function down(): void
    {
        Schema::table('card_views', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
