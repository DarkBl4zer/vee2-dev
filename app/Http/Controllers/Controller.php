<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaModel;
use App\Models\ConfiguracionesModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function Logeado(){
        $temp = Session::get('UsuarioVee');
        if (is_null($temp)) {
            return false;
        } else{
            if (count($temp)==0) {
                return false;
            } else{
                return (object)$temp[0];
            }
        }
    }

    public function Fin(){
        Session::forget('UsuarioVee');
        return Redirect::to(route('fake_login'));
        $config = ConfiguracionesModel::where('nombre', 'UrlSinproc')->first();
        return Redirect::to($config->t_valor."config/cerrar_session.php");
    }

    public function MsjRespuesta($exito, $error=false){
        if ($exito) {
            return response()->json(array(
                "estado"=>true,
                "tipo"=>"success",
                "txt"=>"¡Registrado!"
            ));
        } else {
            return response()->json(array(
                "estado"=>false,
                "tipo"=>"error",
                "txt"=>"¡Error!, detalle en consola.",
                "error"=>$error
            ),500);
        }
    }

    public function Auditoria($id_usuario, $tipo, $modelo, $old, $new){
        AuditoriaModel::create(array(
            'id_usuario' => $id_usuario,
            'tipo' => $tipo,
            'modelo' => $modelo,
            'old_json' => ($old != null)?json_encode($old, JSON_UNESCAPED_UNICODE):null,
            'new_json' => json_encode($new, JSON_UNESCAPED_UNICODE)
        ));
    }
}
