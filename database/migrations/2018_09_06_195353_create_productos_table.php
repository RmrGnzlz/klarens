<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcategoria')->unsigned();
            $table->string('codigo',20)->nullable();
            $table->string('nombre',100)->unique();
            $table->decimal('precio_venta',11,2);
            $table->decimal('impuesto',4,2)->default(0);
            $table->integer('stock')->unsigned();
            $table->string('descripcion',250)->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
            $table->foreign('idcategoria')->references('id')->on('categorias');
        });
        DB::table('productos')->insert(array('idcategoria'=>'1', 'codigo' => '123456', 'nombre' => 'Leche x 200 c.c.', 'precio_venta' => 2000, 'stock' => 0, 'descripcion' => 'Leche pequeña de medio litro'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
