<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Nombre');
            $table->date('FechaInicio');
            $table->date('FechaFin');
            $table->integer('PkGestion')->unsigned();
            $table->foreign('PkGestion')->references('id')->on('gestion');
            $table->integer('Estado')->unsigned();
            $table->foreign('Estado')->references('id')->on('concepto');
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
        Schema::dropIfExists('periodo');
    }
}
