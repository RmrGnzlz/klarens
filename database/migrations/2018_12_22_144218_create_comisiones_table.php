<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComisionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisiones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idvendedor')->unsigned();
            $table->foreign('idvendedor')->references('id')->on('vendedores');
            $table->dateTime('inicio');
            $table->dateTime('fin')->nullable();
            $table->decimal('porcentaje',4,2);
            $table->integer('vendido')->unsigned();
            $table->decimal('total',10,2);
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
        Schema::dropIfExists('comisiones');
    }
}
