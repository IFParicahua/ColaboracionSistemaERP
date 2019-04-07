<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Nombre');
            $table->date('FechaInicio');
            $table->date('FechaFin');
            $table->integer('PkEmpresa')->unsigned();
            $table->foreign('PkEmpresa')->references('id')->on('empresa');
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
        Schema::dropIfExists('gestion');
    }
}
