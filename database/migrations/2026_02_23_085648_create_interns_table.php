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
        Schema::create('interns', function (Blueprint $table) {
            $table->uuid('id_intern')->primary();
            // id departemen foreign key table departemen
            $table->foreignUuid('id_department');
            $table->string('nin', 16)->unique();
            $table->string('name', 100);

            // gender enum laki-laki, perempuan
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->string('address', 255);
            $table->string('phone', 12)->unique();
            // start internship date
            $table->date('start_date');
            $table->date('end_date');

            // username password
            $table->string('username', 100)->unique();
            $table->string('password', 100);

            // foreign key id_departemen references id_departemen on table departemens
            $table->foreign('id_department')->references('id_department')->on('departments')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interns');
    }
};
