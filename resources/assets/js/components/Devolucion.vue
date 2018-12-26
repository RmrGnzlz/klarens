<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Devoluciones
                </div>
                <!-- Listado -->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Número Comprobante</option>
                                        <option value="fecha_hora">Fecha</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <button type="button" class="btn btn-success disabled">Ver Devoluciones</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
                                        <th>Tipo Comprobante</th>
                                        <th># Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td>
                                            <div v-if="venta.estado == 'Anulado'">
                                                <button type="button" class="btn btn-secondary disabled btn-sm">
                                                    Hacer Devolución
                                                </button>
                                            </div>
                                            <div v-else>
                                                <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                                    Hacer Devolución
                                                </button>
                                            </div>
                                        </td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.tipo_comprobante"></td>
                                        <td v-text="venta.num_comprobante"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.impuesto"></td>
                                        <td>
                                            <div v-if="venta.estado == 'Registrado'">
                                                <span class="badge badge-success">Registrado</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger" v-text="venta.estado"></span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!-- Detalle -->
                <template v-else-if="listado == 0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="impuesto"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Número Comprobante</label>
                                    <p v-text="num_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="alert alert-danger" v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Total Venta</label>
                                    <!-- <input type="number" class="form-control" :value="total" disabled> -->
                                    <div class="alert alert-success">$ {{ total }}.00</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Total Pagado</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="number" class="form-control" step="50" min="0" :max="total" v-model="pagado">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Total Deuda</label>
                                    <div class="alert alert-danger">$ {{total - pagado}}.00
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Entregado(s)</th>
                                            <th>Devuelto(s)</th>
                                            <th>Vendido(s)</th>
                                            <th>Descuento (Vendidos)</th>
                                            <th>$ Devolución</th>
                                            <th>$ Venta</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle) in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo">
                                                Artículo nombre
                                            </td>
                                            <td v-text="detalle.precio">
                                            </td>
                                            <td v-text="detalle.cantidadinicial">
                                            </td>
                                            <td>
                                                <span class="text-danger" v-show="detalle.cantidad > detalle.cantidadinicial">Máximo devolución: {{ detalle.cantidadinicial }}</span>
                                                <input v-model="detalle.cantidad" type="number" min="0" :max="detalle.cantidadinicial" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" disabled :value="detalle.cantidadfinal = detalle.cantidadinicial - detalle.cantidad">
                                            </td>
                                            <td>
                                                <span class="text-danger" v-show="detalle.descuento > (detalle.precio * detalle.cantidadfinal)">Descuento mayor al subtotal</span>
                                                <input v-model="detalle.descuento" type="number" min="0" class="form-control" :max="detalle.precio * detalle.cantidadfinal">
                                            </td>
                                            <td>
                                                {{ detalle.precio * detalle.cantidad}}
                                            </td>
                                            <td>
                                                {{ detalle.precio * detalle.cantidadfinal - detalle.descuento}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right">Total Parcial Devolución:</td>
                                            <td>$ {{ total_parcial_dev = total_dev - total_impuesto_dev }}</td>
                                            <td colspan="3" align="right">Total Parcial Venta:</td>
                                            <td>$ {{ total_parcial = total - total_impuesto }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right">Total Impuesto Devolución:</td>
                                            <td>$ {{ total_impuesto_dev = ((total_dev*impuesto)/(1+impuesto)).toFixed(2) }}</td>
                                            <td colspan="3" align="right">Total Impuesto Venta:</td>
                                            <td>$ {{ total_impuesto = ((total*impuesto)/(1+impuesto)).toFixed(2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right">Total Neto Devolución:</td>
                                            <td>$ {{ total_dev = calcularTotalDev }}</td>
                                            <td colspan="3" align="right">Total Neto Venta:</td>
                                            <td>$ {{ total = calcularTotal }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No hay artículos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" @click="ocultarDetalle()">Cancelar</button>
                                <button type="button" class="btn btn-primary" @click="registrarDevolucion()">Guardar Cambios</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Fin Detalle -->
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
        props : ['ruta'],
        data (){
            return {
                venta_id: 0,
                idcliente : 0,
                idvendedor: 0,
                cliente: '',
                vendedor: '',
                tipo_comprobante : 'FACTURA',
                num_comprobante : '',
                impuesto : 0,
                total : 0.0,
                pagado: 0,
                total_dev: 0.0,
                total_impuesto : 0.0,
                total_impuesto_dev : 0.0,
                total_parcial: 0.0,
                total_parcial_dev: 0.0,
                pagado: 0,
                arrayVenta : [],
                arrayDetalle : [],
                listado: 1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorVenta : 0,
                errorMostrarMsjVenta : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'num_comprobante',
                buscar : '',
                criterioA: 'nombre',
                buscarA: '',
                idarticulo: 0,
                codigo: '',
                articulo: '',
                stock: 0,
                precio: 0,
                cantidad: 0,
                descuento: 0
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularTotal: function() {
                var resultado = 0.0;
                for(var i = 0; i < this.arrayDetalle.length; i++) {
                    resultado += this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidadfinal - this.arrayDetalle[i].descuento;
                }
                return resultado;
            },
            calcularTotalDev: function() {
                var resultado = 0.0;
                for(var i = 0; i < this.arrayDetalle.length; i++) {
                    resultado += this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad;
                }
                return resultado;
            }
        },
        methods : {
            listarVenta (page,buscar,criterio){
                let me=this;
                var url= this.ruta + '/venta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayVenta = respuesta.ventas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarVenta(page,buscar,criterio);
            },
            registrarDevolucion(){
                if (this.validarVenta()){
                    return;
                }
                
                let me = this;

                axios.post(this.ruta + '/devolucion/registrar', {
                    'id': this.venta_id,
                    'total' : this.total,
                    'total_dev': this.total_dev,
                    'idcliente': this.idcliente,
                    'idvendedor': this.idvendedor,
                    'tipo_comprobante': this.tipo_comprobante,
                    'num_comprobante' : this.num_comprobante,
                    'impuesto' : this.impuesto,
                    'pagado': this.pagado,
                    'data' : this.arrayDetalle,
                }).then(function (response) {
                    me.listado = 1;
                    me.listarVenta(1,'','num_comprobante');
                    me.idcliente = 0;
                    me.idvendedor = 0;
                    me.tipo_comprobante = 'FACTURA';
                    me.impuesto = 0;
                    me.total = 0;
                    me.pagado = 0;
                    me.arrayDetalle = [];
                    me.pdfVenta(response.data.id);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarVenta(){
                let me = this;
                me.errorVenta = 0;
                me.errorMostrarMsjVenta = [];
                var art;

                me.arrayDetalle.map(function(x) {
                    if(x.cantidad > x.cantidadinicial) {
                        art = x.articulo + ': No puede devolverse más de lo vendido.';
                        me.errorMostrarMsjVenta.push(art);
                    }
                    if(x.descuento > x.cantidadfinal*x.precio) {
                        art = x.articulo + ' con descuento mayor al subtotal'
                        me.errorMostrarMsjVenta.push(art);
                    }
                })
                if(me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                return me.errorVenta;
            },
            ocultarDetalle() {
                this.listado = 1;
                this.idproveedor = 0;
                this.tipo_comprobante = 'FACTURA';
                this.num_comprobante = '';
                this.impuesto = 0;
                this.total = 0;
                this.arrayDetalle = [];
                this.venta_id = 0;
                this.errorMostrarMsjVenta = [];
            },
            verVenta(id) {
                this.listado = 0;
                let me = this;
                me.venta_id = id;
                // Obtener datos de la venta
                var arrayVentaTemp = [];
                var url=this.ruta + '/venta/obtenerCabecera?id='+ id;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayVentaTemp = respuesta.venta;

                    me.cliente = arrayVentaTemp[0]['nombre'];
                    me.tipo_comprobante = arrayVentaTemp[0]['tipo_comprobante'];
                    me.num_comprobante = arrayVentaTemp[0]['num_comprobante'];
                    me.impuesto = arrayVentaTemp[0]['impuesto'];
                    me.total = arrayVentaTemp[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });
                // Obtener datos de los detalles
                var url=this.ruta + '/venta/obtenerDetalles?id='+ id;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    respuesta.detalles.map(function(x) {
                        x.cantidadinicial = x.cantidad;
                        x.cantidad = 0;
                        x.cantidadfinal = x.cantidadinicial - x.cantidad;
                    })
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
                console.log(this.arrayDetalle);
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            },
            abrirModal(){
                this.arrayArticulo = [];
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios artículos'
            },
            desactivarVenta(id){
               swal({
                title: '¿Está seguro de anular esta venta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put(this.ruta + '/venta/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarVenta(1,'','num_comprobante');
                        swal(
                        '¡Anulada!',
                        'La venta ha sido anulada con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
        },
        mounted() {
            this.listarVenta(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
</style>
