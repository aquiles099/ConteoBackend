<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroPlantaRacimosQuarterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarters', function (Blueprint $table) {
            $table->integer('number_plants');
            $table->integer('number_plants_equivalent');
            $table->integer('number_plants_ha');
            $table->integer('number_plants_equivalent_ha');
            $table->integer('number_clusters_desired');
            $table->integer('number_clusters_ha');
            $table->integer('number_clusters_for_quarters');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quarters', function (Blueprint $table) {
            $table->integer('number_plants');
            $table->integer('number_plants_equivalent');
            $table->integer('number_plants_ha');
            $table->integer('number_plants_equivalent_ha');
            $table->integer('number_clusters_desired');
            $table->integer('number_clusters_ha');
            $table->integer('number_clusters_for_quarters');
        });
    }
}
