<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionesModel;
use App\Models\PerfilesModel;
use App\Models\UsuarioNotificacionModel;
use Carbon\Carbon;
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

    public function PermisosPorPagina($url) {
        $permisos = (object)Session::get('PermisosVee');
        $arrP = array();
        foreach ($permisos->rol as $permiso) {
            if($permiso->url == $url){
                if (is_null($permiso->estados)) {
                    array_push($arrP, $permiso->accion.": true");
                } else{
                    array_push($arrP, $permiso->accion.": ".$permiso->estados);
                }
            }
        }
        foreach ($permisos->usuario as $permiso) {
            if($permiso->url == $url){
                if (is_null($permiso->estados)) {
                    array_push($arrP, $permiso->accion.": true");
                } else{
                    array_push($arrP, $permiso->accion.": ".$permiso->estados);
                }
            }
        }
        return "{".implode(", ", $arrP)."}";
    }

    public function Notificar($noti){
        $sesion = (object)Session::get('UsuarioVee');
        $delegada = (isset($noti->id_delegada))?$noti->id_delegada:$sesion->trabajo->id_delegada;
        if ($noti->para == 'Coordinador') {
            $tipoCoord = ($sesion->trabajo->tipo_delegada == 1)?"PD":"LOCALES";
            $perfiles = PerfilesModel::where('id_rol', 2)->where('activo', true)->where('tipo_coord', $tipoCoord)->get();
        }
        if ($noti->para == 'Delegado') {
            $perfiles = PerfilesModel::where('id_rol', 3)->where('activo', true)->where('id_delegada', $delegada)->get();
        }
        if ($noti->para == 'Enlace') {
            $perfiles = PerfilesModel::where('id_rol', 4)->where('activo', true)->where('id_delegada', $delegada)->get();
        }
        if ($noti->para == 'Funcionarios') {
            $perfiles = PerfilesModel::whereIn('id_usuario', $noti->funcionarios)->where('id_rol', 5)->where('activo', true)->where('id_delegada', $delegada)->get();
        }
        foreach ($perfiles as $item) {
            UsuarioNotificacionModel::create(array(
                'id_usuario' => $item->id_usuario,
                'id_perfil' => $item->id,
                'tipo' => $noti->tipo,
                'texto' => $noti->texto,
                'url' => $noti->url
            ));
        }
    }

    function DescontarDiasHabiles($fecha, $dias){
        $sesion = (object)Session::get('UsuarioVee');
        $fecha_inicio = Carbon::createFromFormat('Y-m-d H:i:s', $fecha);
        $x = 0;
        $fecha_fin = null;
        $diaTemp = $fecha_inicio;
        while($x < $dias) {
            $diaTemp = $fecha_inicio->subDay();
            if(!$diaTemp->isWeekend()){
                if(!in_array($diaTemp->format('d.m.Y'), $sesion->festivos)){
                    $x++;
                    if($x == $dias){
                        $fecha_fin = $diaTemp->format('Y-m-d');
                    }
                }
            }
        }
        return $fecha_fin;
    }

}
