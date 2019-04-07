<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallecomprobanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallecomprobante', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Numero');
            $table->string('Glosa')->nullable();
            $table->decimal('MontoDebe', 12, 2)->nullable();
            $table->decimal('MontoHaber', 12, 2)->nullable();
            $table->decimal('MontoDebeAlt', 12, 2)->nullable();
            $table->decimal('MontoHaberAlt', 12, 2)->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('PkComprobante')->unsigned();
            $table->foreign('PkComprobante')->references('id_comprobante')->on('comprobante');
            $table->integer('PkCuenta')->unsigned();
            $table->foreign('PkCuenta')->references('id')->on('cuenta');

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
        Schema::dropIfExists('detallecomprobante');
    }
}
