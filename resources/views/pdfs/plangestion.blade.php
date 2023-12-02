<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Plan de gestión</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .titulo{
        background-color: #5b9bd5;
        padding: 5px;
        text-align: center;
    }
    .titulo strong, .titulo_div strong {
        color: white;
    }
    .titulo_div{
        border: solid 1px;
        margin-top: 20px;
        background-color: #5b9bd5;
        text-align: center;
    }
    .claro{
        background-color: #bdd6ee;
    }
    body {
        margin-top: 115px;
        margin-left: 20px;
        margin-right: 22px;
        margin-bottom: 50px;
        /*border-bottom: solid 1px;
        border-top: solid 1px;*/
    }
    #footer {
        position: fixed;
        bottom: -10px;
        line-height: 1.1;
        font-size: 12px;
        width: 600px;
        margin-left: 42px;
    }
    #footer .page:after { content: counter(page, upper-roman); }
    table, th, td {
        border: 1px solid;
        border-collapse: collapse;
        padding: 0px;
        font-size: 16px;
        line-height: 1.1;
        width: 100%;
    }
    .page-break {
        page-break-after: always;
    }
  </style>
</head>
<body>
    {{-- ********************Header******************** --}}
    <table style="position: fixed; top: -15px; margin-left: 20px; width: 683px;">
        <tr>
            <td rowspan="3" style="text-align: center; width: 173px;"><strong>PERSONERÍA DE BOGOTÁ, D. C.</strong></td>
            <td rowspan="3" style="text-align: center; width: 350px;"><strong>PLAN DE GESTIÓN DE LA ACCIÓN DE PREVENCIÓN Y CONTROL A LA FUNCIÓN PÚBLICA</strong></td>
            <td colspan="2" style="padding-left: 5px;"><strong>Código:</strong> 06-FR-01</td>
        </tr>
        <tr>
            <td style="padding-left: 5px; width: 50px;"><strong>Versión:</strong><br>6</td>
            <td style="padding-left: 5px;"><strong>Página:</strong><br>&nbsp;</span></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 5px;"><strong>Vigente desde:</strong> 6/12/2021</td>
        </tr>
    </table>
    <div id="footer">
        <strong>Nota:</strong> Si este documento se encuentra impreso se considera Copia no Controlada. La versión vigente está publicada en el repositorio oficial de la Personería de Bogotá, D. C.
    </div>
    {{-- ********************Información general******************** --}}
    <table>
        <tr>
            <td colspan="2" class="titulo"><strong>INFORMACIÓN GENERAL</strong></td>
        </tr>
        <tr class="claro">
            <td style="padding: 5px; text-align: center;"><strong>Número de la acción de Prevención y Control a la Función Pública</strong></td>
            <td style="padding: 5px; text-align: center;"><strong>Personería Delegada y/o Personería Local</strong></td>
        </tr>
        <tr>
            <td style="padding: 5px;">{{$plangestion->accion['numero']}}</td>
            <td style="padding: 5px;">{{$plangestion->delegada}}</td>
        </tr>
        <tr>
            <td class="claro" style="padding: 1px; text-align: center;"><strong>Fecha de entrega final del informe de Prevención y Control a la Función Pública</strong></td>
            <td style="padding: 5px; text-align: center;">{{$plangestion->finforme}}</td>
        </tr>
        <tr>
            <td colspan="2" class="claro" style="padding: 8px; text-align: center;"><strong>Título de la Acción de Prevención y Control a la Función Pública</strong></td>
        </tr>
        <tr>
            <td colspan="2" class="text-justify" style="padding: 5px;">{{$plangestion->accion['titulo']}}</td>
        </tr>
    </table>

    {{-- ********************Datos PG******************** --}}
    <div class="titulo_div">
        <strong>ENTIDAD(ES) VIGILADA(S)</strong>
    </div>
    <div style="border: solid 1px; padding-left: 20px;">
        {!!$plangestion->accion['entidades']!!}
    </div>

    <div class="titulo_div">
        <strong>OBJETIVO GENERAL</strong>
    </div>
    <div style="border: solid 1px; padding: 5px; text-align: justify;">
        {{$plangestion->accion['objetivo_general']}}
    </div>

    @php
        $especificos = "";
        $metodologia = "";
        $muestra = "";
        $contexto = "";
        $informacion = "";
        foreach ($plangestion->textos as $item) {
            if ($item->tipo == 1) {
                $especificos = $item->texto;
            }
            if ($item->tipo == 2) {
                $metodologia = $item->texto;
            }
            if ($item->tipo == 3) {
                $muestra = $item->texto;
            }
            if ($item->tipo == 4) {
                $contexto = $item->texto;
            }
            if ($item->tipo == 5) {
                $informacion = $item->texto;
            }
        }
    @endphp

    <div class="titulo_div">
        <strong>OBJETIVOS ESPECÍFICOS</strong>
    </div>
    <div style="border: solid 1px; padding: 5px; text-align: justify;">
        {!!$especificos!!}
    </div>

    {{-- <div class="page-break"></div> --}}
    <div class="titulo_div">
        <strong>METODOLOGÍA</strong>
    </div>
    <div style="border: solid 1px; padding: 5px; text-align: justify;">
        {!!$metodologia!!}
    </div>

    {{-- <div class="page-break"></div> --}}
    <div class="titulo_div">
        <strong>MUESTRA Y/O MEDIO DE CONTRASTE</strong>
    </div>
    <div style="border: solid 1px; padding: 5px; text-align: justify;">
        {!!$muestra!!}
    </div>

    {{-- <div class="page-break"></div> --}}
    <div class="titulo_div">
        <strong>CONTEXTO DE LA PROBLEMÁTICA</strong>
    </div>
    <div style="border: solid 1px; padding: 5px; text-align: justify;">
        {!!$contexto!!}
    </div>

    {{-- <div class="page-break"></div> --}}
    <div class="titulo_div">
        <strong>INFORMACIÓN REVISADA</strong>
    </div>
    <div style="border: solid 1px; padding: 5px; text-align: justify;">
        {!!$informacion!!}
    </div>

    <div class="titulo_div">
        <strong>CRONOGRAMA</strong>
    </div>
    <div style="border: solid 1px; padding: 5px; text-align: justify;">
        Ver anexo
    </div>

    {{-- ********************************** RESPONSABLES ********************************** --}}
    <table style="margin-top: 20px;border: none;">
        <tr style="border: none;">
            <td style="border: none;"><strong>RESPONSABLES:</strong></td>
        </tr>
    </table>
    <table style="margin-top: 20px;">
        <tr class="claro">
            <td rowspan="2" style="padding: 5px; text-align: center; width: 250px;"><strong>Nombres y apellidos</strong></td>
            <td colspan="3" style="padding: 5px; text-align: center;"><strong>CONFORMACIÓN DE EQUIPO DE TRABAJO</strong></td>
        </tr>
        <tr class="claro">
            <td style="padding: 5px; text-align: center;"><strong>Profesión</strong></td>
            <td style="padding: 5px; text-align: center;"><strong>Cargo</strong></td>
            <td style="padding: 5px; text-align: center;"><strong>Firma</strong></td>
        </tr>
        @foreach ($funcionarios as $item)
        <tr style="font-style: italic;">
            <td style="padding: 5px; font-size: 14px;">{{$item['nombre']}}</td>
            <td style="padding: 5px; font-size: 14px;">{{$item['profesion']}}</td>
            <td style="padding: 5px; font-size: 14px;">{{$item['cargo']}}</td>
            <td style="padding: 5px; font-size: 14px;">
                <img src="{!!$item['base64Firma']!!}" style="width: 100px">
            </td>
        </tr>
        @endforeach
    </table>

    {{-- ********************************** APROBACIÓN ********************************** --}}
    <table style="margin-top: 20px;border: none;">
        <tr style="border: none;">
            <td style="border: none;"><strong>APROBACIÓN:</strong></td>
        </tr>
    </table>
    <table style="margin-top: 20px;">
        <tr class="claro">
            <td style="padding: 5px; text-align: center; width: 100%;"><strong>Personero(a) Delegado(a) o Local</strong></td>
            <td style="padding: 5px; text-align: center; width: 20px;"><strong>Sí:</strong></td>
            <td style="background-color: white; padding: 5px; text-align: center; width: 20px;"><strong>{{($datos->delegado_nombre=='Delegado')?'':'X'}}</strong></td>
            <td style="padding: 5px; text-align: center; width: 20px;"><strong>No:</strong></td>
            <td style="background-color: white; padding: 5px; text-align: center; width: 20px;"></td>
            <td style="padding: 5px; text-align: center; width: 40px;"><strong>Fecha:</strong></td>
            <td style="background-color: white; padding: 5px; text-align: center; width: 90px;">{{$datos->delegado_fecha}}</td>
        </tr>
        <tr class="claro">
            <td colspan="7" style="padding: 5px;"><strong>Firma:</strong></td>
        </tr>
        <tr>
            <td colspan="7" style="padding: 5px;">
                <img src="{!!$datos->delegado_firma!!}" width="300px" height="100px">
            </td>
        </tr>
        <tr class="claro">
            <td colspan="7" style="padding: 5px;"><strong>Nombre:</strong></td>
        </tr>
        <tr>
            <td colspan="7" style="padding: 5px;">
                {{$datos->delegado_nombre}}
            </td>
        </tr>
        <tr class="claro">
            <td colspan="7" style="padding: 5px;"><strong>Empleo:</strong></td>
        </tr>
        <tr>
            <td colspan="7" style="padding: 5px;">
                Personero(a) Delegado(a) o Local
            </td>
        </tr>
    </table>

    {{-- ********************************** VIABILIDAD ********************************** --}}
    <table style="margin-top: 20px;border: none;">
        <tr style="border: none;">
            <td style="border: none;"><strong>VIABILIDAD:</strong></td>
        </tr>
    </table>
    <table style="margin-top: 20px;">
        <tr class="claro">
            <td style="padding: 5px; text-align: center; width: 100%;"><strong>CONCEPTO DE VIABILIDAD</strong></td>
            <td style="padding: 5px; text-align: center; width: 20px;"><strong>Sí:</strong></td>
            <td style="background-color: white; padding: 5px; text-align: center; width: 20px;"><strong>{{($datos->coordinador_nombre=='Coordinador')?'':'X'}}</strong></td>
            <td style="padding: 5px; text-align: center; width: 20px;"><strong>No:</strong></td>
            <td style="background-color: white; padding: 5px; text-align: center; width: 20px;"></td>
            <td style="padding: 5px; text-align: center; width: 40px;"><strong>Fecha:</strong></td>
            <td style="background-color: white; padding: 5px; text-align: center; width: 90px;">{{$datos->coordinador_fecha}}</td>
        </tr>
        <tr class="claro">
            <td colspan="7" style="padding: 5px;"><strong>Firma:</strong></td>
        </tr>
        <tr>
            <td colspan="7" style="padding: 5px;">
                <img src="{!!$datos->coordinador_firma!!}" width="300px" height="100px">
            </td>
        </tr>
        <tr class="claro">
            <td colspan="7" style="padding: 5px;"><strong>Nombre:</strong></td>
        </tr>
        <tr>
            <td colspan="7" style="padding: 5px;">
                {{$datos->coordinador_nombre}}
            </td>
        </tr>
        <tr class="claro">
            <td colspan="7" style="padding: 5px;"><strong>Empleo:</strong></td>
        </tr>
        <tr>
            <td colspan="7" style="padding: 5px;">
                Personero(a) Delegado(a) para la Coordinación
            </td>
        </tr>
    </table>


</body>
</html>
