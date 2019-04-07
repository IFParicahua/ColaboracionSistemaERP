<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('NroLote')->nullable();
            $table->date('FechaIngreso')->nullable();
            $table->date('FechaVencimiento')->nullable();
            $table->integer('Cantidad')->nullable();
            $table->decimal('PrecioCompra',12,2);
            $table->integer('Stock')->nullable();;
            $table->integer('PkEmpresa')->unsigned();
            $table->foreign('PkEmpresa')->references('id')->on('empresa');
            $table->integer('PkArticulo')->unsigned();
            $table->foreign('PkArticulo')->references('id')->on('articulos');
            $table->integer('PkNota')->unsigned();
            $table->foreign('PkNota')->references('id')->on('notas');
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
        Schema::dropIfExists('lotes');
    }
}
