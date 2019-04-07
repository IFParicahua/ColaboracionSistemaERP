<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Codigo');
            $table->string('Nombre');
            $table->string('Nivel');
            $table->string('TipoCuenta');
            $table->integer('idUser')->unsigned();
            $table->integer('PkEmpresa')->unsigned();
            $table->integer('CuentaPadre')->nullable()->unsigned();;
            $table->foreign('CuentaPadre')->references('id')->on('cuenta');
            $table->foreign('PkEmpresa')->references('id')->on('empresa');
            $table->foreign('idUser')->references('id')->on('users');
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
        Schema::dropIfExists('cuenta');
    }
}
