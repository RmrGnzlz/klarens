<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creando los disparadores
        // DB::unprepared('CREATE TRIGGER `tr_updStockIngreso` AFTER INSERT ON `detalle_ingresos` FOR EACH ROW BEGIN
        //     UPDATE productos SET stock = stock + NEW.cantidad
        //     WHERE articulos.id = NEW.idarticulo;
        // END');

        // DB::unprepared('CREATE TRIGGER `tr_updStockVenta` AFTER INSERT ON `detalle_ventas` FOR EACH ROW BEGIN
        //     UPDATE productos SET stock = stock - NEW.cantidad
        //     WHERE articulos.id = NEW.idarticulo;
        // END');

        // DB::unprepared("CREATE TRIGGER `tr_updStockIngresoAnular` AFTER UPDATE ON `ingresos` FOR EACH ROW BEGIN 
        //     IF(NEW.estado = 'Anulado') THEN
        //         UPDATE productos
        //         JOIN detalle_ingresos
        //         ON detalle_ingresos.idarticulo = articulos.id
        //         AND detalle_ingresos.idingreso = new.id
        //         SET articulos.stock = articulos.stock - detalle_ingresos.cantidad;
        //     END IF;
        // END");

        // DB::unprepared('CREATE TRIGGER `tr_updClienteVenta` AFTER INSERT ON `ventas` FOR EACH ROW BEGIN
        //     UPDATE personas SET deuda = deuda + NEW.total - NEW.pagado
        //     WHERE personas.id = NEW.idcliente;
        // END');

        // DB::unprepared("CREATE TRIGGER `tr_updStockVentaAnular` BEFORE UPDATE ON `ventas` FOR EACH ROW BEGIN
        //     IF(NEW.estado = 'Anulado') THEN
        //     UPDATE articulos
        //     JOIN detalle_ventas
        //     ON detalle_ventas.idarticulo = articulos.id
        //     AND detalle_ventas.idventa = NEW.id
        //     SET articulos.stock = articulos.stock + detalle_ventas.cantidad;
        //     UPDATE personas SET deuda = deuda - (OLD.total - OLD.pagado) WHERE id = NEW.idcliente;
        //     END IF;
        // END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminando disparadores
        DB::unprepared('DROP TRIGGER `tr_updStockIngreso`');
        DB::unprepared('DROP TRIGGER `tr_updStockVenta`');
        DB::unprepared('DROP TRIGGER `tr_updStockIngresoAnular`');
        DB::unprepared('DROP TRIGGER `tr_updClienteVenta`');
        DB::unprepared('DROP TRIGGER `tr_updStockVentaAnular`');
    }

}
