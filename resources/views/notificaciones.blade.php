@extends('base')
@section('Xtitle')
Prevención y Control Función Pública
@endsection
@section('Xestilos')
    <!-- Datatable style-->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('Xsidebar')
@include('sidebar')
@endsection
@section('Xtopbar')
@include('topbar')
@endsection
@section('Xhead')
@endsection
@section('Xcontent')
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notificaciones</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Creada</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Vista</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notificaciones as $item)
                    <tr>
                        <td>{{$item->creado}}</td>
                        <td>
                            <span class="hide">{{$item->tipo}}</span>
                            <div class="icon-circle bg-{{$item->tipo}}" style="margin: 0 auto;">
                                <i class="fas fa-{{($item->tipo=="success")?"check":(($item->tipo=="danger")?"exclamation":"pen")}} text-white"></i>
                            </div>
                        </td>
                        <td>{{$item->texto}}</td>
                        <td>{{($item->activo)?"":$item->editado}}</td>
                        <td>
                            <i class="fas fa-external-link-square-alt" data-toggle="tooltip" data-placement="top" title="Revisar" onclick="Notificacion({{$item->id}}, '{{$item->url}}');">&nbsp;&nbsp;&nbsp;</i>
                            <i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="EliminarNotificacion({{$item->id}})">&nbsp;&nbsp;&nbsp;</i>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('Xscripts')
    <!-- Page level plugins -->
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset('js/notificaciones.js')}}"></script>
@endsection
