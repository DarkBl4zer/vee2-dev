@extends('base')
@section('Xtitle')
Acciones de prevención y control
@endsection
@section('Xestilos')
    <!-- Datatable style-->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/select2/css/select2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datetimepicker/jquery.datetimepicker.min.css')}}">
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
        <div class="row">
            <div class="col-4">
                <h6 class="m-0 font-weight-bold text-primary">Acciones de prevención y control</h6>
                <div class="form-group" style="margin-top: 20px; width: 95px;">
                    <select class="form-control select2" id="periodo" name="periodo" onchange="ConsultarAcciones();">
                        <option value="{{date("Y")}}">{{date("Y")}}</option>
                        @foreach ($years as $item)
                        <option value="{{$item->year}}">{{$item->year}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body" style="position: relative;">
        <i id="btnNuevo" class="fas fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Nueva acción" onclick="Nuevo();" style="font-size: 24px; cursor: pointer; display: none;"></i>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 13px;">
            </table>
        </div>
    </div>
</div>

<!-- Modal Nueva Accion-->
<div class="modal fade" id="modalNuevaAccion" tabindex="-1" role="dialog" aria-labelledby="modalNuevaAccionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNuevaAccionLabel">Nueva acción</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="id_actuacion">Tipo de actuación <sup style="color: var(--danger)">*</sup></label>
                            <select class="form-control" id="id_actuacion" onchange="CambiarTipoAccion();">
                                @foreach ($acciones as $item)
                                <option value="{{$item->valor_numero}}">{{$item->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowPadre" style="display: none;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="accion">Acción para seguimiento <sup style="color: var(--danger)">*</sup></label>
                            <select class="form-control" id="id_padre">
                                {!!$paraSeguimiento!!}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="temap">Tema principal <sup style="color: var(--danger)">*</sup></label>
                            <select class="form-control" id="temap" onchange="CambiarTemaP();">
                                <option value="">...</option>
                                @foreach ($temasp as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5" id="rowTemaS" style="display: none;">
                        <div class="form-group">
                            <label for="temas">Tema secundario <sup style="color: var(--danger)">*</sup></label>
                            <select class="form-control" id="temas">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 text-center" id="rowActa" style="display: none;">
                        <i class="fas fa-file-pdf" data-toggle="tooltip" data-placement="top" title="Acta" style="font-size: 30px; padding-top: 33px; cursor: pointer; color: var(--danger);"></i>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="titulo">Título <sup style="color: var(--danger)">*</sup></label>
                            <textarea class="form-control" id="titulo" rows="3" maxlength="2000" onkeyup="CaracteresRestantes(this.value, 'cont_titulo', 2000);" style="text-transform: uppercase;"></textarea>
                            <span id="cont_titulo" style="font-size: 11px; padding-top: 2px; position: absolute; right: 15px;">
                                <i class="fas fa-align-left"></i> 2000
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="objetivo_general">Objetivo general <sup style="color: var(--danger)">*</sup></label>
                            <textarea class="form-control" id="objetivo_general" rows="3" maxlength="2000" onkeyup="CaracteresRestantes(this.value, 'cont_objetivo_general', 2000);" style="text-transform: uppercase;"></textarea>
                            <span id="cont_objetivo_general" style="font-size: 11px; padding-top: 2px; position: absolute; right: 15px;">
                                <i class="fas fa-align-left"></i> 2000
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="entidades">Entidad(s) <sup style="color: var(--danger)">*</sup></label>
                        <select id="entidades" class="form-control" name="entidades[]" multiple="multiple" style="width: 100% !important;">
                        </select>
                    </div>
                </div>
                <div class="row top10">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="numero_profesionales"># Profesionales <sup style="color: var(--danger)">*</sup></label>
                            <input type="number" class="form-control" min="1" id="numero_profesionales">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaPG">Fecha plan de gestión <sup style="color: var(--danger)">*</sup></label>
                        <input class="form-control" id="fechaPG" type="text" placeholder="DD/MM/AAAA" autocomplete="off" style="width: 100%;" onchange="CambioFecha(this.id);" readonly="">
                    </div>
                    <div class="col-md-3">
                        <label for="fechaIni">Fecha inicio acción <sup style="color: var(--danger)">*</sup></label>
                        <input class="form-control" name="fechaIni" id="fechaIni" type="text" placeholder="DD/MM/AAAA" autocomplete="off" style="width: 100%;" onchange="CambioFecha(this.id);" readonly="">
                    </div>
                    <div class="col-md-3">
                        <label for="fechaFin">Fecha fin acción <sup style="color: var(--danger)">*</sup></label>
                        <input class="form-control" name="fechaFin" id="fechaFin" type="text" placeholder="DD/MM/AAAA" autocomplete="off" style="width: 100%;" onchange="CambioFecha(this.id);" readonly="">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="ConfirmarGuardarNuevaAccion();">Guardar</button>
        </div>
      </div>
    </div>
</div>

<!-- Modal ver/repetir declración -->
<div class="modal fade" id="modalVerRepetirDeclaracion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 2px 4px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" id="botonesFirma">
                        <div id="iconVerDeclaracion" class="col-md-6 text-center" data-toggle="tooltip" data-placement="top" title="Ver la declaración" style="line-height: 13px; cursor: pointer;">
                            <i class="fas fa-file-contract" style="font-size: 32px;"></i>
                            <br><br><span style="font-size: 13px;">Ver la declaración<span>
                        </div>
                        <div id="iconRepetirDeclaracion" class="col-md-6 text-center" data-toggle="tooltip" data-placement="top" title="Registrar nuevamente la declaración" style="line-height: 13px; cursor: pointer;">
                            <i class="fas fa-redo" style="font-size: 32px"></i>
                            <br><br><span style="font-size: 13px;">Registrar nuevamente la declaración<span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Accion-->
<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetalleLabel">Detalle acción</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tablaDetalle" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Campo</th>
                                    <th scope="col">Dato registrado</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTablaDetalle">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
        </div>
      </div>
    </div>
</div>

<input type="hidden" id="idCreaEdita" value="0">

@include('componentes.imparcialidad_conflicto', ['profesiones' => $profesiones, 'cargos' => $cargos])

@endsection
@section('Xscripts')
    <script>
        var ppEditar = true;
    </script>
    <!-- Page level plugins -->
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/i18n/es.js')}}"></script>
    <script src="{{asset('vendor/datetimepicker/jquery.datetimepicker.full.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset('js/listar_acciones.js')}}"></script>
@endsection
