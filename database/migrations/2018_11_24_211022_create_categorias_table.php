<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('CategoriaPadre_id')->nullable()->unsigned();
            $table->string('Nombre');
            $table->string('Descripcion');
            $table->integer('PkEmpresa')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('PkEmpresa')->references('id')->on('empresa');
            $table->foreign('CategoriaPadre_id')->references('id')->on('categorias');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('categorias');
    }
}
