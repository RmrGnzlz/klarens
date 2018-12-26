<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_salidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idsalida')->unsigned();
            $table->integer('iddetalle')->unsigned();
            $table->integer('cantidad')->unsigned();
            $table->foreign('idsalida')->references('id')->on('salidas');
            $table->foreign('iddetalle')->references('id')->on('detalle_inventarios');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_salidas');
    }
}
