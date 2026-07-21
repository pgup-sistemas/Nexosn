<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('plan');
            $table->index('is_admin');
            $table->index('trial_ends_at');
            $table->index('plan_expires_at');
        });

        Schema::table('cards', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('target_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['plan']);
            $table->dropIndex(['is_admin']);
            $table->dropIndex(['trial_ends_at']);
            $table->dropIndex(['plan_expires_at']);
        });

        Schema::table('cards', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['target_id']);
        });
    }
};
