@extends('base')
@section('Xtitle')
Configurar Listas
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
        <h6 class="m-0 font-weight-bold text-primary">Configurar listas</h6>
        <div class="form-group" style="margin-top: 20px;">
            <label for="lista">Listas desplegables <sup style="color: var(--danger)">*</sup></label>
            <select class="form-control select2" id="lista" name="lista" onchange="ConsultarLista(this.value);" style="font-size: 14px;">
                <option value="">...</option>
                @foreach ($listas as $item)
                @include('select_lista', ['lista' => $item])
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-body" style="position: relative;">
        <i id="btnNuevo" class="fas fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Nuevo item" onclick="Nuevo();" style="font-size: 24px; cursor: pointer; display: none;"></i>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 14px;">
            </table>
        </div>
    </div>
</div>

<div id="plantillaTipoValor" style="display: none;">
    <span class="dataFila@id">@valor</span>
    <select class="form-control inputFila@id" id="tipoValor@id" onchange="CambioTipoValor(@id);" style="font-size: 14px;">
        @foreach ($tipoValor as $item)
        @include('select_lista', ['lista' => $item])
        @endforeach
    </select>
</div>

@endsection
@section('Xscripts')
    <!-- Page level plugins -->
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('vendor/select2/js/i18n/es.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{asset('js/listas.js')}}"></script>
@endsection
