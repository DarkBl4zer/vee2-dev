<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>


    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Datos SINPROC -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="datosSinproc" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600">{{$sesion->nombre}}</span>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="datosSinproc">
                <a class="dropdown-item itemsinproc">
                    <span class="small"><strong>SINPROC:</strong> </span>
                </a>
                <a class="dropdown-item itemsinproc" href="#" data-toggle="tooltip" data-placement="top" title="Copiar">
                    <span class="small copy">{{$sesion->d_sinproc}}</span>
                </a>
                <a class="dropdown-item itemsinproc" href="#" data-toggle="tooltip" data-placement="top" title="Copiar">
                    <span class="small copy">{{$sesion->email}}</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item itemsinproc">
                    <span class="small"><strong>PERFILES:</strong> </span>
                </a>
                @foreach ($sesion->perfiles as $item)
                <a class="dropdown-item itemsinproc" href="#" data-toggle="tooltip" data-placement="top" title="Cambiar" onclick="Trabajo({{$item->id}});">
                    <span class="small">
                        <i class="fas fa-caret-right" id="Tperfil{{$item->id}}" style="display: {{($sesion->trabajo->id_perfil == $item->id)?"contents":"none"}}"></i>
                        @if ($item->rol->id == 1)
                        <strong>{{$item->rol->nombre}}</strong>
                        @endif
                        @if ($item->rol->id == 2)
                        <strong>{{$item->rol->nombre}}</strong><span style="font-size: 11px;">>>{{$item->tipo_coord}}</span>
                        @endif
                        @if ($item->rol->id > 2)
                        <strong>{{$item->rol->nombre}}</strong><span style="font-size: 11px;">>>{{$item->delegada->nombre}}</span>
                        @endif
                    </span>
                </a>
                @endforeach
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter" id="contNoti" style="display: none"></span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">Notificaciones</h6>
                <div id="itemsNotificacion"></div>
                <a class="dropdown-item text-center small text-gray-500" href="{{route('notificaciones')}}">Mostrar todas</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{route('logout')}}">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Regresar a SINPROC
                </a>
            </div>
        </li>

    </ul>

</nav>
