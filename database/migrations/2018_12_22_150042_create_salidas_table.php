<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idvendedor')->unsigned()->nullable();
            $table->integer('idconductor')->unsigned();
            $table->integer('idvehiculo')->unsigned();
            $table->decimal('total',11,2);
            $table->tinyInteger('estado')->default(1); // 0 = cancelado, 1 = activo, 2 = finalizado
            $table->foreign('idvendedor')->references('id')->on('vendedores');
            $table->foreign('idconductor')->references('id')->on('conductores');
            $table->foreign('idvehiculo')->references('id')->on('vehiculos');
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
        Schema::dropIfExists('salidas');
    }
}
