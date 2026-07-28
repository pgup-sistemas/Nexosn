<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('google_calendar_token')->nullable()->after('remember_token');
        });

        Schema::table('card_appointments', function (Blueprint $table) {
            $table->string('google_event_id')->nullable()->after('token_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_calendar_token');
        });
        Schema::table('card_appointments', function (Blueprint $table) {
            $table->dropColumn('google_event_id');
        });
    }
};
