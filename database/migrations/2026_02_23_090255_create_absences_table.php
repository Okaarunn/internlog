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
            // id
            $table->uuid('id_absence')->primary();
            $table->foreignUuid('id_admin')->nullable();
            $table->foreignUuid('id_intern');

            // absence
            $table->date('date_absence');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha']);
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();

            // duration intern, example 8h 30min 
            $table->integer('duration')->nullable();

            // validation status enum pending, approved, rejected
            $table->enum('validation_status', ['pending', 'approved', 'rejected']);

            // information for absence
            $table->string('information', 255)->nullable();

            // foreign key id_intern references id_intern on table interns
            $table->foreign('id_intern')->references('id_intern')->on('interns')->cascadeOnDelete();
            $table->foreign('id_admin')->references('id_admin')->on('admins')->nullOnDelete();

            // created date
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
