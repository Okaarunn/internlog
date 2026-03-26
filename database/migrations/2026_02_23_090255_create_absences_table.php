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
        Schema::create('absences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('intern_id');
            $table->uuid('admin_id')->nullable();
            $table->date('date')->nullable();

            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->integer('duration')->nullable();

            $table->text('notes_out')->nullable();

            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'alpha']);
            $table->enum('validation_status', ['menunggu', 'disetujui', 'ditolak'])->nullable();

            $table->foreign('intern_id')->references('id')->on('interns')->cascadeOnDelete();
            $table->foreign('admin_id')->references('id')->on('admins')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
