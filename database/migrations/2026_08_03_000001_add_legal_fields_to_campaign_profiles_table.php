<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Campo opcional para identificação do responsável legal pelo conteúdo,
        // relevante quando o template de campanha é usado em eleição oficial
        // regida pelas normas do TSE. Ver docs/auditoria-template-campanha.md.
        Schema::table('campaign_profiles', function (Blueprint $table) {
            $table->string('legal_responsible_name')->nullable()->after('hq_lng');
            $table->string('legal_responsible_document', 30)->nullable()->after('legal_responsible_name');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_profiles', function (Blueprint $table) {
            $table->dropColumn(['legal_responsible_name', 'legal_responsible_document']);
        });
    }
};
