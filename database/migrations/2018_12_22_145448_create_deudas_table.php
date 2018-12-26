<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeudasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deudas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcliente')->unsigned();
            $table->foreign('idcliente')->references('id')->on('personas');
            $table->decimal('valor',11,2);
            $table->decimal('anterior',11,2);
            $table->decimal('pagado',11,2);
            $table->decimal('total',11,2);
            $table->tinyInteger('estado')->default(1); // 0 = cancelado, 1 = activo, 2 = pagado
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
        Schema::dropIfExists('deudas');
    }
}
