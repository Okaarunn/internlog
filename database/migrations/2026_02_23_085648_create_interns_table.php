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
            $table->uuid('id')->primary();
            $table->foreignUuid('department_id');
            $table->string('nin', 16)->unique();
            $table->string('name', 100);
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->string('address', 255);
            $table->string('phone', 12)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('username', 100)->unique();
            $table->string('password');
            
            $table->foreign('department_id')->references('id')->on('departments')->cascadeOnDelete();
            
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
