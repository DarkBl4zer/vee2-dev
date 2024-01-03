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
        <i id="btnNuevo" class="fas fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Nuevo plan de gestón" onclick="Nuevo(0);" style="font-size: 24px; cursor: pointer;"></i>
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
                <div class="row" id="rowDatoAccion">
                    <div class="col-md-9">
                        <label for="accion">Acción <sup style="color: var(--danger)">*</sup></label>
                        <select id="accion" class="form-control" style="width: 100% !important;" onchange="DefinirFechaMaxima();">
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
                    <div class="col-md-12" style="position: relative; display: none;">
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

@include('componentes.nuevo_plangestion')

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

@include('componentes.imparcialidad_conflicto', ['profesiones' => $profesiones, 'cargos' => $cargos])

<!-- Modal -->
<div class="modal fade" id="modalAprobarDelegado" tabindex="-1" role="dialog" aria-labelledby="modalAprobarDelegadoLabel" aria-hidden="true">
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
                            <label class="requerido minilabel" style="margin-top: -14px;">¿Viabilidad delegado?</label>
                            <br>
                            <button type="button" class="btn btn-secondary btn-sm" style="width: 50px;" id="delegadoSi" onclick="AprobarDelegado('Si');">Si</button>
                            <button type="button" class="btn btn-secondary btn-sm" style="width: 50px;" id="delegadoNo" onclick="AprobarDelegado('No');">No</button>
                        </div>
                    </div>
                    <div class="row apruebaBotonesD" style="margin-top: 32px; display: none;">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="custom-file">
                                    <form id="formActaD" method="post" enctype="multipart/form-data">
                                        <input type="file" class="custom-file-input" id="inputActaD" name="inputActaD" aria-describedby="inputGroupActaD" accept=".pdf" onchange="RemoveInvalid(this.id);">
                                        <label class="custom-file-label" for="inputActaD" style="border-radius: 5px; font-size: 14px;" data-browse="Elegir">Acta de viabilidad delegado</label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row apruebaBotonesD" style="margin-top: 15px; display: none;">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-light btn-block" onclick="FirmarDelegado(true);"><i class="fas fa-file-pdf"></i> Vista previa</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-block" onclick="FirmarDelegado(false);"><i class="fas fa-signature"></i> Firmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mesa de trabajo -->
<div class="modal fade" id="modalMesaTrabajo" tabindex="-1" role="dialog" aria-labelledby="modalMesaTrabajoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMesaTrabajoLabel">¿Se realizó mesa de trabajo?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-light btn-block" onclick="VistaPrevia($('#idCreaEdita').val(), false);"><i class="fas fa-file-pdf"></i> Vista previa</button>
                    </div>
                </div>
                <div class="row" style="padding-top: 40px;">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-secondary btn-sm" style="width: 50px;" id="realizoMTSi" onclick="RealizoMesa('Si');">Si</button>
                        <button type="button" class="btn btn-secondary btn-sm" style="width: 50px;" id="realizoMTNo" onclick="RealizoMesa('No');">No</button>
                        <input type="hidden" name="realizo" id="realizo">
                        <div class="invalid-feedback" id="Mrealizo">Debe seleccionar una opción</div>
                    </div>
                </div>
                <div class="row" style="display: none; padding-top: 40px;" id="rowAprobarMesaT">
                    <div class="col-md-12">
                        <label class="requerido minilabel" style="margin-top: -14px;">¿Aprueba plan de gestión?</label>
                        <br>
                        <button type="button" class="btn btn-secondary btn-sm" style="width: 110px;" id="aproboMesaSi" onclick="AprobarMesa('Si');">Aprobar</button>
                        <button type="button" class="btn btn-secondary btn-sm" style="width: 110px;" id="aproboMesaNo" onclick="AprobarMesa('No');">No aprobar</button>
                        <input type="hidden" name="aproboMesa" id="aproboMesa">
                        <div class="invalid-feedback" id="MaproboMesa">Debe seleccionar una opción</div>
                    </div>
                </div>
                <div class="row" style="display: none; padding-top: 30px;" id="rowArchivoMesaT">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="custom-file">
                                <form id="formActaE" method="post" enctype="multipart/form-data">
                                    <input type="file" class="custom-file-input" id="inputActaE" name="inputActaE" aria-describedby="inputGroupActaE" accept=".pdf" onchange="RemoveInvalid(this.id);">
                                    <label class="custom-file-label" for="inputActaE" style="border-radius: 5px; font-size: 14px;" data-browse="Elegir">Acta mesa de trabajo con enlace</label>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row top10" style="display: none;" id="rowMotivoMesaT">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="titulo">Motivo / Observaciones <sup style="color: var(--danger)">*</sup></label>
                            <textarea class="form-control" id="motivo_rechazo_mt" rows="3" maxlength="2000" onkeyup="FiltrarCaracteres(this.id, 'itemLista');"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarMesaTrabajo();" id="btnGuardar">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAprobarCoordinador" tabindex="-1" role="dialog" aria-labelledby="modalAprobarCoordinadorLabel" aria-hidden="true">
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
                            <label class="requerido minilabel" style="margin-top: -14px;">¿Aprueba plan de gestión?</label>
                            <br>
                            <button type="button" class="btn btn-secondary btn-sm" style="width: 50px;" id="apruebaPTSi" onclick="AprobarPG('Si');">Si</button>
                            <button type="button" class="btn btn-secondary btn-sm" style="width: 50px;" id="apruebaPTNo" onclick="AprobarPG('No');">No</button>
                            <input type="hidden" id="aproboPg">
                            <div class="invalid-feedback" id="MaproboPg">Debe seleccionar una opción</div>
                        </div>
                    </div>
                    <div class="row" id="apruebaBotonesFirma" style="margin-top: 32px; display: none;">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-light btn-block" onclick="FirmarPlanG(true);"><i class="fas fa-file-pdf"></i> Vista previa</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-block" onclick="FirmarPlanG(false);"><i class="fas fa-signature"></i> Firmar</button>
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
    <!-- Page level plugins -->
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/i18n/es.js')}}"></script>
    <script src="{{asset('vendor/datetimepicker/jquery.datetimepicker.full.min.js')}}"></script>
    <!-- Include Summernote JS -->
    <script src="{{asset('vendor/summernote/summernote-bs4.js')}}"></script>
    <script src="{{asset('vendor/summernote/lang/summernote-es-ES.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset('js/listar_planesg.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bs-custom-file-input.min.js')}}"></script>
@endsection
