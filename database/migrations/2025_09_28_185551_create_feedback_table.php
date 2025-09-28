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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('id_feedback');
            $table->unsignedBigInteger('id_evenement');
            $table->unsignedBigInteger('id_participant');
            $table->unsignedTinyInteger('note');
            $table->text('commentaire')->nullable();
            $table->dateTime('date_feedback');
            $table->timestamps();

            $table->foreign('id_evenement')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('id_participant')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
