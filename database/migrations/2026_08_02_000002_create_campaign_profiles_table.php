<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('campaign_name')->nullable();
            $table->string('role_title')->nullable();
            $table->string('ballot_number', 20)->nullable();
            $table->string('organization_name')->nullable();
            $table->string('affiliation')->nullable();
            $table->string('slogan')->nullable();
            $table->string('portrait_photo')->nullable();
            $table->dateTime('countdown_target_at')->nullable();
            $table->string('hq_address')->nullable();
            $table->decimal('hq_lat', 10, 7)->nullable();
            $table->decimal('hq_lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_profiles');
    }
};
