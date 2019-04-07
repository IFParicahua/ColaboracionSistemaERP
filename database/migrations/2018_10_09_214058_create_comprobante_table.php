<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprobanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobante', function (Blueprint $table) {
            $table->increments('id_comprobante');
            $table->integer('Serie');
            $table->string('Glosa');
            $table->date('Fecha');
            $table->decimal('TC',12,2)->nullable();
            $table->string('TipoComprobante');
            $table->integer('PkEmpresa')->unsigned();;
            $table->foreign('PkEmpresa')->references('id')->on('empresa');
            $table->integer('Estado')->unsigned();;
            $table->foreign('Estado')->references('id')->on('concepto');
            $table->integer('user_id')->unsigned();;
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('PkMoneda')->unsigned();;
            $table->foreign('PkMoneda')->references('id')->on('moneda');
            $table->integer('PkPeriodo')->unsigned();;
            $table->foreign('PkPeriodo')->references('id')->on('periodo');
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
        Schema::dropIfExists('comprobante');
    }
}
