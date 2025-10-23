<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ressources', function (Blueprint $table) {
            // Drop the old constraint
            $table->dropForeign(['fournisseur_id']);
            
            // Add the new one referencing fournisseurs
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('ressources', function (Blueprint $table) {
            // Reverse: Drop new, add back old
            $table->dropForeign(['fournisseur_id']);
            $table->foreign('fournisseur_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};