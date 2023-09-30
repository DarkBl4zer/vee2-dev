@extends('base')
@section('Xtitle')
Configurar Usuarios
@endsection
@section('Xestilos')
    <!-- Datatable style-->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/select2/css/select2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2-bootstrap4.min.css')}}">
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
        <h6 class="m-0 font-weight-bold text-primary">Configurar usuarios</h6>
    </div>
    <div class="card-body" style="position: relative;">
        <button id="btnSync" type="button" class="btn btn-sm btn-primary" onclick="ConfSyncUsuariosSinproc();" style="position: absolute; left: 250px; z-index: 100;">Actualizar funcionarios desde SINPROC</button>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Cedula</th>
                        <th>Nombre</th>
                        <th>Perfiles</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datos->usuarios as $item)
                    <tr>
                        <td>{{$item->cedula}}</td>
                        <td>{{$item->nombre}}</td>
                        <td>
                            <i class="fas fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Nuevo perfil" onclick="NuevoPerfil({{intval($item->id)}});"></i>
                            <ul style="position: relative;">
                            @foreach ($item->perfiles as $item2)
                                @if (intval($item2->id_rol) == 1)
                                <li>{{$item2->nombre_rol}} <i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Eliminar perfil" onclick="ConfirmarEliminarPerfil({{intval($item2->id_perfil)}});" style="font-size: 16px;"></i></li>
                                @endif
                                @if (intval($item2->id_rol) == 2)
                                <li>{{$item2->nombre_rol}}<span style="font-size: 11px;">>>{{$item2->tipo_coord}}</span> <i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Eliminar perfil" onclick="ConfirmarEliminarPerfil({{intval($item2->id_perfil)}});" style="font-size: 16px;"></i></li>
                                @endif
                                @if (intval($item2->id_rol) > 2)
                                <li>{{$item2->nombre_rol}}<span style="font-size: 11px;">>>{{$item2->nombre_delegada}}</span> <i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Eliminar perfil" onclick="ConfirmarEliminarPerfil({{intval($item2->id_perfil)}});" style="font-size: 16px;"></i></li>
                                @endif
                            @endforeach
                            </ul>
                        </td>
                        <td class="text-center">
                            <span style="font-weight: bold; color: var(--{{($item->activo)?"success":"danger"}});">{{($item->activo)?"Activo":"Inactivo"}}</span>
                            <br>
                            <i class="fas fa-play-circle" data-toggle="tooltip" data-placement="top" title="Activar usuario" onclick="ConfirmarActivar({{intval($item->id)}}, true);"></i>
                            <i class="fas fa-pause-circle" data-toggle="tooltip" data-placement="top" title="Inactivar usuario" onclick="ConfirmarActivar({{intval($item->id)}}, false);"></i>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Agregar Perfil -->
<div class="modal fade" id="addPerfilModal" tabindex="-1" role="dialog" aria-labelledby="addPerfilModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addPerfilModalLabel">Agregar perfil</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container">
                <div class="row">
                    <input type="hidden" id="idUsuario">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="rol">Rol <sup style="color: var(--danger)">*</sup></label>
                            <select class="form-control select2" id="rol" onchange="CambioRol();">
                                <option value="">...</option>
                                @foreach ($datos->roles as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="div_delegada" class="col-sm-12" style="display: none;">
                        <div class="form-group">
                            <label for="delegada">Delegada <sup style="color: var(--danger)">*</sup></label>
                            <select class="form-control select2" id="delegada" name="delegada" onchange="ValidarCampo(this.id);">
                                <option value="">...</option>
                                @foreach ($datos->delegadas as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="div_tipo_coord" class="col-sm-12" style="display: none;">
                        <div class="form-group">
                            <label for="tipo_coord">Tipo coordinador(a) <sup style="color: var(--danger)">*</sup></label>
                            <select class="form-control select2" id="tipo_coord" name="tipo_coord" onchange="ValidarCampo(this.id);">
                                <option value="">...</option>
                                <option value="PD">PD</option>
                                <option value="LOCALES">LOCALES</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button id="btnGuardarPerfil" type="button" class="btn btn-primary" onclick="GuardarPerfil();">Guardar</button>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('Xscripts')
    <script type="text/javascript">
    var getUsuariosVEE = '{{$datos->getUsuariosVEE}}';
    </script>
    <!-- Page level plugins -->
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset('js/usuarios.js')}}"></script>
@endsection
