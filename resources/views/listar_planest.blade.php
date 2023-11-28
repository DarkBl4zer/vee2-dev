@extends('base')
@section('Xtitle')
Planes de trabajo
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
        <div class="row">
            <div class="col-4">
                <h6 class="m-0 font-weight-bold text-primary">Planes de trabajo</h6>
                <div class="form-group" style="margin-top: 20px; width: 95px;">
                    <select class="form-control select2" id="periodo" name="periodo" onchange="ConsultarPlanes();">
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
        <i id="btnNuevo" class="fas fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Nuevo plan de trabajo" onclick="Nuevo(0);" style="font-size: 24px; cursor: pointer; display: none;"></i>
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
          <h5 class="modal-title" id="modalNuevoPlanLabel">Nueva plan de trabajo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="dataTableAcciones" width="100%" cellspacing="0" style="font-size: 12px;">
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div id="divErrorAcciones" class="col-md-12" style="font-size: 13px; color: var(--danger); display: none;">
                        Se requiere seleccionar por lo menos una acción. *
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="ConfirmarGuardarNuevoPlan();">Guardar</button>
        </div>
      </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalFirmar" tabindex="-1" role="dialog" aria-labelledby="modalFirmarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 0px 8px;">
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="custom-file">
                                    <form id="formActa" method="post" enctype="multipart/form-data">
                                        <input type="file" class="custom-file-input" id="inputActa" name="inputActa" aria-describedby="inputGroupActa" accept=".pdf" onchange="RemoveInvalid(this.id);">
                                        <label class="custom-file-label" for="inputActa" style="border-radius: 5px; font-size: 14px;" data-browse="Elegir">Acta aprobación plan de trabajo</label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="botonesFirma" style="margin-top: 32px;">
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

<!-- Modal -->
<div class="modal fade" id="modalAprobar" tabindex="-1" role="dialog" aria-labelledby="modalAprobarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 0px 8px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="requerido minilabel" style="margin-top: -14px;">¿Aprueba plan de trabajo?</label>
                            <br>
                            <button type="button" class="btn btn-secondary btn-sm" style="width: 50px;" id="apruebaPTSi" onclick="AprobarPT('Si');">Si</button>
                            <button type="button" class="btn btn-secondary btn-sm" style="width: 50px;" id="apruebaPTNo" onclick="AprobarPT('No');">No</button>
                        </div>
                    </div>
                    <div class="row" id="apruebaBotonesFirma" style="margin-top: 32px; display: none;">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-light btn-block" onclick="FirmarPlanT(true);"><i class="fas fa-file-pdf"></i> Vista previa</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-block" onclick="FirmarCoorPlanT();"><i class="fas fa-signature"></i> Firmar</button>
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
        var ppEditar = true;
    </script>
    <!-- Page level plugins -->
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset('js/listar_planest.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bs-custom-file-input.min.js')}}"></script>
@endsection
