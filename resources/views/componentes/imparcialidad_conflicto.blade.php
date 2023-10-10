<!-- Modal Imparcialidad y conflicto -->
<div class="modal fade" id="modalImparcialidad" tabindex="-1" role="dialog" aria-labelledby="modalImparcialidadLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 930px;">
        <div class="modal-content" style="width: 930px;">
        <div class="modal-header">
            <h5 class="modal-title" id="modalImparcialidadLabel">Declaración de imparcialidad y conflictos de interés</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label for="lugar_expedicion">Lugar de expedición de cédula <sup style="color: var(--danger)">*</sup></label>
                        <input type="text" class="form-control" id="lugar_expedicion" onkeyup="this.value = FiltrarCaracteresOLD(this.id, 'listas');">
                    </div>
                    <div class="col-md-6">
                        <label for="">Tipo de vinculación  <sup style="color: var(--danger)">*</sup></label>
                        <select class="form-control" id="tipoUsuario" onchange="TipoUsuario(this.value);">
                            <option value="">...</option>
                            <option value="1">Funcionario</option>
                            <option value="2">Contratista</option>
                        </select>
                    </div>
                </div>
                <div class="row top10">
                    <div class="col-md-6">
                        <label for="profesion">Profesión <sup style="color: var(--danger)">*</sup></label>
                        <select class="form-control" id="profesion" onchange="ValidarCampo('profesion');">
                            <option value="">...</option>
                            @foreach ($profesiones as $profesion)
                                <option value="{{$profesion->valor_numero}}">{{$profesion->nombre}}</option>
                            @endforeach
                        </select>
                        <span style="font-size: 11px; color: var(--info); position: absolute;">* Si no encuentra su profesion en la lista comunicarce con el administrador.</span>
                    </div>
                    <div class="col-sm-6" id="dSCargo" style="display: none;">
                        <label for="scargo">Cargo <sup style="color: var(--danger)">*</sup></label>
                        <select class="form-control select2" id="scargo" onchange="ValidarCampo('scargo');">
                            <option value="">...</option>
                            @foreach ($cargos as $cargo)
                                <option value="{{$cargo->id_cargo}}">{{$cargo->nombre_cargo}} - {{$cargo->codigo_cargo}} - {{$cargo->grado_cargo}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6" id="dCargo" style="display: none;">
                        <label for="cargo">Nombre del cargo <sup style="color: var(--danger)">*</sup></label>
                        <input type="text" class="form-control" id="cargo" value="No aplica - Contratista" onkeyup="this.value = FiltrarCaracteresOLD(this.id, 'listas');">
                    </div>
                    <div class="col-sm-6" id="dContrato" style="display: none;">
                        <label for="contrato">Número del Contrato <sup style="color: var(--danger)">*</sup></label>
                        <input type="text" class="form-control" id="contrato" onkeyup="this.value = FiltrarCaracteresOLD(this.id, 'contrato');">
                    </div>
                </div>
                <!-- ********************************** Texto html ********************************** -->
                <div class="row" style="margin-top: 30px; font-size: 14px;">
                    <div class="col-12">
                        <strong>2.- DECLARACIÓN DE CONFLICTOS DE INTERÉS:</strong>
                    </div>
                </div>
                <div class="row" style="font-size: 14px;">
                    <div class="col-12">
                        <p style="text-align: justify;">De conformidad con el principio de imparcialidad señalado en el numeral 3 del artículo 3 de la Ley 1437 de 2011, <span id="Mdeclara" style="display: none; position: absolute; top: -17px; font-size: 12px; color: #dc3545;">Requerido</span><select id="declara" style="padding: 5px; border-color: #bbbbbb; border-radius: 5px;" onchange="CambioDeclara();">
                            <option value="">...</option>
                            <option value="1">NO</option>
                            <option value="2">SI</option>
                        </select> tengo conflictos de interés, ni me encuentro incurso (a) en las causales de impedimento establecidas en la Constitución y en la Ley, en especial las estipuladas en el artículo 11 ibídem, para el desarrollo de la acción de prevención y control a la función pública y/o seguimiento, al cual he sido designado (a) o asignado (a), por tal motivo declaro:
                        </p>
                        <ol type="a" style="text-align: justify;">
                            <li>Que a mi leal saber y entender, no tengo relaciones oficiales, profesionales, personales o financieras con la entidad (sujeta a vigilancia de la Personería de Bogotá, D.C.) y servidores públicos sujetos a examen, ni intereses comerciales, profesionales, financieros y/o económicos en actividades sujetas a control.</li>
                            <li>Asimismo, tampoco tuve un desempeño previo en la ejecución de las actividades y operaciones relacionadas con los sujetos y objetos de control aquí declarados.</li>
                            <li>Declaro no tener relaciones de parentesco con el personal vinculado con el sujeto y el objeto de vigilancia y control.</li>
                            <li>Declaro no realizar favores (como préstamos de dinero, relaciones de arrendador-arrendatario, entre otros) ni tener prejuicios sobre personas, grupos o actividades propias o que hacen parte de la entidad sujeta a control de la Personería de Bogotá, D.C., atrás mencionada, incluyendo los derivados de convicciones sociales, políticas, religiosas o de género.</li>
                            <li>Me comprometo a informar oportunamente y por escrito cualquier impedimento o conflicto de interés de tipo personal, profesional o contractual, sobreviniente a esta declaración, como: inhabilidades de ley o por parentesco familiar, de amistad íntima, enemistad, odio o resentimiento, litigios pendientes, razones religiosas e ideológicas.</li>
                            <li>Me comprometo a no divulgar informaciones reservadas recibidas, ni los resultados parciales o finales de la comisión o designación, recibida por fuera de los canales autorizados por la Personería de Bogotá D.C., en virtud de los principios de integridad y confidencialidad.</li>
                        </ol>
                    </div>
                </div>
                <div class="row" id="conTitulo" style="font-size: 14px; display: none;">
                    <div class="col-12">
                        <strong>3.- CONFLICTOS DE INTERÉS DECLARADOS</strong>
                    </div>
                </div>
                <div class="row" id="conContenido" style="font-size: 14px; display: none;">
                    <div class="col-12">
                        <p>En caso de declarar algún tipo de conflicto de interés diligenciar la siguiente información:</p>
                        <p>
                            <ol type="1">
                                <li>Relaciones e intereses oficiales, profesionales, personales, financieros, económicos, y/o comerciales:
                                    <table class="table table-bordered" style="margin-top: 5px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nombre y apellido</th>
                                                <th scope="col">Cargo</th>
                                                <th scope="col">Área de la entidad pública</th>
                                                <th scope="col">Tipo de relación</th>
                                                <th scope="col" style="width: 170px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabla1Body">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-center"><button type="button" class="btn btn-sm btn-info" onclick="AgeragrTabla(1);" id="btnAgregarTabla1" style="font-size: 12px;">Agregar</button></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </li>
                                <li  style="margin-top: 15px;">Relaciones de parentesco:
                                    <table class="table table-bordered" style="margin-top: 5px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nombre y apellido</th>
                                                <th scope="col">Cargo</th>
                                                <th scope="col">Área de la entidad pública</th>
                                                <th scope="col">Tipo de relación</th>
                                                <th scope="col" style="width: 170px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabla2Body">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-center"><button type="button" class="btn btn-sm btn-info" onclick="AgeragrTabla(2);" id="btnAgregarTabla2" style="font-size: 12px;">Agregar</button></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </li>
                                <div style="display: none; margin-top: 25px; margin-bottom: 25px; color: var(--danger);" id="errTablas">Se debe agregar información en por lo menos una de las tablas.</div>
                                <li style="margin-top: 15px;">
                                    Explique brevemente la motivación del impedimento o del conflicto de interés (artículo 12 de la Ley 1437 de 2011):
                                    <textarea class="form-control" id="motivo" rows="3" style="margin-top: 5px;" onkeyup="this.value = FiltrarCaracteresOLD(this.id, 'listas');"></textarea>
                                    <div class="invalid-feedback">Este campo es requerido</div>
                                </li>
                            </ol>
                        </p>
                    </div>
                </div>
                <!-- ********************************** Texto html ********************************** -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="ParaFirmar();">Guardar</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalFirmar" tabindex="-1" role="dialog" aria-labelledby="modalFirmarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" id="alertNoFirma" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-info" role="alert">
                                Por favor configure primero la firma <a href="/config/firma">aqui</a>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="botonesFirma">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-light btn-block" onclick="GenerarVistaPrevia();"><i class="fas fa-file-pdf"></i> Vista previa</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-block" onclick="Firmar();"><i class="fas fa-signature"></i> Firmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('js/componentes/imparcialidad_conflicto.js')}}"></script>
