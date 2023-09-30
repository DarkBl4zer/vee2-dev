        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('inicio')}}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Módulo <sup>PyCFP</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            @foreach ($sesion->menu as $xxx)
                @php
                    $item = (object)$xxx;
                    $temp= str_replace(" ","_",strtolower($item->nombre));
                    $item->slag = preg_replace('/[^A-Za-z0-9\-]/', '', $temp);
                @endphp
                @if ($item->tipo == "MENU")
                    @include('sidebar.MENU', ['menu' => $item])
                @endif
                @if ($item->tipo == "SEPAR")
                    <hr class="sidebar-divider my-0">
                @endif
                @if ($item->tipo == "TITLE")
                    @include('sidebar.TITLE', ['title' => $item->nombre])
                @endif
                @if ($item->tipo == "LINK")
                    @include('sidebar.LINK', ['menu' => $item])
                @endif
            @endforeach

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
