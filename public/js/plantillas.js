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
                        <span class="font-weight-bold">${data.texto}</span>
                    </div>
                </a>`;
    }

    itemInputText(data){
        return `<span class="hide">${(data.valor==null)?"":data.valor}</span><span class="dataFila${data.id}">${(data.valor==null)?"":data.valor}</span>
                <input type="text" class="form-control inputFila${data.id}" maxlength="${data.max}" id="inputText${data.xid}" onkeyup="FiltrarCaracteres(this.id, '${data.filtro}');" value="${(data.valor==null)?"":data.valor}" style="font-size: 14px;">`;
    }

    itemEstadoTabla(data){
        return `<span style="font-weight: bold; color: var(--${(data.activo=='1')?"success":"danger"});">${(data.activo=='1')?"Activo":"Inactivo"}</span>
        <br>
        <i class="fas fa-play-circle" data-toggle="tooltip" data-placement="top" title="Activar" onclick="ConfirmarActivar(${data.id}, true);"></i>
        <i class="fas fa-pause-circle" data-toggle="tooltip" data-placement="top" title="Inactivar" onclick="ConfirmarActivar(${data.id}, false);"></i>`;
    }

    itemAccionesTabla(data){
        let html = "";
        if (data.editar) {
            html += `<i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="Editar" onclick="Editar(${data.id});"></i>`;
        }
        if (data.reemplazar) {
            html += `<i class="fas fa-clone" data-toggle="tooltip" data-placement="top" title="Reemplazar" onclick="Reemplazar(${data.id});"></i>`;
        }
        if (data.eliminar) {
            html += `<i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="ConfirmarEliminar(${data.id});"></i>`;
        }
        if (data.guardar) {
            html += `<i class="fas fa-save inputFila${data.id}" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="ConfirmarGuardar(${data.id});"></i>`;
        }
        if (data.conflicto) {
            if (data.estado == 1 || data.estado == 3) {
                html += `<i class="fas fa-file-signature" data-toggle="tooltip" data-placement="top" title="Imparcialidad y conflictos de interés" onclick="CrearDeclaracion(${data.id});"></i>`;
            } else{
                html += `<i class="fas fa-file-contract" data-toggle="tooltip" data-placement="top" title="Imparcialidad y conflictos de interés" onclick="VerDeclaracion(${data.id}, ${data.estado}, '${data.dec_firmada}');"></i>`;
            }
        }
        if (data.generar) {
            if (data.estado == 1 || data.estado == 3) {
                html += `<i class="fas fa-file-signature" data-toggle="tooltip" data-placement="top" title="Generar/Firmar" onclick="GenerarFirmar(${data.id});"></i>`;
            } else{
                html += `<i class="fas fa-file-contract" data-toggle="tooltip" data-placement="top" title="Ver firmado" onclick="VerFirmado('${data.archivo}');"></i>`;
            }
        }
        if (data.generar_pg && data.estado > 1) {
            html += `<i class="fas fa-file-pdf" data-toggle="tooltip" data-placement="top" title="Vista previa" onclick="VistaPrevia(${data.id}, ${data.estado});"></i>`;
        }
        if (data.next) {
            html += `<i class="fas fa-forward" data-toggle="tooltip" data-placement="top" title="Siguiente" onclick="Siguiente(${data.id});"></i>`;
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

}
