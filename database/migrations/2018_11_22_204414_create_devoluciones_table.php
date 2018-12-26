<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idventa')->unsigned();
            $table->foreign('idventa')->references('id')->on('ventas');
            $table->integer('idusuario')->unsigned();
            $table->foreign('idusuario')->references('id')->on('users');
            $table->dateTime('fecha_hora');
            $table->decimal('total', 11, 2);
            $table->boolean('estado')->default(1);
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
        Schema::dropIfExists('devoluciones');
    }
}
