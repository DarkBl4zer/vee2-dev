@extends('base')
@section('Xtitle')
Listar Temas
@endsection
@section('Xestilos')
    <!-- Datatable style-->
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/select2/css/select2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2-bootstrap4.min.css')}}">
    <style>
        .masiva{
            display: none;
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
                <h6 class="m-0 font-weight-bold text-primary">Listar temas</h6>
                <div class="form-group" style="margin-top: 20px;">
                    <label for="tipoTema">Tipo de tema <sup style="color: var(--danger)">*</sup></label>
                    <select class="form-control select2" id="tipoTema" name="tipoTema" onchange="ConsultarTemas();" style="font-size: 14px;">
                        <option value="1">PRINCIPALES</option>
                        <option value="2">SECUNDARIOS</option>
                    </select>
                </div>
            </div>
            <div class="col-4"></div>
            <div class="col-1 text-right masiva" style="padding-top: 62px;">
                <a class="fas fa-file-excel" href="/files/PLANTILLA_CARGA.xlsx" target="_blank" data-toggle="tooltip" data-placement="top" title="Plantilla carga masiva" style="font-size: 30px; color: var(--success);"></a>
            </div>
            <div class="col-2 masiva" style="padding-top: 62px;">
                <div class="input-group">
                    <div class="custom-file">
                        <form id="formCargaMasiva" method="post" enctype="multipart/form-data">
                            <input type="file" class="custom-file-input" id="inputCargaMasiva" name="inputCargaMasiva" aria-describedby="inputGroupCargaMasiva" accept=".xlsx" onchange="RemoveInvalid(this.id);">
                            <label class="custom-file-label" for="inputCargaMasiva" style="border-radius: 5px; font-size: 14px;" data-browse="Elegir">Seleccionar archivo</label>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-1 text-left masiva" style="padding-top: 62px;">
                <i class="fas fa-file-upload" style="font-size: 30px; cursor:pointer;" onclick="ConfirmarCargaMasiva();" data-toggle="tooltip" data-placement="top" title="Cargar"></i>
            </div>
        </div>
    </div>
    <div class="card-body" style="position: relative;">
        <i id="btnNuevo" class="fas fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Nuevo tema" onclick="Nuevo();" style="font-size: 24px; cursor: pointer; display: none;"></i>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 14px;">
            </table>
        </div>
    </div>
</div>

<div id="plantillaSelectActa" style="display: none;">
    <span class="dataFila@id">@valor</span>
    <select class="form-control inputFila@id" id="selectActa@id" onchange="CambioSelectActa(@id);" style="font-size: 14px;">
        <option value="">...</option>
        @foreach ($actas as $item)
        <option value="{{$item->id}}">{{$item->descripcion}}</option>
        @endforeach
        <option value="9999">+ NUEVA ACTA</option>
    </select>
</div>

@include('componentes.nueva_acta')

<div id="plantillaSelectTemasP" style="display: none;">
    <span class="dataFila@id">@valor</span>
    <select class="form-control inputFila@id" id="selectTemasP@id" style="font-size: 14px;">
        <option value="">...</option>
        @foreach ($temasp as $item)
        <option value="{{$item->id}}">{{$item->nombre}}</option>
        @endforeach
    </select>
</div>

@endsection
@section('Xscripts')
    <!-- Page level plugins -->
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset('js/listar_temas.js')}}"></script>
@endsection
