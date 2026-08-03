<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Genérica e reutilizável por qualquer template (não exclusiva de campanha) —
        // ver docs/auditoria-template-campanha.md §17.
        Schema::create('card_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->string('category', 40)->default('other'); // management_plan|material|other
            $table->string('label');
            $table->string('file_path');
            $table->string('file_type', 20)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['card_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_files');
    }
};
