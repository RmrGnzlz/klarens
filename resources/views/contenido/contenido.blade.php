    @extends('principal')
    @section('contenido')

    @if(Auth::check())
        <template v-if="menu==0">
            <dashboard :ruta="ruta"></dashboard>
        </template>
        @if (Auth::user()->idrol == 1)

        <template v-if="menu==1">
            <categoria :ruta="ruta"></categoria>
        </template>

        <template v-if="menu==2">
            <producto :ruta="ruta"></producto>
        </template>

        <template v-if="menu==3">
            <ingreso :ruta="ruta"></ingreso>
        </template>

        <template v-if="menu==4">
            <proveedor :ruta="ruta"></proveedor>
        </template>

        <template v-if="menu==5">
            <venta :ruta="ruta"></venta>
        </template>
        
        <template v-if="menu==13">
            <devolucion :ruta="ruta"></devolucion>
        </template>

        <template v-if="menu==14">
            <vendedor :ruta="ruta"></vendedor>
        </template>

        <template v-if="menu==6">
            <cliente :ruta="ruta"></cliente>
        </template>

        <template v-if="menu==7">
            <user :ruta="ruta"></user>
        </template>

        <template v-if="menu==8">
            <rol :ruta="ruta"></rol>
        </template>

        <template v-if="menu==9">
            <consultaingreso :ruta="ruta"></consultaingreso>
        </template>

        <template v-if="menu==10">
            <consultaventa :ruta="ruta"></consultaventa>
        </template>

        <template v-if="menu==11">
            <h1>Ayuda</h1>
        </template>

        <template v-if="menu==12">
            <h1>Acerca de</h1>
        </template>
        @elseif (Auth::user()->idrol == 2)
        <template v-if="menu==5">
            <venta :ruta="ruta"></venta>
        </template>
        
        <template v-if="menu==13">
            <devolucion :ruta="ruta"></devolucion>
        </template>

        <template v-if="menu==14">
            <vendedor :ruta="ruta"></vendedor>
        </template>

        <template v-if="menu==6">
            <cliente :ruta="ruta"></cliente>
        </template>
        <template v-if="menu==10">
            <consultaventa :ruta="ruta"></consultaventa>
        </template>

        <template v-if="menu==11">
            <h1>Ayuda</h1>
        </template>

        <template v-if="menu==12">
            <h1>Acerca de</h1>
        </template>
        @elseif (Auth::user()->idrol == 3)
        <template v-if="menu==1">
            <categoria :ruta="ruta"></categoria>
        </template>

        <template v-if="menu==2">
            <producto :ruta="ruta"></producto>
        </template>

        <template v-if="menu==3">
            <ingreso :ruta="ruta"></ingreso>
        </template>

        <template v-if="menu==4">
            <proveedor :ruta="ruta"></proveedor>
        </template>
        <template v-if="menu==9">
            <consultaingreso :ruta="ruta"></consultaingreso>
        </template>
        <template v-if="menu==11">
            <h1>Ayuda</h1>
        </template>

        <template v-if="menu==12">
            <h1>Acerca de</h1>
        </template>
        @else

        @endif

    @endif
       
        
    @endsection