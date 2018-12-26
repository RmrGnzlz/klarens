<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_inventarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idinventario')->unsigned();
            $table->foreign('idinventario')->references('id')->on('inventarios')->onDelete('cascade');
            $table->integer('idproducto')->unsigned();
            $table->foreign('idproducto')->references('id')->on('productos');
            $table->integer('cantidad_inicial')->unsigned();
            $table->integer('cantidad')->unsigne();
            $table->decimal('precio_compra',11,2);
            $table->decimal('precio',11,2);
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
        Schema::dropIfExists('detalle_inventarios');
    }
}
