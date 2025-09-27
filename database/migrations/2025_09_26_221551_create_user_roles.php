<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer l'ancienne colonne si elle existe
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            
            // Ajouter la nouvelle colonne avec les bons valeurs
            $table->enum('role', ['admin', 'fournisseur', 'utilisateur', 'organisateur', 'participant'])
                  ->default('utilisateur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
