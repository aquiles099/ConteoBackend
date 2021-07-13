<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormacionCuartelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarters', function (Blueprint $table) {            
            $table->string('formacion', 45);
            $table->string('edad_plantas', 45);
            $table->string('distancia_entrehilera', 45);
            $table->string('distancia_sobrehilera', 45);
            $table->string('densidad_plantas', 45);
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
