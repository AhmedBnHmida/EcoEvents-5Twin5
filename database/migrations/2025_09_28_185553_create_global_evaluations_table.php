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
        Schema::create('global_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_evenement');
            $table->float('moyenne_notes', 3, 2);
            $table->unsignedInteger('nb_feedbacks');
            $table->float('taux_satisfaction', 5, 2);
            $table->timestamps();

            $table->foreign('id_evenement')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_evaluations');
    }
};
