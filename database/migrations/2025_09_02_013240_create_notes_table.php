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
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->string('description', 1000)->nullable();
            $table->longText('content')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->enum('visibility', ['public', 'private', 'friends'])->default('public');
            $table->integer('downloads_count')->default(0);
            $table->integer('ratings_sum')->default(0);
            $table->integer('ratings_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('visibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
