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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('provider'); // facebook, google, github, etc.
            $table->string('provider_id'); // user ID from provider
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('avatar')->nullable();
            $table->text('token')->nullable()->encrypted(); // Encrypted access token
            $table->text('refresh_token')->nullable()->encrypted(); // Encrypted refresh token
            $table->timestamp('token_expires_at')->nullable();
            $table->json('scopes')->nullable(); // OAuth scopes
            $table->json('provider_data')->nullable(); // Additional provider metadata
            $table->enum('status', ['active', 'inactive', 'revoked'])->default('active');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_id']);
            $table->index('provider');
            $table->index('provider_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account');
    }
};
