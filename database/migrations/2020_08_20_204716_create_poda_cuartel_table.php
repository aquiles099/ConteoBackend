<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePodaCuartelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poda_cuartels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_poda')->unsigned();
            $table->foreign('id_poda')
                ->references('id')
                ->on('podas')
                ->onDelete('cascade')
                ->onUpdate('cascade');  
            $table->unsignedBigInteger('id_quarter')->unsigned();
            $table->foreign('id_quarter')
                ->references('id')
                ->on('quarters')
                ->onDelete('cascade')
                ->onUpdate('cascade'); 
            $table->string('densidad_plantas', 45);
            $table->string('plantas_muertas', 45);
            $table->string('medias_plantas', 45);
            $table->string('porcentaje_desecho', 45);            
            $table->string('causa_desecho', 45);
            $table->string('cargadores_plantas', 45);
            $table->string('yemas_cargador', 45);
            $table->string('pitones_plantas', 45);
            $table->string('yemas_piton', 45);
            $table->string('n_yemas_plantas', 45);
            $table->string('n_yemas_hectareas', 45);
            $table->string('yemas_fertiles', 45);
            $table->string('racimos_plantas_potenciales', 45);
            $table->string('racimos_cargador_potenciales', 45);
            $table->string('brotacion_esperada', 45);
            $table->string('brotes_chupados', 45);
            $table->string('porcentaje_racimos_chico', 45);
            $table->string('porcentaje_racimos_medianos', 45);
            $table->string('porcentaje_racimos_grandes', 45);
            $table->string('gr_racimos_chico', 45);
            $table->string('gr_racimos_medianos', 45);
            $table->string('gr_racimos_grandes', 45);
            $table->string('racimos_efectivos_hectareas', 45);
            $table->string('racimos_deseados_planta', 45);
            $table->string('racimos_deseados_hectareas', 45);
            $table->string('racimos_eliminar', 45);   
            $table->string('tipo_arreglo', 45);
            $table->string('n_plantasha_densidad', 45);
            $table->string('n_plantasha', 45);
            $table->string('porcentaje_descarte', 45);
            $table->string('kilos_frutasha_efectivos', 45);
            $table->string('kilos_frutasha_deseados', 45);
            $table->string('cajas82ha_efectivas', 45);
            $table->string('cajas82ha_deseados', 45);
            $table->string('n_cajas_deseada_planta', 45);
            $table->string('balance_cajas_efectiva_deseadas', 45);
            $table->string('cajas82ha_deseadas_cuartel', 45);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poda_cuartel');
    }
}
