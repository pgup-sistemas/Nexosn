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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('plan', ['free', 'pro'])->default('free')->after('email_verified_at');
            $table->timestamp('plan_expires_at')->nullable()->after('plan');
            $table->timestamp('trial_ends_at')->nullable()->after('plan_expires_at');
            $table->string('efi_subscription_id')->nullable()->after('trial_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan', 'plan_expires_at', 'trial_ends_at', 'efi_subscription_id']);
        });
    }
};
