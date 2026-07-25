<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('card_views', function (Blueprint $table) {
            $table->string('ip_hash', 64)->nullable()->after('card_id');
        });

        // Migra registros existentes: hasheia os IPs já armazenados
        DB::table('card_views')->whereNotNull('ip')->lazyById()->each(function ($row) {
            DB::table('card_views')->where('id', $row->id)->update([
                'ip_hash' => hash('sha256', $row->ip . config('app.key')),
            ]);
        });

        Schema::table('card_views', function (Blueprint $table) {
            $table->dropColumn('ip');
        });
    }

    public function down(): void
    {
        Schema::table('card_views', function (Blueprint $table) {
            $table->string('ip', 45)->nullable()->after('card_id');
            $table->dropColumn('ip_hash');
        });
    }
};
