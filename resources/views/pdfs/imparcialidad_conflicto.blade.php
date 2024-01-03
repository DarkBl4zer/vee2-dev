<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Imparcialidad y conflictos de interés</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    table, th, td {
        border:1px solid black;
        padding: 5px;
    }
    body{
        margin: 65px 70px 80px 25px;
        font-size: 12px;
    }
    #header { position: fixed; left: 0px; top: -25px; right: 0px; height: 150px;}
    #footer { position: fixed; left: 0px; bottom: -70px; right: 0px; height: 150px;}
    #footer .page:after { content: counter(page, upper-roman); }
  </style>
</head>
<body>
    <div id="header">
        <div class="row">
            <div class="col-md-12 text-left">
                <img src="{!!$funcionario->logo!!}">
            </div>
        </div>
    </div>
    <div id="footer">
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="{!!$funcionario->pie!!}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <strong>DECLARACIÓN DE IMPARCIALIDAD Y CONFLICTOS DE INTERÉS</strong>
        </div>
    </div>
    <div class="row" style="margin-top: 25px;">
        <div class="col-12">
            <strong>1.- IDENTIFICACIÓN DEL SERVIDOR PÚBLICO Y/O CONTRATISTA</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p style="text-align: justify;">Yo <strong>{{$funcionario->nombre}}</strong> identificado(a) con cédula de ciudadanía: <strong>{{$funcionario->cedula}}</strong>, expedida en <strong>{{$declaracion["lugar_expedicion"]}}</strong> </p>
            <p style="text-align: justify;">En mi calidad de <strong>{{($declaracion["funcionario"]==1)?"Funcionario":"Contratista"}}</strong></p>
            <p style="text-align: justify;">Nombre del cargo / Número del Contrato / Profesión: <strong>{{$declaracion["cargo"]}} / {{$declaracion["contrato"]}} / {{$declaracion["profesion"]}}</strong></p>
            <p style="text-align: justify;">Dependencia: <strong>{{$funcionario->delegada}}</strong></p>
            <p style="text-align: justify;">Título de la Acción de Prevención y Control a la Función Pública y/o Seguimiento: <strong style="text-transform: uppercase !important">{{$accion->titulo}}</strong></p>
            <p style="text-align: justify; margin-bottom: 5px;">Entidad (es) Vigilada (s):</p>
            <strong>
                {!!$accion->entidades['string']!!}
            </strong>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-12">
            <strong>2.- DECLARACIÓN DE CONFLICTOS DE INTERÉS:</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p style="text-align: justify;">De conformidad con el principio de imparcialidad señalado en el numeral 3 del artículo 3 de la Ley 1437 de 2011, <strong>{{($declaracion["conflicto"]==1)?"SI":"NO"}}</strong> tengo conflictos de interés, ni me encuentro incurso (a) en las causales de impedimento establecidas en la Constitución y en la Ley, en especial las estipuladas en el artículo 11 ibídem, para el desarrollo de la acción de prevención y control a la función pública y/o seguimiento, al cual he sido designado (a) o asignado (a), por tal motivo declaro: </p>
            <ol type="a">
                <li style="text-align: justify;">Que a mi leal saber y entender, no tengo relaciones oficiales, profesionales, personales o financieras con la entidad (sujeta a vigilancia de la Personería de Bogotá, D.C.) y servidores públicos sujetos a examen, ni intereses comerciales, profesionales, financieros y/o económicos en actividades sujetas a control.</li>
                <li style="text-align: justify;">Asimismo, tampoco tuve un desempeño previo en la ejecución de las actividades y operaciones relacionadas con los sujetos y objetos de control aquí declarados.</li>
                <li style="text-align: justify;">Declaro no tener relaciones de parentesco con el personal vinculado con el sujeto y el objeto de vigilancia y control.</li>
                <li style="text-align: justify;">Declaro no realizar favores (como préstamos de dinero, relaciones de arrendador-arrendatario, entre otros) ni tener prejuicios sobre personas, grupos o actividades propias o que hacen parte de la entidad sujeta a control de la Personería de Bogotá, D.C., atrás mencionada, incluyendo los derivados de convicciones sociales, políticas, religiosas o de género.</li>
                <li style="text-align: justify;">Me comprometo a informar oportunamente y por escrito cualquier impedimento o conflicto de interés de tipo personal, profesional o contractual, sobreviniente a esta declaración, como: inhabilidades de ley o por parentesco familiar, de amistad íntima, enemistad, odio o resentimiento, litigios pendientes, razones religiosas e ideológicas.</li>
                <li style="text-align: justify;">Me comprometo a no divulgar informaciones reservadas recibidas, ni los resultados parciales o finales de la comisión o designación, recibida por fuera de los canales autorizados por la Personería de Bogotá D.C., en virtud de los principios de integridad y confidencialidad.</li>
            </ol>
        </div>
    </div>
    <div class="row" id="conTitulo">
        <div class="col-12">
            <strong>3.- CONFLICTOS DE INTERÉS DECLARADOS</strong>
        </div>
    </div>
    <div class="row" id="conContenido">
        <div class="col-12">
            <p>En caso de declarar algún tipo de conflicto de interés diligenciar la siguiente información:</p>
            <p>
                <ol type="1">
                    <li>Relaciones e intereses oficiales, profesionales, personales, financieros, económicos, y/o comerciales:
                        <table>
                            <thead>
                                <tr>
                                    <th><strong>Nombre y apellido</strong></th>
                                    <th><strong>Cargo</strong></th>
                                    <th><strong>Área de la entidad pública</strong></th>
                                    <th><strong>Tipo de relación</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                @foreach ($declaracion->tablas as $item)
                                    @if ($item->tipo == 1)
                                    <tr>
                                        <td>{{$item->nombres}}</td>
                                        <td>{{$item->cargo}}</td>
                                        <td>{{$item->area}}</td>
                                        <td>{{$item->tipo_relacion}}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tfoot>
                        </table>
                    </li>
                    <li>Relaciones de parentesco:
                        <table>
                            <thead>
                                <tr>
                                    <th><strong>Nombre y apellido</strong></th>
                                    <th><strong>Cargo</strong></th>
                                    <th><strong>Área de la entidad pública</strong></th>
                                    <th><strong>Tipo de relación</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                @foreach ($declaracion->tablas as $item)
                                    @if ($item->tipo == 2)
                                    <tr>
                                        <td>{{$item->nombres}}</td>
                                        <td>{{$item->cargo}}</td>
                                        <td>{{$item->area}}</td>
                                        <td>{{$item->tipo_relacion}}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tfoot>
                        </table>
                    </li>
                    <li>
                        Explique brevemente la motivación del impedimento o del conflicto de interés (artículo 12 de la Ley 1437 de 2011): <strong>{{(isset($declaracion["explicacion"]))?$declaracion["explicacion"]:""}}</strong>
                    </li>
                </ol>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <strong>4.-COMPROMISOS</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p>Así mismo, de conformidad con el Código de Integridad de la Personería de Bogotá D.C., me comprometo a:</p>
            <p>
                <ul>
                    <li style="text-align: justify;">No aceptar regalos o dadivas para favorecer a terceros con mi trabajo realizado.</li>
                    <li style="text-align: justify;">No retardar injustificadamente el trabajo encomendado.</li>
                    <li style="text-align: justify;">No modificar injustificadamente los resultados de las acciones de Prevención y control a la función pública y/o seguimiento a realizar.</li>
                    <li style="text-align: justify;">No recibir influencia externa en el trabajo a ejecutar.</li>
                    <li style="text-align: justify;">Excusarme de participar en actividades cuando no tengan la imparcialidad exigida o la pierdan en el transcurso del trabajo a efectuar.</li>
                    <li style="text-align: justify;">Informar los resultados de mi trabajo y cumplir con los procedimientos pertinentes.</li>
                </ul>
            </p>
            @php
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            if ($declaracion["firmado"]) {
                $fecha = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',  $declaracion["updated_at"]);
            } else {
                $fecha = Carbon\Carbon::now();
            }
            $mes = $meses[($fecha->format('n')) - 1];
            $fechaFirma = $fecha->format('d') . ' días del mes de ' . $mes . ' del año ' . $fecha->format('Y');
            @endphp
            <p style="text-align: justify;">La presente declaración se realiza bajo la gravedad del juramento y es firmada en la ciudad de Bogotá D.C., a los {{$fechaFirma}}.</p>
        </div>
    </div>
    <div class="row" style="margin-top: 50px;">
        <div class="col-12">
            <p>
                <img src="{!!$funcionario->imgFirma!!}" style="width: 200px;">
                <br>
                <strong>Firma del Declarante</strong><br>
                <strong>Nombre Completo: {{$funcionario->nombre}}</strong><br>
                <strong>Cédula de ciudadanía N°. {{$funcionario->cedula}}</strong>
            </p>
        </div>
    </div>
</body>
</html>
