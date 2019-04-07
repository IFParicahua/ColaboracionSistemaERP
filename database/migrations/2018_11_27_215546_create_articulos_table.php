<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Nombre');
            $table->string('Descripcion');
            $table->integer('Cantidad');
            $table->decimal('PrecioVenta', 12, 2)->nullable();
            $table->integer('PkEmpresa')->unsigned();
            $table->foreign('PkEmpresa')->references('id')->on('empresa');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('PkCategoria')->unsigned();
            $table->foreign('PkCategoria')->references('id')->on('categorias');
            $table->string('Demanda')->nullable();
            $table->string('TiempoEspera')->nullable();
            $table->decimal('CostoOrden', 12, 2)->nullable();
            $table->decimal('CostoInventario', 12, 2)->nullable();
            $table->string('PuntoNuevoPedido')->nullable();
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
        Schema::dropIfExists('articulos');
    }
}
