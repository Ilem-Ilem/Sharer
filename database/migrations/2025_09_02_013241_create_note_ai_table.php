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
        Schema::create('note_ai', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->longText('summary')->nullable();
            $table->json('keywords')->nullable();
            $table->json('embedding')->nullable();
            $table->json('topics')->nullable();
            $table->json('qa_cache')->nullable();
            $table->string('generated_by', 100)->nullable();
            $table->timestamps();
            $table->index('note_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_ai');
    }
};
