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
        Schema::table('sponsorings', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['type_sponsoring_id']);
            
            // Drop the old column
            $table->dropColumn('type_sponsoring_id');
            
            // Add new enum column
            $table->enum('type_sponsoring', ['argent', 'materiel', 'logistique', 'autre'])->after('montant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sponsorings', function (Blueprint $table) {
            // Drop enum column
            $table->dropColumn('type_sponsoring');
            
            // Restore the old structure
            $table->unsignedBigInteger('type_sponsoring_id')->after('montant');
            $table->foreign('type_sponsoring_id')->references('id')->on('type_sponsorings')->onDelete('cascade');
        });
    }
};