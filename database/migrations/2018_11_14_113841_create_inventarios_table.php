<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idingreso')->unsigned()->nullable();
            $table->foreign('idingreso')->references('id')->on('ingresos');
            $table->integer('idusuario')->unsigned();
            $table->foreign('idusuario')->references('id')->on('users');
            $table->dateTime('inicio');
            $table->dateTime('fin')->nullable();
            $table->decimal('valor',11,2);
            $table->decimal('vendido',11,2)->default(0);
            $table->tinyInteger('estado')->default(1); // 0 = cancelado, 1 = activo, 2 = finalizado
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
        Schema::dropIfExists('inventarios');
    }
}
