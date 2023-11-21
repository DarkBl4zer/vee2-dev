<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaModel;
use App\Models\ConfiguracionesModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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

    public function MsjRespuesta($exito, $error=false, $code=500){
        if ($exito) {
            return response()->json(array(
                "estado"=>true,
                "tipo"=>"success",
                "txt"=>"¡Registrado!"
            ), 200);
        } else {
            return response()->json(array(
                "estado"=>false,
                "tipo"=>"error",
                "txt"=>"¡Error!, detalle en consola.",
                "error"=>$error
            ),$code);
        }
    }

    public function Auditoria($id_usuario, $tipo, $modelo, $id_modelo, $old, $new){
        AuditoriaModel::create(array(
            'id_usuario' => $id_usuario,
            'tipo' => $tipo,
            'modelo' => $modelo,
            'id_modelo' => $id_modelo,
            'old_json' => ($old != null)?json_encode($old, JSON_UNESCAPED_UNICODE):null,
            'new_json' => json_encode($new, JSON_UNESCAPED_UNICODE)
        ));
    }

    public function ImagenFirma($firma){
        if (Storage::disk('local')->exists('/vee2_firmas/'.$firma)) {
            $path = storage_path().'/app/vee2_firmas/'.$firma;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $imgFirma = 'data:image/'.$type.';base64,'.base64_encode($data);
            return $imgFirma;
        } else{
            $noFirmaT = pathinfo(public_path('img/noFirma.jpg'), PATHINFO_EXTENSION);
            $noFirmaD = file_get_contents(public_path('img/noFirma.jpg'));
            return 'data:image/'.$noFirmaT.';base64,'.base64_encode($noFirmaD);
        }
    }
}
