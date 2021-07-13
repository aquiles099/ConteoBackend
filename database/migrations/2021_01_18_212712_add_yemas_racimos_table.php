<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYemasRacimosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('poda_cuartels', function (Blueprint $table) {            
            $table->string('racimos_cargador_potencialesA2', 45);
            $table->string('cargadores_plantas_alt2', 45);
            $table->string('yemas_cargador_alt2', 45);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
