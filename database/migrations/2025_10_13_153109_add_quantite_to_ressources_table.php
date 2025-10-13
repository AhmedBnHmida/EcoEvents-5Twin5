<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantiteToRessourcesTable extends Migration
{
    public function up()
    {
        Schema::table('ressources', function (Blueprint $table) {
            $table->integer('quantite')->after('type')->default(1); // Choisis le default adapté
        });
    }

    public function down()
    {
        Schema::table('ressources', function (Blueprint $table) {
            $table->dropColumn('quantite');
        });
    }
}