<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Bogota');

use App\Models\FirmasModel;
use App\Models\ListasModel;
use App\Models\PerfilesModel;
use App\Models\RolesModel;
use App\Models\RolSubMenuModel;
use App\Models\UsuarioNotificacionModel;
use App\Models\UsuariosModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BackendController extends Controller
{
    public function prueba(){
        $sesion = Session::get('UsuarioVee');
        $lista = ListasModel::where('id', 4)->first();
        $tipoValor = array(0, $lista->id, $lista->valor_numero, $lista->valor_texto);
        $valor = $tipoValor[$lista->tipo_valor];
        return $valor;
    }
    /* ====================================================== LOGIN ====================================================== */
    public function Login(Request $request){
        if (!isset($request->delegada) || !isset($request->cedula) || $request->delegada == "" || $request->cedula == "") {
            return $this->Fin();
        } else{
            $usuario = UsuariosModel::where('cedula', $request->cedula)->first();
            if (!(array)$usuario) {
                return Redirect::to(route('sinregistro'));
            } else {
                $sesion = array(
                    "id" => $usuario->id,
                    "cedula" => $usuario->cedula,
                    "email" => $usuario->email,
                    "nombre" => $usuario->nombre,
                    "d_sinproc" => $request->delegada,
                    "perfiles" => $usuario->perfiles,
                    "trabajo" => (object)array(
                        "id_perfil" => $usuario->perfiles[0]->id
                    ),
                    "menu" => $this->MenusPorRol($usuario->perfiles[0]->rol->id)
                );
                //return $sesion;
                Session::put('UsuarioVee', $sesion);
                return Redirect::to(route('inicio'));
            }
        }
    }


    /* ====================================================== BASE ====================================================== */
    public function VariablesTrabajo(Request $request){
        try {
            $sesion = (object)Session::get('UsuarioVee');
            $sesion->trabajo->id_perfil = $request->id;
            $id_rol = PerfilesModel::where('id', $request->id)->first()->id_rol;
            $sesion->menu = $this->MenusPorRol($id_rol);
            Session::put('UsuarioVee', $sesion);
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    public function Notificaciones(Request $request){
        $sesion = (object)Session::get('UsuarioVee');
        if ($request->todo) {
            $notificaciones = UsuarioNotificacionModel::where('id_usuario', $sesion->id)
                                                        ->where('eliminado', false)
                                                        ->where('id_perfil', $sesion->trabajo->id_perfil)
                                                        ->orderBy('created_at', 'desc')->get();
        } else{
            $notificaciones = UsuarioNotificacionModel::where('id_usuario', $sesion->id)
                                                        ->where('eliminado', false)
                                                        ->where('id_perfil', $sesion->trabajo->id_perfil)
                                                        ->where('activo', true)
                                                        ->orderBy('created_at', 'desc')->get();
        }
        return response()->json($notificaciones);
    }

    public function NotificacionesVista(Request $request){
        try {
            UsuarioNotificacionModel::where('id', $request->id)->update(array("activo" => $request->estado));
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    private function MenusPorRol($rol){
        $menus = RolesModel::where('id', $rol)->first()->menus;
        $tempMenu = array();
        foreach ($menus as $item) {
            $tempSubMenu = array();
            if ($item->tipo == "MENU") {
                foreach ($item->submenus as $item2) {
                    $cont = RolSubMenuModel::where('id_rol', $rol)->where('id_submenu', $item2->id)->where('activo', true)->count();
                    if ($cont > 0) {
                        $arrSub = array(
                            'nombre' => $item2->nombre,
                            'url' => $item2->url,
                            'orden' => $item2->orden,
                        );
                        array_push($tempSubMenu, $arrSub);
                    }
                }
            }
            $arrMenu = array(
                'tipo' => $item->tipo,
                'icono' => $item->icono,
                'nombre' => $item->nombre,
                'descripcion' => $item->descripcion,
                'url' => $item->url,
                'orden' => $item->orden,
                'submenu' => $tempSubMenu,
            );
            array_push($tempMenu, $arrMenu);
        }
        return $tempMenu;
    }

    /* ====================================================== NOTIFICACIONES ====================================================== */
    public function EliminarNotificacion(Request $request){
        try {
            UsuarioNotificacionModel::where('id', $request->id)->update(array("activo" => false, "eliminado" => true));
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    /* ====================================================== CONFIGURACIONES / USUARIOS ====================================================== */
    public function AgregarPerfil(Request $request){
        try {
            if ($request->rol == 1) {
                $cont = PerfilesModel::where('id_usuario', $request->id)->where('activo', true)->where('id_rol', $request->rol)->count();
            }
            if ($request->rol == 2) {
                $cont = PerfilesModel::where('id_usuario', $request->id)->where('activo', true)->where('id_rol', $request->rol)->where('tipo_coord', $request->tipo)->count();
            }
            if ($request->rol > 2) {
                $cont = PerfilesModel::where('id_usuario', $request->id)->where('activo', true)->where('id_rol', $request->rol)->where('id_delegada', $request->delegada)->count();
            }
            if ($cont > 0) {
                return response()->json(array(
                    "estado"=>false,
                    "tipo"=>"error",
                    "txt"=>"¡Error!, perfil existente."
                ));
            } else {
                $sesion = (object)$request->sesion;
                $new = array(
                    'id_usuario' => $request->id,
                    'id_rol' => $request->rol,
                    'id_delegada' => $request->delegada,
                    'tipo_coord' => $request->tipo,
                    'usuario_crea' => $sesion->cedula
                );
                PerfilesModel::create($new);
                $this->Auditoria($sesion->id, "INSERT", "PerfilesModel", null, $new);
                return $this->MsjRespuesta(true);
            }
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    public function EliminarPerfil(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $old = PerfilesModel::where('id', $request->id)->first();
            $new = array('activo' => false);
            PerfilesModel::where('id', $request->id)->update($new);
            $this->Auditoria($sesion->id, "DELETE", "PerfilesModel", $old, $new);
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    public function ActivarUsuario(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $old = UsuariosModel::where('id', $request->id)->first();
            $new = array('activo' => $request->activar);
            UsuariosModel::where('id', $request->id)->update($new);
            $this->Auditoria($sesion->id, "UPDATE", "UsuariosModel", $old, $new);
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    public function SincronizarUsuarios(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $usuariosSP = $request->all();
            UsuariosModel::where('id', '>', 2)->update(array('activo' => false));
            foreach ($usuariosSP as $item) {
                $item = (object)$item;
                $usuario = array(
                    'cedula' => $item->cedula,
                    'nombre' => $item->nombre,
                    'email' => $item->nombre,
                    'activo' => true,
                    'usuario_crea' => $sesion->cedula
                );
                $cont = UsuariosModel::where('cedula', intval($item->cedula))->count();
                if ($cont == 0) {
                    $id_usuario = UsuariosModel::create($usuario)->id;
                    PerfilesModel::create(array(
                        'id_usuario' => $id_usuario,
                        'id_rol' => 5,
                        'id_delegada' => intval($item->id_dep),
                        'usuario_crea'  => $sesion->cedula
                    ));
                } else{
                    UsuariosModel::where('cedula', intval($item->cedula))->update($usuario);
                }
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    /* ====================================================== CONFIGURACIONES / FIRMAS ====================================================== */
    public function GuardarFirma(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            if (isset($request->canvaFirma)) {
                $date = Carbon::now()->format('YmdHisu');
                $extension = explode('/', explode(':', substr($request->canvaFirma, 0, strpos($request->canvaFirma, ';')))[1])[1];   // .jpg .png .pdf
                $replace = substr($request->canvaFirma, 0, strpos($request->canvaFirma, ',')+1);
                $image = str_replace($replace, '', $request->canvaFirma);
                $image = str_replace(' ', '+', $image);
                Storage::put('vee2_firmas/cnv_'.$date.'.'.$extension, base64_decode($image));
                $firmas = FirmasModel::where('id_usuario', $sesion->id)->get();
                if (isset($request->inputFirma)) {
                    if (isset($firmas[0])) {
                        FirmasModel::where('id_usuario', $sesion->id)->update(array('activo' => false));
                    }
                    $request->file('inputFirma')->storeAs('vee2_firmas', 'inp_'.$date.'.'.$request->file('inputFirma')->extension());
                    $firma = array(
                        'id_usuario' => $sesion->id,
                        'inp_firma' => 'inp_'.$date.'.'.$request->file('inputFirma')->extension(),
                        'cnv_firma' => 'cnv_'.$date.'.'.$extension,
                        'escala' => $request->escalaFirma
                    );
                    FirmasModel::create($firma);
                    $this->Auditoria($sesion->id, "INSERT", "FirmasModel", null, $firma);
                } else{
                    $old = $firmas[0];
                    $new = array('cnv_firma' => 'cnv_'.$date.'.'.$extension, 'escala' => $request->escalaFirma);
                    FirmasModel::where('id', $firmas[0]->id)->update($new);
                    $this->Auditoria($sesion->id, "UPDATE", "FirmasModel", $old, $new);
                }
                DB::commit();
                return $this->MsjRespuesta(true);
            } else{
                return $this->MsjRespuesta(false, "Firma no recibida.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    /* ====================================================== CONFIGURACIONES / LISTAS ====================================================== */
    public function DatosConfigLista(Request $request){
        try {
            $datos = ListasModel::where('tipo', $request->valor)->get();
            return response()->json($datos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    public function CrearActualizarItemLista(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $new = array();
            ($request->prefijo!=null)?$new["prefijo"]=$request->prefijo:null;
            ($request->nombre!=null)?$new["nombre"]=$request->nombre:null;
            ($request->sufijo!=null)?$new["sufijo"]=$request->sufijo:null;
            ($request->valor_texto!=null)?$new["valor_texto"]=$request->valor_texto:null;
            ($request->valor_numero!=null)?$new["valor_numero"]=$request->valor_numero:null;
            ($request->tipo_valor!=null)?$new["tipo_valor"]=$request->tipo_valor:null;
            ($request->formato!=null)?$new["formato"]=$request->formato:null;
            ($request->id_padre!=null)?$new["id_padre"]=$request->id_padre:null;
            if ($request->id != 0) {
                $old = ListasModel::where('id', $request->id)->first();
                ListasModel::where('id', $request->id)->update($new);
                $this->Auditoria($sesion->id, "UPDATE", "ListasModel", $old, $new);
            } else{
                $new["tipo"] = $request->tipo;
                $insert = ListasModel::create($new);
                $this->Auditoria($sesion->id, "INSERT", "ListasModel", null, $insert);
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    public function ActivarItemLista(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $old = ListasModel::where('id', $request->id)->first();
            $new = array('activo' => $request->activar);
            ListasModel::where('id', $request->id)->update($new);
            $this->Auditoria($sesion->id, "UPDATE", "ListasModel", $old, $new);
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

    public function TemasPorTipo(Request $request){
        try {
            $datos = ListasModel::where('tipo', $request->tipo)->get();
            return response()->json($datos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getMessage());
        }
    }

}
