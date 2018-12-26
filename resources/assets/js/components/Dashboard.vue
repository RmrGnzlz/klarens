<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ingresos</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="ingresos"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Compras de los últimos meses.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ventas</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="ventas"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Ventas de los últimos meses.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
    export default {
        props : ['ruta'],
        data() {
            return {
                varIngreso: null,
                charIngreso: null,
                ingresos: [],
                varTotalIngreso: [],
                varMesIngreso: [],
                meses: ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'],
                varMesesIngreso: [],
                varVenta: null,
                charVenta: null,
                ventas: [],
                varTotalVenta: [],
                varMesVenta: [],
                varMesesVenta: []
            }
        },
        methods: {
            getData() {
                let me = this;
                var url = this.ruta + '/dashboard';
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.ingresos = respuesta.ingresos;
                    me.ventas = respuesta.ventas;
                    // Cargamos los ingresos al chart
                    me.loadIngresos();
                    me.loadVentas();
                }).catch(function(error){
                    console.log(error);
                });
            },
            loadIngresos() {
                let me = this;
                me.ingresos.map(function(x){
                    me.varMesIngreso.push(x.mes);
                    me.varTotalIngreso.push(x.total);
                });
                me.varMesIngreso.map(function(x){
                    me.varMesesIngreso.push(me.meses[x-1]);
                })
                me.varIngreso = document.getElementById('ingresos').getContext('2d');
                var myChart = new Chart(me.varIngreso, {
                    type: 'bar',
                    data: {
                        labels: me.varMesesIngreso,
                        datasets: [{
                            label: 'Ingresos',
                            data: me.varTotalIngreso,
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadVentas() {
                let me = this;
                me.ventas.map(function(x){
                    me.varMesVenta.push(x.mes);
                    me.varTotalVenta.push(x.total);
                });
                me.varMesVenta.map(function(x){
                    me.varMesesVenta.push(me.meses[x-1]);
                })
                me.varVenta = document.getElementById('ventas').getContext('2d');
                var myChart = new Chart(me.varVenta, {
                    type: 'bar',
                    data: {
                        labels: me.varMesesVenta,
                        datasets: [{
                            label: 'Ventas',
                            data: me.varTotalVenta,
                            backgroundColor: 'rgba(55, 160, 200, 0.2)',
                            borderColor: 'rgba(55, 55, 190, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
        },
        mounted() {
            this.getData();
        }
    }
</script>
