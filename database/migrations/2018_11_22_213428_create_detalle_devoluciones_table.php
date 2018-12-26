<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleDevolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_devoluciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iddevolucion')->unsigned();
            $table->foreign('iddevolucion')->references('id')->on('devoluciones')->onDelete('cascade');
            $table->integer('iddetalle')->unsigned();
            $table->foreign('iddetalle')->references('id')->on('detalle_inventarios');
            $table->integer('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_devoluciones');
    }
}
