<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE absences MODIFY COLUMN status ENUM('hadir', 'izin', 'sakit', 'alpha', 'terlambat') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE absences MODIFY COLUMN status ENUM('hadir', 'izin', 'sakit', 'alpha') NOT NULL");
    }
};
