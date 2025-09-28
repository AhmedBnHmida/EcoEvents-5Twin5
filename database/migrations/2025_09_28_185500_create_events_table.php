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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location', 255)->nullable();
            $table->integer('capacity_max');
            $table->unsignedBigInteger('association_id');
            $table->unsignedBigInteger('categorie_id');
            $table->enum('status', ['UPCOMING', 'ONGOING', 'CANCELLED', 'COMPLETED'])->default('UPCOMING');
            $table->dateTime('registration_deadline')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('is_public')->default(true);
            $table->json('images')->nullable();
            $table->timestamps();

            $table->foreign('association_id')->references('id')->on('associations')->onDelete('cascade');
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
