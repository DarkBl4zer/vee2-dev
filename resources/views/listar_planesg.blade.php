@extends('base')
@section('Xtitle')
Planes de gestión
@endsection
@section('Xestilos')
    <!-- Datatable style-->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/select2/css/select2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datetimepicker/jquery.datetimepicker.min.css')}}">
    <!-- Include Summernote CSS -->
    <link href="{{asset('vendor/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <style>
        .select2-container{
            font-size: 13px !important;
        }
    </style>
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
                <h6 class="m-0 font-weight-bold text-primary">Planes de gestión</h6>
            </div>
        </div>
    </div>
    <div class="card-body" style="position: relative;">
        @if ($permiteNueva)
        <i id="btnNuevo" class="fas fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Nueva acta" onclick="Nuevo();" style="font-size: 24px; cursor: pointer; display: none;"></i>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 13px;">
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo plan-->
<div class="modal fade" id="modalNuevoPlan" tabindex="-1" role="dialog" aria-labelledby="modalNuevoPlanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNuevoPlanLabel">Seleccionar equipo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9">
                        <label for="accion">Acción <sup style="color: var(--danger)">*</sup></label>
                        <select id="accion" class="form-control" style="width: 100% !important;">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_informe">Entrega informe final<sup style="color: var(--danger)">*</sup></label>
                        <input class="form-control" id="fecha_informe" type="text" placeholder="DD/MM/AAAA" autocomplete="off" style="width: 100%;padding-left: 8px;height: 28px;" readonly>
                    </div>
                </div>
                <hr>
                <div class="row top10">
                    <span style="margin-left: 10px; font-weight: bold; margin-bottom: 10px;">De la delegada</span>
                    <div class="col-md-12">
                        <table class="table table-bordered" id="dataTableUsuariosIn" width="100%" cellspacing="0" style="font-size: 12px;">
                        </table>
                    </div>
                    <div class="col-md-12" style="position: relative">
                        <button type="button" class="btn btn-primary btn-sm" style="position: absolute; top: -32px;" onclick="ModtrarUsuariosOut();">De otras delegadas</button>
                    </div>
                </div>
                <hr>
                <div class="row" id="rowUsuariosOut" style="display: none;">
                    <span style="margin-left: 10px; font-weight: bold; margin-bottom: 10px;">De otras delegadas</span>
                    <div class="col-md-12">
                        <table class="table table-bordered" id="dataTableUsuariosOut" width="100%" cellspacing="0" style="font-size: 12px;">
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div id="divErrorUsuarios" class="col-md-12" style="font-size: 13px; color: var(--danger); display: none;">
                        Se requiere seleccionar por lo menos un usuario. *
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="ConfirmarGuardarEquipo();">Guardar</button>
        </div>
      </div>
    </div>
</div>

@include('componentes.ver_accion')

@include('componentes.nuevo_plangestion')

<!-- Modal -->
<div class="modal fade" id="modalFirmar" tabindex="-1" role="dialog" aria-labelledby="modalFirmarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" id="alertNoFirma" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                Por favor configure primero la firma <a href="/config/firma">aquí</a>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="botonesFirma">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-light btn-block" onclick="FirmarPlanT(true);"><i class="fas fa-file-pdf"></i> Vista previa</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-block" onclick="FirmarPlanT(false);"><i class="fas fa-signature"></i> Firmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="idCreaEdita" value="0">

@endsection
@section('Xscripts')
    <script>
        var puedeEditar = {{($permiteNueva)?'true':'false'}};
    </script>
    <!-- Page level plugins -->
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('vendor/datetimepicker/jquery.datetimepicker.full.min.js')}}"></script>
    <!-- Include Summernote JS -->
    <script src="{{asset('vendor/summernote/summernote-bs4.js')}}"></script>
    <script src="{{asset('vendor/summernote/lang/summernote-es-ES.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset('js/listar_planesg.js')}}"></script>
@endsection