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
        // add column rememberToken() to users table after column password
        Schema::table('interns', function (Blueprint $table) {
            $table->rememberToken()->after('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop column rememberToken() from users table
        Schema::table('interns', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
};
