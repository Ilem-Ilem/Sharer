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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('mention_notifications')->default(true);
            $table->boolean('comment_notifications')->default(true);
            $table->boolean('collab_notifications')->default(true);
            $table->boolean('update_notifications')->default(false);
            $table->boolean('realtime_notifications')->default(true);
            $table->boolean('reminder_notifications')->default(false);
            $table->string('notification_frequency')->default('immediate');
            $table->string('font_size')->default('medium');
            $table->string('font_family')->default('Inter');
            $table->boolean('autosave')->default(true);
            $table->boolean('spellcheck')->default(true);
            $table->boolean('search_engine_indexing')->default(false);
            $table->boolean('online_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            //
        });
    }
};
