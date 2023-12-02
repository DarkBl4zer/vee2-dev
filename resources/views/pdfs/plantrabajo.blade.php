<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Plan de trabajo</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .pagenum:before {
        content: counter(page);
    }
  </style>
  <style>
    @page { margin: 250px 50px; }
    #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px;}
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px;}
    #footer .page:after { content: counter(page, upper-roman); }
  </style>
</head>
<body>
<div id="header">
    <div class="row">
        <div class="col-md-12 text-center">
            <table border="1px" style="width: 100%;">
                <tr>
                    <td rowspan="3" style="font-weight: bold; width: 180px; font-size: 20px; text-align: center;">PERSONERÍA DE BOGOTÁ, D. C.</td>
                    <td rowspan="3" style="font-weight: bold; font-size: 20px; text-align: center;">PLAN DE TRABAJO</td>
                    <td colspan="2"><strong>Código:</strong> 06-FR-12</td>
                </tr>
                <tr>
                    <td style="width: 90px"><strong>Versión:</strong><br>1</td>
                    <td style="width: 90px"><strong>Página:</strong><br><span class="pagenum"></span></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Vigente desde:</strong> 28/08/2019</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="footer">
    <div class="row">
        <div class="col-md-12">
            <strong>Nota:</strong> Si este documento se encuentra impreso se considera Copia no Controlada. La versión vigente está publicada en el repositorio oficial de la Personería de Bogotá, D. C.
        </div>
    </div>
</div>
<div class="container-fluid">
  <div class="row" style="margin-top: 50px; width: 95%;">
    <div class="col-md-12">
        <table border="1px" style="width: 100%;">
            <tr style="font-weight: bold; background-color: #b5b5b5;">
                <td style="padding-left: 5px;">No. </td>
                <td style="padding-left: 5px;">Personería Delegada o Local a la que pertenece</td>
                <td style="padding-left: 5px;">Actuación</td>
                <td style="padding-left: 5px;">Título del informe de la acción de Prevención y Control a la Función Pública a la cual se le va a realizar seguimiento</td>
                <td style="padding-left: 5px;">Cordis mediante el cual el informe de la acción de Prevención y Control a la Función Pública fue remitido a la(s) entidad(es) vigilada(s)</td>
                <td style="padding-left: 5px;">Título de la acción de Prevención y Control a la Función Pública</td>
                <td style="padding-left: 5px;">Objetivo general de la acción de Prevención y Control a la Función Pública</td>
                <td style="padding-left: 5px;">Fecha en la que se radicará el Plan de Gestión de la acción de Prevención y Control a la Función Pública, en la Personería Delegada para la Coordinación respectiva. </td>
                <td style="padding-left: 5px;">Tema del seguimiento </td>
                <td style="padding-left: 5px;">Nombre de la(s) entidad(es) vigilada(s) </td>
                <td style="padding-left: 5px;">Número de profesionales que realizarán la acción de Prevención y Control a la Función Pública o seguimiento</td>
                <td style="padding-left: 5px;">Fecha de inicio de la acción de Prevención y Control a la Función Pública o seguimiento</td>
                <td style="padding-left: 5px;">Fecha de finalización de la acción de Prevención y Control a la Función Pública o seguimiento</td>
            </tr>
            @foreach ($PTAcciones as $PTA)
            <tr>
                <td style="text-align: center;">{{$PTA->accion->numero}}</td>
                <td style="padding-left: 5px;">{{$PTA->accion->delegada}}</td>
                <td style="padding-left: 5px;">{{$PTA->accion->actuacion}}</td>
                <td style="padding-left: 5px;">{{(!is_null($PTA->accion->id_padre))?$PTA->accion->padre->titulo:""}}</td>
                <td style="padding-left: 5px;">{{(!is_null($PTA->accion->id_padre))?$PTA->accion->padre->cordis:""}}</td>
                <td style="padding-left: 5px;">{{$PTA->accion->titulo}}</td>
                <td style="padding-left: 5px;">{{$PTA->accion->objetivo_general}}</td>
                <td style="text-align: center;">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $PTA->accion->fecha_plangestion)->format('d/m/Y')}}</td>
                <td style="padding-left: 5px;">{{(!is_null($PTA->accion->id_padre))?$PTA->accion->archivoacta->tema:""}}</td>
                <td style="padding-left: 5px;">{!!$PTA->accion->entidades['string']!!}</td>
                <td style="text-align: center;">{{$PTA->accion->numero_profesionales}}</td>
                <td style="text-align: center;">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $PTA->accion->fecha_inicio)->format('d/m/Y')}}</td>
                <td style="text-align: center;">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $PTA->accion->fecha_final)->format('d/m/Y')}}</td>
            </tr>
            @endforeach
        </table>
    </div>
  </div>
    <div class="row" style="margin-top: 50px;">
        <div class="col-md-12 text-center">
            FORMATO PLAN DE TRABAJO VIGENCIA: <strong>{{$PTA->plantrabajo->year}}</strong>
        </div>
    </div>
    <div class="row" style="margin-top: 50px; width: 95%;">
        <div class="col-md-12">
            PERSONERÍA DELEGADA O LOCAL: <strong>{{$PTA->plantrabajo->delegada['nombre']}}</strong>
        </div>
        <div class="col-md-12" style="border: solid 1px">
            <table style="margin: 20px 0px;">
                <tr>
                    <td>Fecha de entrega:</td>
                    <td style="width: 500px; border-bottom: solid 1px; padding-left: 20px;"><strong>{{$datos->delegado_fecha}}</strong></td>
                </tr>
            </table>
            <table style="margin: 20px 0px;">
                <tr>
                    <td>Nombre Personero(a) Delegado(a) o Personero(a) Local:</td>
                    <td style="width: 500px; border-bottom: solid 1px; padding-left: 20px;"><strong>{{$datos->delegado_nombre}}</strong></td>
                </tr>
            </table>
            <table style="margin: 20px 0px;">
                <tr>
                    <td>Firma Personero(a) Delegado(a) o Personero(a)  Local:</td>
                    <td style="width: 500px; border-bottom: solid 1px; padding-left: 20px;">
                        <img src="{!!$datos->delegado_firma!!}" style="width: 200px;">
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row" style="margin-top: 50px; width: 95%;">
        <div class="col-md-12">
            @php
                $tipoDelegada = "PD PARA LA COORDINACIÓN DE GESTIÓN DE PERSONERÍAS LOCALES";
                if ($PTA->plantrabajo->delegada['tipo'] == 1) {
                    $tipoDelegada = "PD PARA LA COORDINACIÓN DE PREVENCIÓN Y CONTROL A LA FUNCIÓN PÚBLICA";
                }
            @endphp
            PERSONERÍA DELEGADA PARA LA COORDINACIÓN DE: <strong>{{$tipoDelegada}}</strong>
        </div>
        <div class="col-md-12" style="border: solid 1px">
            <table style="margin: 20px 0px;">
                <tr>
                    <td>Aprobó:
                        <span style="margin-left: 20px;">SI</span>
                        @if ($datos->coordinador_fecha != 'Fecha')
                        <img src="{!!$datos->checked!!}" style="width: 20px;">
                        @else
                        <img src="{!!$datos->unchecked!!}" style="width: 20px;">
                        @endif
                        <span style="margin-left: 20px;">NO</span>
                        <img src="{!!$datos->unchecked!!}" style="width: 20px;">
                    </td>
                </tr>
            </table>
            <table style="margin: 20px 0px;">
                <tr>
                    <td>Fecha de aprobación:</td>
                    <td style="width: 500px; border-bottom: solid 1px; padding-left: 20px;">
                        <strong>{{$datos->coordinador_fecha}}</strong>
                    </td>
                </tr>
            </table>
            <table style="margin: 20px 0px;">
                <tr>
                    <td>Nombre Personero(a) Delegado(a) para la Coordinación:</td>
                    <td style="width: 500px; border-bottom: solid 1px; padding-left: 20px;">
                        <strong>{{$datos->coordinador_nombre}}</strong>
                    </td>
                </tr>
            </table>
            <table style="margin: 20px 0px;">
                <tr>
                    <td>Firma Personero(a) Delegado(a) para la Coordinación:</td>
                    <td style="width: 500px; border-bottom: solid 1px; padding-left: 20px;">
                        <img src="{!!$datos->coordinador_firma!!}" style="width: 200px;">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
