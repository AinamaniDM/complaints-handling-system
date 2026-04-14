<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add category_id, attachment, attachment_type to complaints
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('restrict')->after('user_id');
            $table->string('attachment')->nullable()->after('status');
            $table->string('attachment_type')->nullable()->after('attachment');
        });

        // Step 2: Create comments table
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('body');
            $table->timestamps();
            $table->index('complaint_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn(['attachment', 'attachment_type']);
        });
    }
};
