<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->string('type', 50)->index(); // e.g. view, like, search, register
            $table->integer('value')->nullable(); // optional numeric score
            $table->json('metadata')->nullable(); // flexible data: query, user_agent, ip, duration
            $table->timestamps();

            // Useful composite index for common queries
            $table->index(['user_id', 'event_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
