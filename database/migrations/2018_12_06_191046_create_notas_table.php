<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('NroNota')->nullable();;
            $table->date('Fecha')->nullable();;
            $table->string('Detalle')->nullable();;
            $table->string('Total')->nullable();;
            $table->integer('Tipo')->nullable();;
            $table->integer('PkEmpresa')->unsigned();
            $table->foreign('PkEmpresa')->references('id')->on('empresa');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('PkComprobante')->unsigned()->nullable();;
            $table->foreign('PkComprobante')->references('id_comprobante')->on('comprobante');
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
        Schema::dropIfExists('notas');
    }
}
