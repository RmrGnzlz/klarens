<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',50);
            $table->string('descripcion',250)->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
        DB::table('categorias')->insert(array('nombre'=>'Lácteos', 'descripcion' => 'Todos los productos lácteos, yogures, mantequillas, etc.'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}
