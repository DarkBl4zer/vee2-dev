@php
    $tipoValor = array(0, $lista->id, $lista->valor_numero, $lista->valor_texto);
    $valor = $tipoValor[$lista->tipo_valor];
@endphp
<option value="{{$valor}}">{{$lista->nombre}}</option>
