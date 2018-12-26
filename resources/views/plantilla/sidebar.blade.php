<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li @click="menu=0" class="nav-item">
                <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
            </li>
            <li class="nav-title">
                Mantenimiento
            </li>
            @if (Auth::user()->idrol == 1 or Auth::user()->idrol == 3)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Inventario</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=1" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-bag"></i> Categorías</a>
                    </li>
                    <li @click="menu=2" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-bag"></i> Productos</a>
                    </li>
                </ul>
            </li>
            @endif
            @if (Auth::user()->idrol == 1 or Auth::user()->idrol == 3)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Pedidos</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-wallet"></i> Pedidos</a>
                    </li>
                    <li @click="menu=4" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Proveedores</a>
                    </li>
                </ul>
            </li>
            @endif
            @if (Auth::user()->idrol == 1 or Auth::user()->idrol == 2)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Ventas</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=5" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-basket-loaded"></i> Ventas</a>
                    </li>
                    <li @click="menu=13" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-action-redo"></i> Devoluciones</a>
                    </li>
                    <li @click="menu=6" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Clientes</a>
                    </li>
                    <li @click="menu=14" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-user"></i> Vendedores</a>
                    </li>
                </ul>
            </li>
            @endif
            @if (Auth::user()->idrol == 1)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Usuarios</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=7" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-user"></i> Usuarios</a>
                    </li>
                    <li @click="menu=8" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-user-following"></i> Roles</a>
                    </li>
                </ul>
            </li>
            @endif
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Reportes</a>
                <ul class="nav-dropdown-items">
                    @if (Auth::user()->idrol == 1 or Auth::user()->irdol == 3)
                    <li @click="menu=9" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reportes de Pedidos</a>
                    </li>
                    @endif
                    @if (Auth::user()->idrol == 1 or Auth::user()->idrol == 2)
                    <li @click="menu=10" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reportes de Facturas</a>
                    </li>
                    @endif
                </ul>
            </li>
            @if (Auth::check())
            <li @click="menu=11" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-book-open"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
            </li>
            <li @click="menu=12" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-info"></i> Acerca de...<span class="badge badge-info">IT</span></a>
            </li>
            @endif
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>