@extends('base')
@section('Xtitle')
Configurar Firma
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
<div class="col-xl-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Configurar firma</h6>
        </div>
        <div class="card-body" style="position: relative;">
            <!-- Content Row -->
            <div class="row" style="margin-top: 15px;">
                <div class="col-12">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <canvas src="{{asset('img/noFirma.jpg')}}" width="300px" height="100px" id="canvas"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content Row -->
            <div class="row" style="margin-top: 15px;">
                <div class="col-12">
                    <div class="input-group">
                        <div class="custom-file">
                            <form id="formFirma" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="canvaFirma" name="canvaFirma">
                                <input type="hidden" id="escalaFirma" name="escalaFirma">
                                <input type="file" class="custom-file-input" id="inputFirma" name="inputFirma" aria-describedby="inputGroupFirma" accept=".jpg,.jpeg,.png">
                                <label class="custom-file-label" for="inputFirma" style="border-radius: 5px;" data-browse="Elegir">Seleccionar archivo</label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content Row -->
            <div class="row" style="margin-top: 15px;">
                <div class="col-9">
                    <div class="form-group">
                        <label for="escala">Escala de la imagen</label>
                        <input type="range" class="form-control-range" id="escala" value="100">
                    </div>
                </div>
                <div class="col-3 text-center" style="margin-top: 24px;">
                    <strong id="escalaN">100%</strong>
                </div>
            </div>
            <!-- Content Row -->
            <hr>
            <div class="row" style="margin-top: 15px;">
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-secondary" onclick="location.reload();">Cancelar</button>
                </div>
                <div class="col-6 text-center">
                    <button type="button" class="btn btn-primary" onclick="GuardarFirma();">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Xscripts')
<script type="text/javascript">
    var backFirma = {
        inpFirma: "{{$firma->inpFirma}}",
        escFirma: {{($firma->escFirma)?$firma->escFirma:0}},
    };
</script>
<script src="{{asset('js/firma.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/bs-custom-file-input.min.js')}}"></script>
@endsection
