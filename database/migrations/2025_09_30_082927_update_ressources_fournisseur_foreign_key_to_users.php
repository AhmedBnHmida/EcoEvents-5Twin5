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
        Schema::table('ressources', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['fournisseur_id']);
            
            // Add new foreign key constraint referencing users table
            $table->foreign('fournisseur_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ressources', function (Blueprint $table) {
            // Drop the users foreign key constraint
            $table->dropForeign(['fournisseur_id']);
            
            // Restore the old fournisseurs foreign key constraint
            $table->foreign('fournisseur_id')
                  ->references('id')
                  ->on('fournisseurs')
                  ->onDelete('cascade');
        });
    }
};
