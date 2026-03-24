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
        Schema::create('permission_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('intern_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['sakit', 'izin']);
            $table->string('reason', 255);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->uuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            $table->foreign('intern_id')->references('id')->on('interns')->cascadeOnDelete();
            $table->foreign('approved_by')->references('id')->on('admins')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_requests');
    }
};
