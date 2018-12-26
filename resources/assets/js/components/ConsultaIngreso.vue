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
                    <i class="fa fa-align-justify"></i> Ingresos
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
                                    <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th># Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                        <td>
                                            <button type="button" @click="verIngreso(ingreso.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button>
                                        </td>
                                        <td v-text="ingreso.usuario"></td>
                                        <td v-text="ingreso.nombre"></td>
                                        <td v-text="ingreso.tipo_comprobante"></td>
                                        <td v-text="ingreso.serie_comprobante"></td>
                                        <td v-text="ingreso.num_comprobante"></td>
                                        <td v-text="ingreso.fecha_hora"></td>
                                        <td v-text="ingreso.total"></td>
                                        <td v-text="ingreso.impuesto"></td>
                                        <td v-text="ingreso.estado"></td>
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
                <!-- Ver ingreso -->
                <template v-else-if="listado == 2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor</label>
                                    <p v-text="proveedor"></p>
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
                                    <label for="">Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Número Comprobante</label>
                                    <p v-text="num_comprobante"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo">
                                            </td>
                                            <td v-text="detalle.precio">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td>
                                                {{ detalle.precio * detalle.cantidad }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right">Total Parcial:</td>
                                            <td>$ {{ total_parcial = total - total_impuesto }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right">Total Impuesto:</td>
                                            <td>$ {{ total_impuesto = (total*impuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right">Total Neto:</td>
                                            <td>$ {{ total }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay artículos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Fin ver ingreso -->
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
            ingreso_id: 0,
            idproveedor : 0,
            proveedor: '',
            nombre: '',
            tipo_comprobante : 'FACTURA',
            serie_comprobante : '',
            num_comprobante : '',
            impuesto : 0,
            total : 0.0,
            total_impuesto : 0.0,
            total_parcial: 0.0,
            arrayIngreso : [],
            arrayDetalle : [],
            arrayProveedor: [],
            listado: 1,
            modal : 0,
            tituloModal : '',
            tipoAccion : 0,
            errorIngreso : 0,
            errorMostrarMsjIngreso : [],
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
            arrayArticulo: [],
            idarticulo: 0,
            codigo: '',
            articulo: '',
            precio: 0,
            cantidad: 0,
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
                resultado += this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad;
            }
            return resultado;
        }
    },
    methods : {
        listarIngreso (page,buscar,criterio){
            let me=this;
            var url= this.ruta + '/ingreso?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayIngreso = respuesta.ingresos.data;
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
            me.listarIngreso(page,buscar,criterio);
        },
        mostrarDetalle() {
            this.listado = 0;
            this.idproveedor = 0;
            this.tipo_comprobante = 'FACTURA';
            this.serie_comprobante = '';
            this.num_comprobante = '';
            this.impuesto = 0;
            this.total = 0;
            this.idarticulo = 0;
            this.articulo = '';
            this.cantidad = 0;
            this.precio = 0;
            this.arrayDetalle = [];
        },
        ocultarDetalle() {
            this.listado = 1;
        },
        verIngreso(id) {
            this.listado = 2;
            let me = this;
            // Obtener datos del ingreso
            var arrayIngresoTemp = [];
            var url= this.ruta + '/ingreso/obtenerCabecera?id='+ id;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                arrayIngresoTemp = respuesta.ingreso;

                me.proveedor = arrayIngresoTemp[0]['nombre'];
                me.tipo_comprobante = arrayIngresoTemp[0]['tipo_comprobante'];
                me.serie_comprobante = arrayIngresoTemp[0]['serie_comprobante'];
                me.num_comprobante = arrayIngresoTemp[0]['num_comprobante'];
                me.impuesto = arrayIngresoTemp[0]['impuesto'];
                me.total = arrayIngresoTemp[0]['total'];
            })
            .catch(function (error) {
                console.log(error);
            });
            // Obtener datos de los detalles
            var url= this.ruta + '/ingreso/obtenerDetalles?id='+ id;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayDetalle = respuesta.detalles;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
    },
    mounted() {
        this.listarIngreso(1,this.buscar,this.criterio);
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
