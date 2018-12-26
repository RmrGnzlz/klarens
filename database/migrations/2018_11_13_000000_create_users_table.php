<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');
            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('condicion')->default(1);
            $table->integer('idrol')->unsigned();
            $table->foreign('idrol')->references('id')->on('roles');
            
            $table->rememberToken();
            //$table->timestamps();
        });
        DB::table('users')->insert(array('id'=>1, 'usuario'=>'rmrgnzlzlpz', 'password' => '$2y$10$e0qX6zJ85PJDSKnLy62TyOIPDtfnOQBqu.iK12mD1EqjtTPc2p7Nu', 'condicion' => '1', 'idrol' => '1'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
