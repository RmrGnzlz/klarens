
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100)->unique();
            $table->string('tipo_documento', 20)->nullable();
            $table->string('num_documento', 20)->nullable();
            $table->string('direccion', 70)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->decimal('deuda', 11, 2)->default(0);
            $table->timestamps();
        });
        DB::table('personas')->insert(array('nombre'=>'Ramiro González', 'tipo_Documento'=>'CEDULA', 'num_documento' => '1082480166', 'direccion' => 'SISTEMA', 'telefono' => '3107243407', 'email' => 'rmrgnzlzlpz@gmail.com'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
