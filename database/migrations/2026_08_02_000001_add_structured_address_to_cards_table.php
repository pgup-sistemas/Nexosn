<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->string('city', 100)->nullable()->after('address');
            $table->string('state', 2)->nullable()->after('city');
            $table->string('zip_code', 9)->nullable()->after('state');
            $table->string('country', 100)->nullable()->default('Brasil')->after('zip_code');
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn(['city', 'state', 'zip_code', 'country']);
        });
    }
};
