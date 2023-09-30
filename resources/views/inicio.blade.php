@extends('base')
@section('Xtitle')
Prevención y Control Función Pública
@endsection
@section('Xsidebar')
@include('sidebar')
@endsection
@section('Xtopbar')
@include('topbar')
@endsection
@section('Xhead')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Inicio</h1>
    <div class="form-group" style="margin-top: 20px;">
        <select class="form-control select2" id="tipoTema" name="tipoTema" onchange="ConsultarAcciones(this.value);" style="font-size: 14px;">
            <option value="2023">Año 2023</option>
            <option value="2023">Semestre 1 - 2023</option>
            <option value="2022">Enero - 2023</option>
            <option value="2022">Febrero - 2023</option>
            <option value="2022">Marzo - 2023</option>
            <option value="2022">Abril - 2023</option>
            <option value="2022">Mayo - 2023</option>
            <option value="2022">Junio - 2023</option>
            <option value="2022">Julio - 2023</option>
            <option value="2022">Agosto - 2023</option>
            <option value="2022">Septiembre - 2023</option>
            <option value="2022">Octubre - 2023</option>
            <option value="2022">Noviembre - 2023</option>
            <option value="2022">Diciembre - 2023</option>
        </select>
    </div>
</div>
@endsection
@section('Xcontent')
<!-- Content Row -->
<div class="row">

    <!-- Card Acciones de PyC -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Acciones de PyC</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">35</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Planes de gestión -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Planes de gestión</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Actividades Ejecución -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Actividades ejecución</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">53</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('Xscripts')
@endsection
