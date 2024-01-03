class PlantillaHTML{
    itemNotificacion(data){
        return `<a id="noti${data.id}" class="dropdown-item d-flex align-items-center" href="#" onclick="Notificacion(${data.id}, '${data.url}');">
                    <div class="mr-3">
                        <div class="icon-circle bg-${data.tipo}">
                            <i class="fas fa-${(data.tipo=="success")?"check":(data.tipo=="danger")?"exclamation":"pen"} text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">${data.creado}</div>
                        <span ${(data.activo=='1')?'class="font-weight-bold"':''}>${data.texto}</span>
                    </div>
                </a>`;
    }

    itemInputText(data){
        return `<span class="hide">${(data.valor==null)?"":data.valor}</span><span class="dataFila${data.id}">${(data.valor==null)?"":data.valor}</span>
                <input type="text" class="form-control inputFila${data.id}" maxlength="${data.max}" id="inputText${data.xid}" onkeyup="FiltrarCaracteres(this.id, '${data.filtro}');" value="${(data.valor==null)?"":data.valor}" style="font-size: 14px;">`;
    }

    itemEstadoTabla(data){
        let html = `<span style="font-weight: bold; color: var(--${(data.activo=='1')?"success":"danger"});">${(data.activo=='1')?"Activo":"Inactivo"}</span><br>`;
        if (permisos.activar) {
            html = html + `<i class="fas fa-play-circle" data-toggle="tooltip" data-placement="top" title="Activar" onclick="ConfirmarActivar(${data.id}, true);"></i>
            <i class="fas fa-pause-circle" data-toggle="tooltip" data-placement="top" title="Inactivar" onclick="ConfirmarActivar(${data.id}, false);"></i>`;
        }
        return html;
    }

    itemAccionesActaTabla(data){
        let html = "";
        if (data.reemplazar && permisos.reemplazar) {
            html += `<i class="fas fa-clone" data-toggle="tooltip" data-placement="top" title="Reemplazar" onclick="Reemplazar(${data.id});"></i>`;
        }
        return html;
    }

    itemAccionesTabla(data){
        let html = "";
        if (data.documentos) {
            html += `<i class="fas fa-stamp" data-toggle="tooltip" data-placement="top" title="Documentos" onclick="DocumentosAccion(${data.id_accion});"></i>`;
        }
        if (data.detalle) {
            html += `<i class="fas fa-file-invoice" data-toggle="tooltip" data-placement="top" title="Detalle de la acción" onclick="VerDetalle(${data.id});"></i>`;
        }
        if (data.editar && permisos.editar) {
            html += `<i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="Editar" onclick="Editar(${data.id});"></i>`;
        }
        if (data.conflicto && permisos.conflicto) {
            if (data.estado == 1) {
                html += `<i class="fas fa-file-signature" data-toggle="tooltip" data-placement="top" title="Imparcialidad y conflictos de interés" onclick="CrearDeclaracion(${data.id});"></i>`;
            } else{
                if (data.dec_firmada == '') {
                    html += `<i class="fas fa-file-signature" data-toggle="tooltip" data-placement="top" title="Imparcialidad y conflictos de interés" onclick="CrearDeclaracion(${data.id});"></i>`;
                } else {
                    let conflicto = "";
                    if (data.dec_conflicto=="1") {
                        conflicto = '<i class="fas fa-exclamation" style="color: var(--danger); position: absolute; font-size: 16px; right: -4px;"></i>';
                    }
                    html += `<i class="fas fa-file-contract" data-toggle="tooltip" data-placement="top" title="Imparcialidad y conflictos de interés" onclick="VerDeclaracion(${data.id}, ${data.estado}, '${data.dec_firmada}');" style="position: relative;">${conflicto}</i>`;
                }
            }
        }
        if (permisos.cambiod && data.dec_conflicto) {
            html += `<i class="fas fa-people-arrows" data-toggle="tooltip" data-placement="top" title="Delegado(a) con conflictos de interés" onclick="CambiarDelegado(${data.id})"></i>`;
        }
        return html;
    }

    itemAccionesPTTabla(data){
        let html = "";
        if (data.editar && permisos.editar) {
            html += `<i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="Editar" onclick="Editar(${data.id});"></i>`;
        }
        if (data.generar) {
            if (data.estado == 1 || data.estado == 3) {
                html += `<i class="fas fa-file-signature" data-toggle="tooltip" data-placement="top" title="Generar/Firmar" onclick="GenerarFirmar(${data.id});"></i>`;
            } else{
                html += `<i class="fas fa-file-contract" data-toggle="tooltip" data-placement="top" title="Ver firmado" onclick="VerFirmado('${data.archivo}');"></i>`;
            }
        }
        if (data.aprobar) {
            if (permisos.aprobar && permisos.aprobar.includes(parseInt(data.estado)) && !data.r_activo) {
                html += `<i class="fas fa-tasks" data-toggle="tooltip" data-placement="top" title="Aprobar/Rechazar" onclick="ConfirmarAprobar(${data.id}, ${data.estado});"></i>`;
            }
        }
        if (data.rechazos) {
            let respuesta = false;
            if (permisos.respuesta && permisos.respuesta.includes(parseInt(data.estado))) {
                respuesta = true;
            }
            if (data.r_activo) {
                html += `<i class="fas fa-bell" data-toggle="tooltip" data-placement="top" title="Solicitudes de modificación" onclick="Rechazos(${data.id}, ${respuesta});" style="color: var(--orange);"></i>`;
            } else {
                html += `<i class="fas fa-bell-slash" data-toggle="tooltip" data-placement="top" title="Solicitudes de modificación" onclick="Rechazos(${data.id}, 'sin_nota');"></i>`;
            }
        }
        if (permisos.modificar && permisos.modificar.includes(parseInt(data.estado))) {
            html += `<i class="fas fa-sync-alt" data-toggle="tooltip" data-placement="top" title="Modificar plan de trabajo" onclick="ConfirmarModificar(${data.id});"></i>`;
        }
        return html;
    }

    itemAccionesPGTabla(data){
        let html = "";
        if (data.documentos) {
            html += `<i class="fas fa-stamp" data-toggle="tooltip" data-placement="top" title="Documentos" onclick="DocumentosAccion(${data.id});"></i>`;
        }
        if (data.detalle) {
            html += `<i class="fas fa-file-invoice" data-toggle="tooltip" data-placement="top" title="Detalle de la acción" onclick="VerDetalle(${data.id});"></i>`;
        }
        if (data.editar && permisos.editar && permisos.editar.includes(parseInt(data.estado))) {
            html += `<i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="Editar" onclick="Editar(${data.id});"></i>`;
        }
        if (data.conflicto && permisos.conflicto) {
            if (data.estado == 2 && data.dec_firmada == null) {
                html += `<i class="fas fa-file-signature" data-toggle="tooltip" data-placement="top" title="Imparcialidad y conflictos de interés" onclick="CrearDeclaracion(${data.id});"></i>`;
            }
            if(data.estado > 2 || data.dec_firmada != null){
                html += `<i class="fas fa-file-contract" data-toggle="tooltip" data-placement="top" title="Imparcialidad y conflictos de interés" onclick="VerDeclaracion(${data.id}, ${data.estado}, '${data.dec_firmada}');"></i>`;
            }
        }
        if (data.generar) {
            if(data.estado > 1 && data.estado < 8){
                html += `<i class="fas fa-file-pdf" data-toggle="tooltip" data-placement="top" title="Vista previa plan de gestión" onclick="VistaPrevia(${data.id}, ${data.estado});"></i>`;
            }
            if (data.estado == 8) {
                html += `<i class="fas fa-file-pdf" data-toggle="tooltip" data-placement="top" title="Ver firmado" onclick="VerFirmado('${data.archivo}');"></i>`;
            }
        }
        if (data.aprobar) {
            let title = '';
            if (data.estado == 3) {
                title = 'Viabilidad delegado';
            }
            if (data.estado == 4) {
                title = 'Mesa trabajo delegado';
            }
            if (permisos.aprobar && permisos.aprobar.includes(parseInt(data.estado))) {
                html += `<i class="fas fa-tasks" data-toggle="tooltip" data-placement="top" title="${title}" onclick="ConfirmarAprobar(${data.id}, ${data.estado});"></i>`;
            }
        }
        if (data.rechazos) {
            let respuesta = false;
            if (permisos.respuesta && permisos.respuesta.includes(parseInt(data.estado))) {
                respuesta = true;
            }
            if (data.r_activo) {
                html += `<i class="fas fa-bell" data-toggle="tooltip" data-placement="top" title="Solicitudes de modificación" onclick="Rechazos(${data.id}, ${respuesta});" style="color: var(--orange);"></i>`;
            } else {
                html += `<i class="fas fa-bell-slash" data-toggle="tooltip" data-placement="top" title="Solicitudes de modificación" onclick="Rechazos(${data.id}, 'sin_nota');"></i>`;
            }
        }
        if (permisos.equipo && permisos.equipo.includes(parseInt(data.estado))) {
            html += `<i class="fas fa-users-cog" data-toggle="tooltip" data-placement="top" title="Cambiar equipo" onclick="CambiarEquipo(${data.id});"></i>`;
        }
        return html;
    }

    itemValorLista(data){
        let show1, show2, show3;
        show1 = show2 = show3 = `display: none;`;
        let valor = ['','',''];
        if (data.tipo_valor==1) {
            show1 = "";
            valor[0] = data.id;
        }else if (data.tipo_valor==2) {
            show2 = "";
            valor[1] = (data.valor_numero!=null)?data.valor_numero:'';
        } else{
            show3 = "";
            valor[2] = (data.valor_texto!=null)?data.valor_texto:'';
        }
        if (data.id == 0) {
            return `<input type="text" class="form-control" id="valorT1Id${data.id}" value="auto" readonly="true" style="font-size: 14px; ${show1}">
            <input type="number" min="0" step="1" class="form-control" id="valorT2Id${data.id}" style="font-size: 14px; ${show2}">
            <input type="text" maxlength="${data.max}" class="form-control" id="valorT3Id${data.id}" onkeyup="FiltrarCaracteres(this.id, '${data.filtro}');" style="font-size: 14px; ${show3}">`;
        } else {
            return `<span class="dataFila${data.id}">${valor[data.tipo_valor-1]}</span><input type="text" class="form-control inputFila${data.id}" id="valorT1Id${data.id}" value="${data.id}" readonly="true" style="font-size: 14px; ${show1}">
            <input type="number" min="0" step="1" class="form-control inputFila${data.id}" id="valorT2Id${data.id}" value="${valor[1]}" style="font-size: 14px; ${show2}">
            <input type="text" maxlength="${data.max}" class="form-control inputFila${data.id}" id="valorT3Id${data.id}" value="${valor[2]}" onkeyup="FiltrarCaracteres(this.id, '${data.filtro}');" style="font-size: 14px; ${show3}">`;
        }
    }

    itemLinkTabla(data){
        return `<a href="#" onclick="ClickLink${data.xid}(${data.id});">${data.texto}</a>`;
    }

    options(data){
        let html = `<option value="">...</option>`;
        data.forEach(element => {
            html += `<option value="${element.id}">${element.nombre}</option>`;
        });
        return html;
    }

    itemCheckbox(data){
        return `<input type="checkbox" id="accionNo${data.id}" value="${data.id}" onclick="CheckAccion(${data.id});" ${(data.checked)?'checked':''}>`;
    }

    itemPerfilesUsuario(data){
        let html = `<i class="fas fa-plus-circle mas_perfil" data-toggle="tooltip" data-placement="top" title="" onclick="NuevoPerfil(${data.id});" data-original-title="Nuevo perfil"></i>`;
        html += `<ul style="position: relative; margin-bottom: 0px;">`;
        data.apperfiles.forEach(element => {
            if (element.id_rol == 1) {
                html += `<li>${element.rol} <i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Eliminar perfil" onclick="ConfirmarEliminarPerfil(${element.id});" style="font-size: 16px;"></i></li>`;
            }
            if (element.id_rol == 2) {
                html += `<li>${element.rol} <span style="font-size: 11px;">${element.tipo_coord}</span> <i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Eliminar perfil" onclick="ConfirmarEliminarPerfil(${element.id});" style="font-size: 16px;"></i></li>`;
            }
            if (element.id_rol > 2) {
                html += `<li>${element.rol} <span style="font-size: 11px;">${element.delegada}</span> <i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Eliminar perfil" onclick="ConfirmarEliminarPerfil(${element.id});" style="font-size: 16px;"></i></li>`;
            }
        });
        html += `</ul>`;
        return html;
    }

    itemEquipoPlangestion(data){
        let html = `<ul style="position: relative; margin-bottom: 0px;">`;
        data.forEach(element => {
            html += `<li>${element.nombre} ${(element.firmado)?'<i class="fas fa-check" data-toggle="tooltip" data-placement="top" title="Firmado" style="font-size: 14px;"></i>':'<i class="fas fa-ellipsis-h" data-toggle="tooltip" data-placement="top" title="Sin firmar" style="font-size: 14px;"></i>'}</li>`;
        });
        html += `</ul>`;
        return html;
    }

    itemTablaActividadesCronograma(data){
        let html = '<tr id="trActividad_'+index+'"><td style="text-align: left;">'+$('#cron_actividad').val()+'</td><td>'+nombreEtapa+'</td><td class="text-center"><i class="fas fa-trash-alt" onclick="EliminarActividad('+index+');" style="font-size: 18px;"></i></td></tr>';
        $('#bodyTablaActividades').append(html);
    }

    itemsTablaDocumentos(data){
        let html = '';
        data.forEach(element => {
            html += `<tr>
                <td>${element.id}</td>
                <td>${element.t_tipo}</td>
                <td>${element.fecha}</td>
                <td>${element.usuario}</td>
                <td style="text-align: left;"><a href="/back/descargar_archivo/?carpeta=${element.carpeta}&archivo=${element.archivo}" target="_blank">${element.n_original}</a></td>
            </tr>`;
        });
        return html;
    }

    itemsTablaDetalleAccion(data){
        return `
        <tr><td>Número</td><td>${data.numero}</td></tr>
        <tr><td>Tipo de actuación</td><td>${data.actuacion}</td></tr>
        <tr><td>Acción para seguimiento</td><td>${(data.id_padre!=null)?'APC'+data.id_padre+' - '+data.padre.titulo:''}</td></tr>
        <tr><td>Tema principal</td><td>${(data.archivoacta.padre != null)?data.archivoacta.padre:data.archivoacta.tema}</td></tr>
        <tr><td>Tema secundario</td><td>${(data.archivoacta.padre != null)?data.archivoacta.tema:''}</td></tr>
        <tr><td>Título</td><td>${data.titulo}</td></tr>
        <tr><td>Objetivo general</td><td>${data.objetivo_general}</td></tr>
        <tr><td>Entidad(s)</td><td>${data.entidades.string}</td></tr>
        <tr><td># Profesionales</td><td>${data.numero_profesionales}</td></tr>
        <tr><td>Fecha plan de gestión</td><td>${data.fecha_plangestion.substring(0, 10).split('-').reverse().join('/')}</td></tr>
        <tr><td>Fecha inicio acción</td><td>${data.fecha_inicio.substring(0, 10).split('-').reverse().join('/')}</td></tr>
        <tr><td>Fecha fin acción</td><td>${data.fecha_final.substring(0, 10).split('-').reverse().join('/')}</td></tr>
        `;
    }

    itemVigente(data){
        let html = '';
        if (data.vigente == '1') {
            html = '<i class="fas fa-check" style="cursor: default;"></i>';
        }
        return html;
    }

    lineaTiempoRechazos(data){
        let html = '';
        data.forEach(element => {
            let rechazo = `
            <div class="row"><div class="col-md-10">
                <div class="alert alert-secondary" role="alert" style="float: left;">
                    <p style="font-size: 13px;">${element.texto_rechazo}</p>
                    <p class="crea_chat">
                        <span style="font-size: 10px;">${element.nombre_rechazo}</span>
                        <br>
                        <span style="font-size: 10px;">${element.fecha_rechazo}</span>
                    </p>
                </div>
            </div><div class="col-md-2"></div></div>
            `;
            let respuesta = '';
            if (element.nombre_respuesta != null) {
                respuesta = `
                <div class="row"><div class="col-md-2"></div><div class="col-md-10">
                    <div class="alert alert-success" role="alert" style="float: right;">
                        <p style="font-size: 13px;">${element.texto_respuesta}</p>
                        <p class="crea_chat">
                            <span style="font-size: 10px;">${element.nombre_respuesta}</span>
                            <br>
                            <span style="font-size: 10px;">${element.fecha_respuesta}</span>
                        </p>
                    </div>
                </div></div>
                `;
            }
            html += rechazo+respuesta;
        });
        return html;
    }

    itemsTablaDelegados(data){
        let html = '';
        data.forEach(element => {
            html += `<tr>
                <td>${element.delegada}</td>
                <td>${element.nombre}</td>
                <td style="vertical-align: middle;"><button type="button" class="btn btn-primary btn-sm" onclick="SeleccionarDelegado(${element.id_usuario}, ${element.id_delegada});">Seleccionar</button></td>
            </tr>`;
        });
        return html;
    }

}
