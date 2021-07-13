<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_employee')->unsigned();
            $table->foreign('id_employee')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('employee_rut');
            $table->foreign('employee_rut')
                ->references('rut')
                ->on('employees')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('working_day', 45);
            $table->string('countscol', 45);
            $table->date('date')->nullable();
            $table->unsignedBigInteger('id_quarter')->unsigned();
            $table->foreign('id_quarter')
                ->references('id')
                ->on('quarters')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('quarter_name');
            $table->foreign('quarter_name')
                ->references('name')
                ->on('quarters')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('id_season')->unsigned();
            $table->foreign('id_season')
                ->references('id')
                ->on('seasons')
                ->onDelete('cascade')
                ->onUpdate('cascade');            
            $table->string('season_name');
            $table->foreign('season_name')
                ->references('name')
                ->on('seasons')
                ->onDelete('cascade')
                ->onUpdate('cascade'); 
            $table->unsignedBigInteger('id_farm')->unsigned();
            $table->foreign('id_farm')
                ->references('id')
                ->on('farms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('farm_name');
            $table->foreign('farm_name')
                ->references('name')
                ->on('farms')
                ->onDelete('cascade')
                ->onUpdate('cascade'); 
            $table->boolean('registered_in_batch')->default(false);
            $table->integer('row')->nullable();
            $table->string('plant', 45)->nullable();
            $table->integer('cant')->nullable();
            $table->integer('obj')->nullable();
            $table->string('diff', 45)->nullable();
            $table->unsignedBigInteger('id_user')->unsigned();
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('counts');
    }
}
