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
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('username', 150)->unique();
            $table->string('avatar', 255)->nullable();
            $table->string('cover_photo', 255)->nullable();
            $table->text('bio')->nullable();
            $table->string('location', 255)->nullable();
            $table->string('occupation', 255)->nullable();
            $table->string('field_of_study', 255)->nullable();
            $table->string('education', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->json('social_links')->nullable();
            $table->integer('followers_count')->default(0);
            $table->integer('following_count')->default(0);
            $table->integer('notes_count')->default(0);
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->string('language', 10)->default('en');
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
