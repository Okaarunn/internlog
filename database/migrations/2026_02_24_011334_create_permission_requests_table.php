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
            $table->uuid('id_permission_request')->primary();
            $table->foreignUuid('id_intern');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['sakit', 'izin']);
            $table->string('reason', 255);
            $table->enum('validation_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignUuid('aproved_by')->nullable();
            $table->timestamp('aproved_at')->nullable();
            $table->foreign('id_intern')->references('id_intern')->on('interns')->onDelete('cascade');
            $table->foreign('aproved_by')->references('id_admin')->on('admins')->nullOnDelete();
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
