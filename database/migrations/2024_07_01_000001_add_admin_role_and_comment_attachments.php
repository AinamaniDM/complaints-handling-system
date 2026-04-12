<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add admin_role column to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('admin_role', 30)->nullable()->after('role');
            // NULL = regular user or super admin
            // Values: finance, hr, academic, facilities, it, accommodation, other
        });

        // Add attachment fields to comments
        Schema::table('comments', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('body');
            $table->string('attachment_type')->nullable()->after('attachment');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin_role');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['attachment', 'attachment_type']);
        });
    }
};
