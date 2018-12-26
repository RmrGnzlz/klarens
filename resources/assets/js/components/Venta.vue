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
                    <i class="fa fa-align-justify"></i> Ventas
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <!-- Listado -->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="nombre">Cliente</option>
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Número Comprobante</option>
                                        <option value="fecha_hora">Fecha</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                        <th>Comprobante</th>
                                        <th># Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Deuda</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td>
                                            <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                            <i class="icon-doc"></i>
                                            </button> &nbsp;
                                            <template v-if="venta.estado != 'Anulado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.tipo_comprobante"></td>
                                        <td v-text="venta.num_comprobante"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.impuesto"></td>
                                        <td v-text="venta.total - venta.pagado"></td>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Cliente*</label>
                                    <v-select :on-search="selectCliente" label="nombre" :options="arrayCliente" placeholder="Buscar Clientes..." :onChange="getDatosCliente">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Vendedor</label>
                                    <v-select :on-search="selectVendedor" label="nombre" :options="arrayVendedor" placeholder="Buscar Vendedores..." :onChange="getDatosVendedor">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="">Impuesto</label>
                                <input type="text" class="form-control" v-model="impuesto">
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Tipo Comprobante*</label>
                                    <select class="form-control" v-model="tipo_comprobante">
                                        <option value="0">Seleccione</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Número Comprobante*</label>
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">
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
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Artículo <span class="text-danger" v-show="idarticulo == 0">Seleccione*</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese Artículo">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Precio <span class="text-danger" v-show="precio == 0">*Ingrese</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Cantidad <span class="text-danger" v-show="cantidad == 0">*Ingrese</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Descuento</label>
                                    <input type="number" value="0" class="form-control" v-model="descuento">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
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
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo">
                                                Artículo nombre
                                            </td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" class="form-control" disabled>
                                            </td>
                                            <td>
                                                <span class="text-error" v-show="detalle.cantidad > detalle.stock">Disponible: {{ detalle.stock }}</span>
                                                <input v-model="detalle.cantidad" type="number" :min="1" :max="detalle.stock" class="form-control">
                                            </td>
                                            <td>
                                                <span class="text-error" v-show="detalle.descuento > (detalle.precio * detalle.cantidad)">Descuento mayor al subtotal</span>
                                                <input v-model="detalle.descuento" type="number" min="0" step="50" :max="detalle.precio*detalle.cantidad" class="form-control">
                                            </td>
                                            <td>
                                                $ {{ detalle.precio * detalle.cantidad - detalle.descuento }} .00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" align="right">Total Parcial:</td>
                                            <td>$ {{ total_parcial = total - total_impuesto }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" align="right">Total Impuesto:</td>
                                            <td>$ {{ total_impuesto = ((total*impuesto)/(1+impuesto)).toFixed(2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" align="right">Total Neto:</td>
                                            <td>$ {{ total = calcularTotal }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                <span class="text-danger">No hay productos agregados</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Fin Detalle -->
                <!-- Ver ingreso -->
                <template v-else-if="listado == 2">
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
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
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
                                            <td v-text="detalle.descuento">
                                            </td>
                                            <td>
                                                {{ detalle.precio * detalle.cantidad - detalle.descuento }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="right">Total Parcial:</td>
                                            <td>$ {{ total_parcial = total - total_impuesto }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="right">Total Impuesto:</td>
                                            <td>$ {{ total_impuesto = (total*impuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="right">Total Neto:</td>
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
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Código</option>
                                    </select>
                                    <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio Venta</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                        <td>
                                            <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                            <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="articulo.codigo"></td>
                                        <td v-text="articulo.nombre"></td>
                                        <td v-text="articulo.nombre_categoria"></td>
                                        <td v-text="articulo.precio_venta"></td>
                                        <td>
                                            <input v-model="articulo.numero" type="number" :min="1" :max="articulo.cantidad" class="form-control">
                                        </td>
                                        <td v-text="articulo.inicio">
                                            <!-- <div v-if="articulo.estado">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div> -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
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
                total_impuesto : 0.0,
                total_parcial: 0.0,
                pagado: 0,
                arrayVenta : [],
                arrayDetalle : [],
                arrayCliente: [],
                arrayVendedor: [],
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
                arrayArticulo: [],
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
                    resultado += this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento;
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
            selectCliente(search,loading){
                let me=this;
                loading(true)

                var url= this.ruta + '/cliente/selectCliente?filtro='+search;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    let respuesta= response.data;
                    q: search
                    me.arrayCliente = respuesta.clientes;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCliente(val1) {
                let me = this;
                me.loading = true;
                me.idcliente = val1.id;
            },
            selectVendedor(search,loading){
                let me=this;
                loading(true)

                var url= this.ruta + '/vendedor/selectVendedor?filtro='+search;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    let respuesta= response.data;
                    q: search
                    me.arrayVendedor = respuesta.vendedores;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosVendedor(val2) {
                let me = this;
                me.loading = true;
                me.idvendedor = val2.id;
            },
            buscarArticulo() {
                let me = this;
                var url = this.ruta + '/producto/buscarProductoVenta?filtro=' + me.codigo;

                axios.get(url).then(function(response) {
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.productos;

                    if(me.arrayArticulo.length > 0) {
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                        me.precio = me.arrayArticulo[0]['precio_venta'];
                        me.stock = me.arrayArticulo[0]['stock'];
                    } else {
                        me.idarticulo = 0;
                        me.articulo = 'No existe producto';
                        me.precio = 0.00;
                        me.stock = 0;
                    }
                })
                .catch(function(error){
                    console.log(error);
                })
            },
            pdfVenta(id) {
                window.open(this.ruta + '/venta/pdf/'+id, '_blank');
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarVenta(page,buscar,criterio);
            },
            encuentra(idArt){
                let exist = false;
                for(var i = 0; i < this.arrayDetalle.length; i++) {
                    if(this.arrayDetalle[i].idarticulo == idArt) {
                        exist = true;
                        break;
                    }
                }
                return exist;
            },
            eliminarDetalle(index) {
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            agregarDetalle() {
                let me = this;
                if(me.idarticulo == 0 || me.cantidad == 0 || me.precio == 0) {

                } else {
                    if(me.encuentra(me.idarticulo)) {
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: '¡Este producto ya se encuentra agregado!'
                        })
                    } else {
                        if(me.cantidad > me.stock) {
                            swal({
                                type: 'error',
                                title: 'Error...',
                                text: '¡Este producto no tiene stock suficiente!'
                            })
                        } else {
                            me.arrayDetalle.push({
                                idarticulo: me.idarticulo,
                                articulo:me.articulo,
                                cantidad:me.cantidad,
                                precio: me.precio,
                                descuento: me.descuento,
                                stock: me.stock
                            });
                            me.codigo = '';
                            me.idarticulo = 0;
                            me.articulo = '';
                            me.cantidad = 0;
                            me.precio = 0;
                            me.descuento = 0;
                            me.stock = 0;
                        }
                    }
                }
            },
            agregarDetalleModal(data = []) {
                let me = this;
                if(me.encuentra(data['id'])) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: '¡Este artículo ya se encuentra agregado!'
                    })
                } else {
                    me.arrayDetalle.push({
                        idarticulo: data['id'],
                        articulo: data['nombre'],
                        cantidad: data['numero'],
                        precio: data['precio_venta'],
                        descuento: 0,
                        stock: data['cantidad']
                    });
                }
            },
            listarArticulo (buscar,criterio){
                let me=this;
                var url=this.ruta + '/producto/listarProductoVenta?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    respuesta.productos.data.map(function(x){
                        x.numero = 1;
                    });
                    me.arrayArticulo = respuesta.productos.data;
                    console.log(respuesta.productos.data);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarVenta(){
                if (this.validarVenta()){
                    return;
                }
                
                let me = this;

                axios.post(this.ruta + '/venta/registrar',{
                    'idcliente': this.idcliente,
                    'idvendedor': this.idvendedor,
                    'tipo_comprobante': this.tipo_comprobante,
                    'num_comprobante' : this.num_comprobante,
                    'impuesto' : this.impuesto,
                    'total' : this.total,
                    'pagado': this.pagado,
                    'data' : this.arrayDetalle,
                }).then(function (response) {
                    me.listado = 1;
                    me.listarVenta(1,'','num_comprobante');
                    me.idcliente = 0;
                    me.idvendedor = 0;
                    me.tipo_comprobante = 'FACTURA';
                    me.num_comprobante = 0;
                    me.impuesto = 0;
                    me.total = 0;
                    me.pagado = 0;
                    me.idarticulo = 0;
                    me.articulo = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.stock = 0;
                    me.codigo = '';
                    me.descuento = 0;
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
                    if(x.cantidad > x.stock) {
                        art = x.articulo + ' cantidad insuficiente';
                        me.errorMostrarMsjVenta.push(art);
                    }
                    if(x.descuento > x.cantidad*x.precio) {
                        art = x.articulo + ' descuento mayor al subtotal'
                        me.errorMostrarMsjVenta.push(art);
                    }
                })

                if(me.idcliente == 0) me.errorMostrarMsjVenta.push('Seleccione un Cliente');
                if(!me.idvendedor) me.idvendedor = 0;
                if(me.tipo_comprobante == 0) me.errorMostrarMsjVenta.push('Seleccione un tipo de comprobante');
                if(!me.num_comprobante) {
                    let actual = new Date();
                    me.num_comprobante = actual.getMonth()+1 + actual.getDate().toString() + actual.getHours().toString() + actual.getMinutes().toString() + actual.getSeconds().toString();
                }
                if(!me.impuesto) me.impuesto = 0;
                if(me.arrayDetalle.length <= 0) me.errorMostrarMsjVenta.push('Ingrese productos');

                if(me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                return me.errorVenta;
            },
            mostrarDetalle() {
                this.listado = 0;
                this.idproveedor = 0;
                this.tipo_comprobante = 'FACTURA';
                this.num_comprobante = '';
                this.impuesto = 0;
                this.total = 0;
                this.pagado = 0;
                this.idarticulo = 0;
                this.articulo = '';
                this.cantidad = 0;
                this.precio = 0;
                this.errorVenta = [];
                this.errorMostrarMsjVenta = [];
                this.arrayDetalle = [];
            },
            ocultarDetalle() {
                this.listado = 1;
            },
            verVenta(id) {
                this.listado = 2;
                let me = this;
                // Obtener datos del ingreso
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
                    me.pagado = arrayVentaTemp[0]['pagado'];
                })
                .catch(function (error) {
                    console.log(error);
                });
                // Obtener datos de los detalles
                var url=this.ruta + '/venta/obtenerDetalles?id='+ id;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';

            },
            abrirModal(){
                this.arrayArticulo = [];
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios productos'
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
