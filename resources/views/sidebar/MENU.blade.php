<li class="nav-item" id="li_{{$menu->slag}}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#colps{{$menu->slag}}" aria-expanded="true" aria-controls="colps{{$menu->slag}}">
        <i class="{{$menu->icono}}"></i>
        <span>{{$menu->nombre}}</span>
    </a>
    <div id="colps{{$menu->slag}}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header" style="white-space: unset;">{{$menu->descripcion}}</h6>
            @foreach ($menu->submenu as $xxx)
            @php
                $item = (object)$xxx;
            @endphp
            <a class="collapse-item" href="/{{$item->url}}" onclick="/*$('#loading').show();*/">{{$item->nombre}}</a>
            @endforeach
        </div>
    </div>
</li>
