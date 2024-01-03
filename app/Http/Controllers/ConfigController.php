<?php

namespace App\Http\Controllers;

use App\Models\UsuariosModel;
use Exception;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function Usuarios(Request $request){
        //try {
            $sesion = (object)$request->sesion;
            $datos = UsuariosModel::get();
            return response()->json($datos);
        /*} catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }*/
    }
}
