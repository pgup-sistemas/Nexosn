<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // MySQL: MODIFY COLUMN é necessário para alterar o enum
            DB::statement("ALTER TABLE card_appointments MODIFY COLUMN status ENUM('pending','confirmed','refused','cancelled') NOT NULL DEFAULT 'pending'");
        }
        // SQLite: enum é armazenado como VARCHAR — 'cancelled' já é aceito sem alteração.
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE card_appointments MODIFY COLUMN status ENUM('pending','confirmed','refused') NOT NULL DEFAULT 'pending'");
        }
    }
};
