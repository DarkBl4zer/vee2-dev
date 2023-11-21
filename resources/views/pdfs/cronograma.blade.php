<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cronograma plan de gestión</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .pagenum:before {
        content: counter(page);
    }
    td{
        padding: 0px 5px 0px 5px;
    }
    .titulo{
        color: white;
        background-color: #5b9bd5;
    }
    .claro{
        background-color: #bdd6ee;
    }
    .gris{
        background-color: #f2f2f2;
    }
    .tabla{
        margin-top: 30px;
        text-align: center;
        width: 100%;
    }
    body {
        margin-top: 115px;
        margin-left: 20px;
        margin-right: 22px;
        margin-bottom: 50px;
    }
    .sel{
        background-color: #007bff;
    }
    #footer {
        position: fixed;
        bottom: -10px;
        line-height: 1.1;
        font-size: 12px;
        width: 100%;
        margin-left: 42px;
    }
    #footer .page:after { content: counter(page, upper-roman); }
    table, th, td {
        border: 1px solid;
        border-collapse: collapse;
    }
  </style>
</head>
<body>
    {{-- ********************Encabezado******************** --}}
    <table style="position: fixed; top: -15px; margin-left: 20px; width: 1455px;">
        <tr>
            <td rowspan="3" style="text-align: center; width: 173px;"><strong>PERSONERÍA DE BOGOTÁ, D. C.</strong></td>
            <td rowspan="3" style="text-align: center; width: 100%;"><strong>PLAN DE GESTIÓN DE LA ACCIÓN DE PREVENCIÓN Y CONTROL A LA FUNCIÓN PÚBLICA</strong></td>
            <td colspan="2" style="padding-left: 5px;"><strong>Código:</strong> 06-FR-01</td>
        </tr>
        <tr>
            <td style="padding-left: 5px; width: 80px;"><strong>Versión:</strong><br>6</td>
            <td style="padding-left: 5px; width: 80px;"><strong>Página:</strong><br>&nbsp;</span></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 5px;"><strong>Vigente desde:</strong> 6/12/2021</td>
        </tr>
    </table>
    <div id="footer">
        <strong>Nota:</strong> Si este documento se encuentra impreso se considera Copia no Controlada. La versión vigente está publicada en el repositorio oficial de la Personería de Bogotá, D. C.
    </div>
    {{-- ********************Cronograma******************** --}}
    <table style="text-align: center; width: 100%; font-size: 10px; margin-top: 20px;">
        <tr class="gris">
            <td rowspan="2"><strong>No.</strong></td>
            <td rowspan="2"><strong>ACTIVIDADES</strong></td>
            <td colspan="4"><strong>Enero</strong></td>
            <td colspan="4"><strong>Febrero</strong></td>
            <td colspan="4"><strong>Marzo</strong></td>
            <td colspan="4"><strong>Abril</strong></td>
            <td colspan="4"><strong>Mayo</strong></td>
            <td colspan="4"><strong>Junio</strong></td>
            <td colspan="4"><strong>Julio</strong></td>
            <td colspan="4"><strong>Agosto</strong></td>
            <td colspan="4"><strong>Septiembre</strong></td>
            <td colspan="4"><strong>Octubre</strong></td>
            <td colspan="4"><strong>Noviembre</strong></td>
            <td colspan="4"><strong>Diciembre</strong></td>
        </tr>
        <tr class="gris">
            @for ($i = 0; $i < 12; $i++)
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            @endfor
        </tr>
        @php
        $cont1=0;$cont2=0;$cont3=0;$cont4=0;
        $tt1=true;$tt2=true;$tt3=true;$tt4=true;
        $cron1="";$cron2="";$cron3="";$cron4="";
        foreach ($cronograma as $item) {
            $arrSemanas = array();
            foreach ($item->semanas as $semana) {
                array_push($arrSemanas, $semana);
            }
            $calendar = "";
            for ($i=1; $i < 13; $i++) {
                for ($ii=1; $ii < 5; $ii++) {
                    if (in_array($i."-".$ii, $arrSemanas)) {
                        $calendar .= '<td class="sel">&nbsp;</td>';
                    } else {
                        $calendar .= '<td>&nbsp;</td>';
                    }
                }
            }
            // ****************************** Etapa 1 ******************************
            if ($item->id_etapa == 1) {
                $cont1++;
                $titulo = "";
                if ($tt1) {
                    $tt1 = false;
                    $titulo = '<tr class="gris text-left"><td colspan="50"><strong>ETAPA DE PLANEACIÓN</strong></td></tr>';
                }
                $cron1 .= $titulo.'<tr><td>'.$cont1.'</td><td class="text-justify">'.$item->actividad.'</td>'.$calendar.'</tr>';
            }
            // ****************************** Etapa 2 ******************************
            if ($item->id_etapa == 2) {
                $cont2++;
                $titulo = "";
                if ($tt2) {
                    $tt2 = false;
                    $titulo = '<tr class="gris text-left"><td colspan="50"><strong>ETAPA DE EJECUCIÓN</strong></td></tr>';
                }
                $cron2 .= $titulo.'<tr><td>'.$cont2.'</td><td class="text-justify">'.$item->actividad.'</td>'.$calendar.'</tr>';
            }
            // ****************************** Etapa 3 ******************************
            if ($item->id_etapa == 3) {
                $cont3++;
                $titulo = "";
                if ($tt3) {
                    $tt3 = false;
                    $titulo = '<tr class="gris text-left"><td colspan="50"><strong>ETAPA DE INFORME</strong></td></tr>';
                }
                $cron3 .= $titulo.'<tr><td>'.$cont3.'</td><td class="text-justify">'.$item->actividad.'</td>'.$calendar.'</tr>';
            }
            // ****************************** Etapa 3 ******************************
            if ($item->id_etapa == 4) {
                $cont4++;
                $titulo = "";
                if ($tt4) {
                    $tt4 = false;
                    $titulo = '<tr class="gris text-left"><td colspan="50"><strong>ETAPA DE CIERRE</strong></td></tr>';
                }
                $cron4 .= $titulo.'<tr><td>'.$cont4.'</td><td class="text-justify">'.$item->actividad.'</td>'.$calendar.'</tr>';
            }
        }
        echo $cron1.$cron2.$cron3.$cron4;
        @endphp
    </table>
    <p style="margin-top: 70px; font-size: 13px; position: relative;">
        <img src="{!!$datos->delegado_firma!!}" style="position: relative; position: relative; width: 180px; top: 15px; margin-left: 38px;">
        <br>
        Firma___________________________________
        <br>
        Personero(a) Delegado(a) / Personero(a) Local {{$datos->delegado_nombre}}
    </p>
    @foreach ($funcionarios as $item)
    <p style="margin-top: 50px; font-size: 13px; position: relative;">
        <img src="{!!$item['base64Firma']!!}" style="position: relative; position: relative; width: 180px; top: 15px; margin-left: 38px;">
        <br>
        Firma___________________________________
        <br>
        Profesional / {{$item['nombre']}}
    </p>
    @endforeach
</body>
</html>
