<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Eventos públicos de campanha (comitê, carreata, reunião) — deliberadamente
        // separada de card_appointments (agendamento 1:1 com o titular). São conceitos
        // de negócio diferentes: cardinalidade e semântica não se misturam.
        // Ver docs/auditoria-template-campanha.md §16 risco R3.
        Schema::create('campaign_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('location')->nullable();
            $table->string('map_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['card_id', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_events');
    }
};
