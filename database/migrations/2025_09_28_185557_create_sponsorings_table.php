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
        Schema::create('sponsorings', function (Blueprint $table) {
            $table->id();
            $table->float('montant');
            $table->unsignedBigInteger('type_sponsoring_id');
            $table->date('date');
            $table->unsignedBigInteger('partenaire_id');
            $table->unsignedBigInteger('evenement_id');
            $table->timestamps();

            $table->foreign('type_sponsoring_id')->references('id')->on('type_sponsorings')->onDelete('cascade');
            $table->foreign('partenaire_id')->references('id')->on('partners')->onDelete('cascade');
            $table->foreign('evenement_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsorings');
    }
};
