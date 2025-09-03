<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collaborations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('collaborator_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['editor', 'viewer'])->default('editor');
            $table->enum('status', ['active', 'ended'])->default('active');
            $table->unsignedInteger('current_page')->nullable()->default(null); // New column for page tracking
            $table->timestamp('last_active_at')->nullable(); // To track activity and clean up stale data
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index('expires_at');
            $table->index(['note_id', 'collaborator_id', 'status']); // Optimize queries for active collaborators
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborations');
    }
};
