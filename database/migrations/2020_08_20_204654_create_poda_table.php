<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePodaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podas', function (Blueprint $table) {
            $table->bigIncrements('id');                  
            $table->unsignedBigInteger('id_season')->unsigned();
            $table->foreign('id_season')
                ->references('id')
                ->on('seasons')
                ->onDelete('cascade')
                ->onUpdate('cascade');              
            $table->unsignedBigInteger('id_farm')->unsigned();
            $table->foreign('id_farm')
                ->references('id')
                ->on('farms')
                ->onDelete('cascade')
                ->onUpdate('cascade'); 
            $table->date('date')->nullable();
            $table->float('total')->nullable();
            $table->unsignedBigInteger('id_user')->unsigned();
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('id_client')->unsigned();
                $table->foreign('id_client')
                    ->references('id')
                    ->on('clients')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
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
        Schema::dropIfExists('poda');
    }
}
