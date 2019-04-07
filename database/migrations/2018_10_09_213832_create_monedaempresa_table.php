<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonedaempresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monedaempresa', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('Cambio',12,2)->nullable();
            $table->integer('Activo');
            $table->dateTime('FechaRegistro');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('Principal')->unsigned();
            $table->foreign('Principal')->references('id')->on('moneda');
            $table->integer('Alternativa')->unsigned();
            $table->foreign('Alternativa')->references('id')->on('moneda');
            $table->integer('PkEmpresa')->unsigned();
            $table->foreign('PkEmpresa')->references('id')->on('empresa');
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
        Schema::dropIfExists('monedaempresa');
    }
}
