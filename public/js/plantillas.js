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

    itemEstadoTabla(data, nombre="item"){
        return `<span style="font-weight: bold; color: var(--${(data.activo=='1')?"success":"danger"});">${(data.activo=='1')?"Activo":"Inactivo"}</span>
        <br>
        <i class="fas fa-play-circle" data-toggle="tooltip" data-placement="top" title="Activar ${nombre}" onclick="ConfirmarActivar(${data.id}, true);"></i>
        <i class="fas fa-pause-circle" data-toggle="tooltip" data-placement="top" title="Inactivar ${nombre}" onclick="ConfirmarActivar(${data.id}, false);"></i>`;
    }

    itemAccionesTabla(data){
        if (data.id == 0) {
            return `<i class="fas fa-save" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="ConfirmarGuardar(${data.id});"></i>`;
        } else {
            return `<i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="Editar" onclick="Editar(${data.id});" style="margin-right: 10px;"></i><i class="fas fa-save" data-toggle="tooltip" data-placement="top" title="Guardar cambios" onclick="ConfirmarGuardar(${data.id});"></i>`;
        }
    }

    itemValorTabla(data){
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
            <input type="text" maxlength="250" class="form-control" id="valorT3Id${data.id}" onkeyup="FiltrarCaracteres(this.id, 'valor_texto');" style="font-size: 14px; ${show3}">`;
        } else {
            return `<span class="dataFila${data.id}">${valor[data.tipo_valor-1]}</span><input type="text" class="form-control inputFila${data.id}" id="valorT1Id${data.id}" value="${data.id}" readonly="true" style="font-size: 14px; ${show1}">
            <input type="number" min="0" step="1" class="form-control inputFila${data.id}" id="valorT2Id${data.id}" value="${valor[1]}" style="font-size: 14px; ${show2}">
            <input type="text" maxlength="250" class="form-control inputFila${data.id}" id="valorT3Id${data.id}" value="${valor[2]}" onkeyup="FiltrarCaracteres(this.id, 'valor_texto');" style="font-size: 14px; ${show3}">`;
        }
    }

    itemInputText(data, tipo){
        let valor = '';
        switch (tipo) {
            case 1:
                valor = (data.prefijo!=null)?data.prefijo:'';
                break;
            case 2:
                valor = (data.nombre!=null)?data.nombre:'';
                break;
            case 3:
                valor = (data.sufijo!=null)?data.sufijo:'';
                break;
            case 4:
                valor = (data.formato!=null)?data.formato:'';
                break;
            default:
                break;
        }
        if (data.id == 0) {
            return `<span class="hide">${data.id}</span><input type="text" maxlength="250" class="form-control" id="textT${tipo}Id${data.id}" onkeyup="FiltrarCaracteres(this.id, 'texto${tipo}');" style="font-size: 14px;">`;
        } else {
            return `<span class="hide">${data.id}</span><span class="dataFila${data.id}">${valor}</span><input type="text" maxlength="250" class="form-control inputFila${data.id}" id="textT${tipo}Id${data.id}" value="${valor}" onkeyup="FiltrarCaracteres(this.id, 'texto${tipo}');" style="font-size: 14px;">`;
        }
    }

    itemInputNumber(data){
        if (data.id == 0) {
            return `<input type="number" min="0" step="1" class="form-control" id="numberId${data.id}" style="font-size: 14px;">`;
        } else {
            return `<span class="dataFila${data.id}">${(data.id_padre!=null)?data.id_padre:''}</span><input type="number" min="0" step="1" class="form-control inputFila${data.id}" id="numberId${data.id}" value="${(data.id_padre!=null)?data.id_padre:''}" style="font-size: 14px;">`;
        }
    }

    itemEliminarTabla(data, nombre="item"){
        return `<i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Eliminar ${nombre}" onclick="ConfirmarEliminar(${data.id});"></i>
        <i class="fas fa-clone" data-toggle="tooltip" data-placement="top" title="Reemplazar ${nombre}" onclick="Reemplazar(${data.id});"></i>`;
    }

    itemLinkTabla(data){
        return `<a href="#" onclick="ClickLink${data.xid}(${data.id});">${data.texto}</a>`;
    }
}
