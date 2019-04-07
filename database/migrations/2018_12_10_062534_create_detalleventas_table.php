<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleventasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalleventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('PkNota')->unsigned()->nullable();
            $table->foreign('PkNota')->references('id')->on('notas');
            $table->integer('NroLote')->unsigned()->nullable();
            $table->foreign('NroLote')->references('id')->on('lotes');
            $table->integer('Cantidad')->nullable();
            $table->decimal('PrecioVenta', 12, 2)->nullable();
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
        Schema::dropIfExists('detalleventas');
    }
}
