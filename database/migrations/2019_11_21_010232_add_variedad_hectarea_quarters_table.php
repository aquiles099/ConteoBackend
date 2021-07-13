<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariedadHectareaQuartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarters', function (Blueprint $table) {
            $table->string('hectareas');
            $table->string('variety');
            $table->string('portainjerto');
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
            $table->string('hectareas');
            $table->string('variety');
            $table->string('portainjerto');
        });
    }
}
