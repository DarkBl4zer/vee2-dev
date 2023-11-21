<!-- Modal Nuevo plan-->
<div class="modal fade" id="modalNext" tabindex="-1" role="dialog" aria-labelledby="modalNextLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNextLabel">Objetivos específicos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row" style="position: relative">
                    <div class="col-md-12" style="position: absolute; z-index: 1;">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <button type="button" id="btnPaso1" class="btn btn-info" onclick="Pasos(1);" style="font-size: 12px;">Paso 1</button>
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="button" id="btnPaso2" class="btn btn-light" onclick="Pasos(2);" style="font-size: 12px;">Paso 2</button>
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="button" id="btnPaso3" class="btn btn-light" onclick="Pasos(3);" style="font-size: 12px;">Paso3</button>
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="button" id="btnPaso4" class="btn btn-light" onclick="Pasos(4);" style="font-size: 12px;">Paso 4</button>
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="button" id="btnPaso5" class="btn btn-light" onclick="Pasos(5);" style="font-size: 12px;">Paso 5</button>
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="button" id="btnPaso6" class="btn btn-light" onclick="Pasos(6);" style="font-size: 12px;">Paso 6</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 12px;">
                        <div class="progress" style="width: 675px; height: 8px; margin-left: 20px;">
                            <div class="progress-bar bg-info" role="progressbar" id="progresoBar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 30px;">
                    <div class="col-md-12">
                    </div>
                </div>
                <div class="row" style="margin-top: 2px;" id="paso1">
                    <div class="col-md-12">
                        <div class="summernote" id="textAreaP1">
                        </div>
                    </div>
                    <div class="col-md-12 top10">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="adjuntoP1" name="adjuntoP1" accept=".pdf">
                                <label class="custom-file-label" for="adjuntoP1" style="border-radius: 5px; font-size: 14px;" data-browse="Elegir">Archivo adjunto opcional</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 2px; display: none;" id="paso2">
                    <div class="col-md-12">
                        <div class="summernote" id="textAreaP2">
                        </div>
                    </div>
                    <div class="col-md-12 top10">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="adjuntoP2" name="adjuntoP2" accept=".pdf">
                                <label class="custom-file-label" for="adjuntoP2" style="border-radius: 5px; font-size: 14px;" data-browse="Elegir">Archivo adjunto opcional</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 2px; display: none;" id="paso3">
                    <div class="col-md-12">
                        <div class="summernote" id="textAreaP3">
                        </div>
                    </div>
                    <div class="col-md-12 top10">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="adjuntoP3" name="adjuntoP3" accept=".pdf">
                                <label class="custom-file-label" for="adjuntoP3" style="border-radius: 5px; font-size: 14px;" data-browse="Elegir">Archivo adjunto opcional</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 2px; display: none;" id="paso4">
                    <div class="col-md-12">
                        <div class="summernote" id="textAreaP4">
                        </div>
                    </div>
                    <div class="col-md-12 top10">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="adjuntoP4" name="adjuntoP4" accept=".pdf">
                                <label class="custom-file-label" for="adjuntoP4" style="border-radius: 5px; font-size: 14px;" data-browse="Elegir">Archivo adjunto opcional</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 2px; display: none;" id="paso5">
                    <div class="col-md-12">
                        <div class="summernote" id="textAreaP5">
                        </div>
                    </div>
                    <div class="col-md-12 top10">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="adjuntoP5" name="adjuntoP5" accept=".pdf">
                                <label class="custom-file-label" for="adjuntoP5" style="border-radius: 5px; font-size: 14px;" data-browse="Elegir">Archivo adjunto opcional</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 2px; display: none;" id="paso6">
                    <div class="col-md-12">
                        <div class="divarea" style="padding: 10px;" id="nueva_actividad">
                            {{-- *******************Actividad y etapa******************* --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="cron_actividad">Actividad <sup style="color: var(--danger)">*</sup></label>
                                                <textarea class="form-control" id="cron_actividad" rows="3" maxlength="2000" onkeyup="FiltrarCaracteres(this.id, 'itemLista');" style="font-size: 13px"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row" style="margin-top: 24px">
                                        <div class="col-md-6" style="margin-top: 5px"><button type="button" class="btn btn-secondary btn-sm btn-block" id="etapa1" onclick="SeleccionarEtapa(1);" style="padding: 0px;">Etapa de Planeación</button></div>
                                        <div class="col-md-6" style="margin-top: 5px"><button type="button" class="btn btn-secondary btn-sm btn-block" id="etapa2" onclick="SeleccionarEtapa(2);" style="padding: 0px;">Etapa de Ejecución</button></div>
                                        <div class="col-md-6" style="margin-top: 5px"><button type="button" class="btn btn-secondary btn-sm btn-block" id="etapa3" onclick="SeleccionarEtapa(3);" style="padding: 0px;">Etapa de Informe</button></div>
                                        <div class="col-md-6" style="margin-top: 5px"><button type="button" class="btn btn-secondary btn-sm btn-block" id="etapa4" onclick="SeleccionarEtapa(4);" style="padding: 0px;">Etapa de Cierre</button></div>
                                    </div>
                                    <span id="Mcron_etapa" class="msjRequerido">Debe seleccionar una etapa.</span>
                                </div>
                            </div>
                            {{-- *******************Meses y semanas******************* --}}
                            <div class="row top10 semanas">
                                <div class="col-md-12">
                                    <table class="table-bordered" style="font-size: 12px; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="4">Enero</th>
                                                <th class="text-center" colspan="4">Febrero</th>
                                                <th class="text-center" colspan="4">Marzo</th>
                                                <th class="text-center" colspan="4">Abril</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_1-1" onclick="SeleccionarSemana('1-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_1-2" onclick="SeleccionarSemana('1-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_1-3" onclick="SeleccionarSemana('1-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_1-4" onclick="SeleccionarSemana('1-4')">4</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_2-1" onclick="SeleccionarSemana('2-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_2-2" onclick="SeleccionarSemana('2-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_2-3" onclick="SeleccionarSemana('2-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_2-4" onclick="SeleccionarSemana('2-4')">4</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_3-1" onclick="SeleccionarSemana('3-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_3-2" onclick="SeleccionarSemana('3-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_3-3" onclick="SeleccionarSemana('3-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_3-4" onclick="SeleccionarSemana('3-4')">4</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_4-1" onclick="SeleccionarSemana('4-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_4-2" onclick="SeleccionarSemana('4-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_4-3" onclick="SeleccionarSemana('4-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_4-4" onclick="SeleccionarSemana('4-4')">4</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row semanas" style="margin-top: 5px;">
                                <div class="col-md-12">
                                    <table class="table-bordered" style="font-size: 12px; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="4">Mayo</th>
                                                <th class="text-center" colspan="4">Junio</th>
                                                <th class="text-center" colspan="4">Julio</th>
                                                <th class="text-center" colspan="4">Agosto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_5-1" onclick="SeleccionarSemana('5-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_5-2" onclick="SeleccionarSemana('5-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_5-3" onclick="SeleccionarSemana('5-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_5-4" onclick="SeleccionarSemana('5-4')">4</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_6-1" onclick="SeleccionarSemana('6-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_6-2" onclick="SeleccionarSemana('6-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_6-3" onclick="SeleccionarSemana('6-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_6-4" onclick="SeleccionarSemana('6-4')">4</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_7-1" onclick="SeleccionarSemana('7-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_7-2" onclick="SeleccionarSemana('7-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_7-3" onclick="SeleccionarSemana('7-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_7-4" onclick="SeleccionarSemana('7-4')">4</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_8-1" onclick="SeleccionarSemana('8-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_8-2" onclick="SeleccionarSemana('8-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_8-3" onclick="SeleccionarSemana('8-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_8-4" onclick="SeleccionarSemana('8-4')">4</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row semanas" style="margin-top: 5px;">
                                <div class="col-md-12">
                                    <table class="table-bordered" style="font-size: 12px; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="4">Septiembre</th>
                                                <th class="text-center" colspan="4">Octubre</th>
                                                <th class="text-center" colspan="4">Noviembre</th>
                                                <th class="text-center" colspan="4">Diciembre</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_9-1" onclick="SeleccionarSemana('9-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_9-2" onclick="SeleccionarSemana('9-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_9-3" onclick="SeleccionarSemana('9-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_9-4" onclick="SeleccionarSemana('9-4')">4</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_10-1" onclick="SeleccionarSemana('10-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_10-2" onclick="SeleccionarSemana('10-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_10-3" onclick="SeleccionarSemana('10-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_10-4" onclick="SeleccionarSemana('10-4')">4</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_11-1" onclick="SeleccionarSemana('11-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_11-2" onclick="SeleccionarSemana('11-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_11-3" onclick="SeleccionarSemana('11-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_11-4" onclick="SeleccionarSemana('11-4')">4</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_12-1" onclick="SeleccionarSemana('12-1')">1</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_12-2" onclick="SeleccionarSemana('12-2')">2</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_12-3" onclick="SeleccionarSemana('12-3')">3</button></td>
                                                <td class="text-center"><button type="button" class="btn btn-light" id="semana_12-4" onclick="SeleccionarSemana('12-4')">4</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <span id="Mcron_semanas" class="msjRequerido">Debe seleccionar al menos una semana del año.</span>
                            {{-- *******************Boton agregar actividad******************* --}}
                            <div class="row top10">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-sm btn-success" onclick="AgregarActividad();">Agregar actividad</button>
                                </div>
                            </div>
                        </div>
                        {{-- *******************Actividades agregadas******************* --}}
                        <div class="row top10" id="rowTablaActividades">
                            <div class="col-md-12">
                                <table class="table-bordered" style="font-size: 12px; width: 100%;" id="tablaActividades">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="text-align: center;">Actividad</th>
                                            <th scope="col" style="text-align: center;">Etapa</th>
                                            <th scope="col" style="width: 90px; text-align: center;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyTablaActividades">
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <span id="Mcronograma" class="msjRequerido"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2 text-left">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="AnteriorPaso();"><i class="fas fa-backward"></i> Anterior</button>
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" class="btn btn-sm btn-primary" onclick="SiguientePaso();">Siguiente <i class="fas fa-forward"></i></button>
                    </div>
                    <div class="col-md-8 text-right">
                        <button type="button" class="btn btn-sm btn-primary" onclick="GuardarPaso();">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<script src="{{asset('vendor/bootstrap/js/bs-custom-file-input.min.js')}}"></script>
<script src="{{asset('js/componentes/nuevo_plangestion.js')}}"></script>
