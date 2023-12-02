<?php

namespace App\Http\Controllers;

use App\Imports\TemasImport;
use App\Models\AccionesModel;
use App\Models\ActasModel;
use App\Models\DeclaracionesModel;
use App\Models\DocumentosModel;
use App\Models\FestivosModel;
use App\Models\FirmasModel;
use App\Models\ListasModel;
use App\Models\PerfilesModel;
use App\Models\PermisosModel;
use App\Models\PlanesGestionModel;
use App\Models\PlanesTrabajoModel;
use App\Models\RechazosPtModel;
use App\Models\RolesModel;
use App\Models\RolSubMenuModel;
use App\Models\TemasPModel;
use App\Models\UsuarioNotificacionModel;
use App\Models\UsuariosModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class BackendController extends Controller
{
    public function prueba(Request $request){
        $sesion = (object)$request->sesion;
        $temas = TemasPModel::where('id_acta', 1)->where('activo', true)->where('eliminado', false)->get();
        $idsTemas = array();
        foreach($temas as $item){
            array_push($idsTemas, $item->id);
        }
        $idsAcciones = array();
        $accTP = AccionesModel::where('estado', '<', 8)->whereNull('id_temas')->whereIn('id_temap', $idsTemas)->get();
        $accTS = AccionesModel::where('estado', '<', 8)->whereIn('id_temas', $idsTemas)->get();
        foreach($accTP as $item){
            array_push($idsAcciones, $item->id);
        }
        foreach($accTS as $item){
            array_push($idsAcciones, $item->id);
        }
        $vaina = DocumentosModel::whereIn('id_accion', $idsAcciones)->where('n_tipo', 1)->get();
        return $vaina;
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
                $festivos = FestivosModel::get();
                $fechas = array();
                foreach ($festivos as $festivo) {
                    array_push($fechas, Carbon::createFromFormat('Y-m-d H:i:s', $festivo->fecha)->format('d.m.Y'));
                }
                $perfil = '';
                if ($usuario->perfiles[0]->rol->id == 1) {
                    $perfil = $usuario->perfiles[0]->rol->nombre;
                }
                if ($usuario->perfiles[0]->rol->id == 2) {
                    $perfil = $usuario->perfiles[0]->rol->nombre.">>".$usuario->perfiles[0]->tipo_coord;
                }
                if ($usuario->perfiles[0]->rol->id > 2) {
                    $perfil = $usuario->perfiles[0]->rol->nombre.">>".$usuario->perfiles[0]->apdelegada;
                }
                $sesion = array(
                    "id" => $usuario->id,
                    "cedula" => $usuario->cedula,
                    "email" => $usuario->email,
                    "nombre" => $usuario->nombre,
                    "firma" => (count($usuario->firmas) == 0)?null:$usuario->firmas[0]->cnv_firma,
                    "d_sinproc" => $request->delegada,
                    "perfiles" => $usuario->perfiles,
                    "festivos" => $fechas,
                    "trabajo" => (object)array(
                        "id_perfil" => $usuario->perfiles[0]->id,
                        'perfil' => $perfil,
                        "id_rol" => $usuario->perfiles[0]->id_rol,
                        "id_delegada" => $usuario->perfiles[0]->id_delegada,
                        "tipo_delegada" => (isset($usuario->perfiles[0]->delegada))?$usuario->perfiles[0]->delegada->tipo:null,
                        "tipo_coord" => $usuario->perfiles[0]->tipo_coord
                    ),
                    "menu" => $this->MenusPorRol($usuario->perfiles[0]->rol->id)
                );
                //return $sesion;
                Session::put('UsuarioVee', $sesion);
                $permisos = array(
                    'rol' => PermisosModel::select('url', 'accion', 'estados')->where('id_rol', $usuario->perfiles[0]->id_rol)->get(),
                    'usuario' => PermisosModel::select('url', 'accion', 'estados')->where('id_usuario', $usuario->id)->get(),
                );
                Session::put('PermisosVee', $permisos);
                return Redirect::to(route('inicio'));
            }
        }
    }


    /* ====================================================== BASE ====================================================== */
    public function VariablesTrabajo(Request $request){
        try {
            $sesion = (object)Session::get('UsuarioVee');
            $perfil = PerfilesModel::where('id',$request->id)->first();
            $xperfil = '';
            if ($perfil->rol->id == 1) {
                $xperfil = $perfil->rol->nombre;
            }
            if ($perfil->rol->id == 2) {
                $xperfil = $perfil->rol->nombre.">>".$perfil->tipo_coord;
            }
            if ($perfil->rol->id > 2) {
                $xperfil = $perfil->rol->nombre.">>".$perfil->apdelegada;
            }
            $sesion->trabajo->id_perfil = $request->id;
            $sesion->trabajo->perfil = $xperfil;
            $sesion->trabajo->id_rol = $perfil->id_rol;
            $sesion->trabajo->id_delegada = $perfil->id_delegada;
            $sesion->trabajo->tipo_delegada = (isset($perfil->delegada))?$perfil->delegada->tipo:null;
            $sesion->trabajo->tipo_coord = $perfil->tipo_coord;
            $sesion->menu = $this->MenusPorRol($perfil->id_rol);
            Session::put('UsuarioVee', $sesion);
            $permisos = array(
                'rol' => PermisosModel::select('url', 'accion', 'estados')->where('id_rol', $perfil->id_rol)->get(),
                'usuario' => PermisosModel::select('url', 'accion', 'estados')->where('id_usuario', $sesion->id)->get(),
            );
            Session::put('PermisosVee', $permisos);
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function Notificaciones(Request $request){
        $sesion = (object)Session::get('UsuarioVee');
        if ($request->todo) {
            $notificaciones = UsuarioNotificacionModel::where('id_usuario', $sesion->id)
                                                        ->where('eliminado', false)
                                                        ->where('id_perfil', $sesion->trabajo->id_perfil)
                                                        ->orderBy('created_at', 'desc')->get();
            $activas = $notificaciones->where('activo', true)->count();
        } else{
            $notificaciones = UsuarioNotificacionModel::where('id_usuario', $sesion->id)
                                                        ->where('eliminado', false)
                                                        ->where('id_perfil', $sesion->trabajo->id_perfil)
                                                        ->where('activo', true)
                                                        ->orderBy('created_at', 'desc')->get();
            $activas = $notificaciones->where('activo', true)->count();
        }
        return response()->json(array(
            'notificaciones' => $notificaciones,
            'activas' => $activas
        ));
    }

    public function NotificacionesVista(Request $request){
        try {
            UsuarioNotificacionModel::where('id', $request->id)->update(array('activo' => $request->estado));
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    private function MenusPorRol($rol){
        $menus = RolesModel::where('id', $rol)->first()->menus;
        $tempMenu = array();
        foreach ($menus as $item) {
            $tempSubMenu = array();
            if ($item->tipo == "MENU") {
                foreach ($item->submenus as $item2) {
                    $cont = RolSubMenuModel::where('id_rol', $rol)->where('id_submenu', $item2->id)->count();
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
            return $this->MsjRespuesta(false, $ex->getTrace());
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
                PerfilesModel::create(array(
                    'id_usuario' => $request->id,
                    'id_rol' => $request->rol,
                    'id_delegada' => $request->delegada,
                    'tipo_coord' => $request->tipo,
                    'usuario_crea' => $sesion->cedula
                ));
                if($request->id == $sesion->id){
                    $this->ActualizarPerfilesSesion($request->id);
                }
                return $this->MsjRespuesta(true);
            }
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    private function ActualizarPerfilesSesion($id){
        $usuario = UsuariosModel::where('id', $id)->first();
        $sesion = (object)Session::get('UsuarioVee');
        $sesion->perfiles = $usuario->perfiles;
        Session::put('UsuarioVee', $sesion);
    }

    public function EliminarPerfil(Request $request){
        try {
            PerfilesModel::where('id', $request->id)->update(array('activo' => false));
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function ActivarUsuario(Request $request){
        try {
            UsuariosModel::where('id', $request->id)->update(array('activo' => $request->activar));
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
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
            return $this->MsjRespuesta(false, $ex->getTrace());
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
                    FirmasModel::create(array(
                        'id_usuario' => $sesion->id,
                        'inp_firma' => 'inp_'.$date.'.'.$request->file('inputFirma')->extension(),
                        'cnv_firma' => 'cnv_'.$date.'.'.$extension,
                        'escala' => $request->escalaFirma
                    ));
                    $XSesion = (object)Session::get('UsuarioVee');
                    $XSesion->firma = "cnv_".$date.".".$extension;
                    Session::put('UsuarioVee', $XSesion);
                } else{
                    FirmasModel::where('id', $firmas[0]->id)->update(array(
                        'cnv_firma' => 'cnv_'.$date.'.'.$extension,
                        'escala' => $request->escalaFirma)
                    );
                    $XSesion = (object)Session::get('UsuarioVee');
                    $XSesion->firma = "cnv_".$date.".".$extension;
                    Session::put('UsuarioVee', $XSesion);
                }
                DB::commit();
                return $this->MsjRespuesta(true);
            } else{
                return $this->MsjRespuesta(false, "Firma no recibida.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    /* ====================================================== CONFIGURACIONES / LISTAS ====================================================== */
    public function DatosConfigLista(Request $request){
        try {
            $datos = ListasModel::where('tipo', $request->valor)->get();
            return response()->json($datos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function CrearActualizarItemLista(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $new = array();
            ($request->nombre!=null)?$new["nombre"]=$request->nombre:null;
            ($request->valor_texto!=null)?$new["valor_texto"]=$request->valor_texto:null;
            ($request->valor_numero!=null)?$new["valor_numero"]=$request->valor_numero:null;
            ($request->tipo_valor!=null)?$new["tipo_valor"]=$request->tipo_valor:null;
            if ($request->id != 0) {
                ListasModel::where('id', $request->id)->update($new);
            } else{
                $cont = 0;
                if ($request->tipo_valor == 2) {
                    $cont = ListasModel::where('tipo', $request->tipo)->where('valor_numero', $request->valor_numero)->count();
                }
                if ($request->tipo_valor == 3) {
                    $cont = ListasModel::where('tipo', $request->tipo)->where('valor_texto', $request->valor_texto)->count();
                }
                if ($cont == 0) {
                    $new["tipo"] = $request->tipo;
                    ListasModel::create($new);
                } else{
                    DB::rollBack();
                    return $this->MsjRespuesta(false, "El valor para este item ya se encuentra registrado.", 200);
                }
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function ActivarItemLista(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            ListasModel::where('id', $request->id)->update(array('activo' => $request->activar));
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    /* ====================================================== ACTAS PRINCIPALES ====================================================== */

    public function ActasTP(Request $request){
        try {
            $sesion = (object)$request->sesion;
            if($sesion->trabajo->id_rol == 1){
                $datos = ActasModel::where('tipo_acta', 1)->get();
            }
            if($sesion->trabajo->id_rol == 2){
                $tipoDelegada = ($sesion->trabajo->tipo_coord == "PD")?1:4;
                $datos = ActasModel::select('vee2_actas.*')->join('view_vee_delegadas', 'vee2_actas.id_delegada', 'view_vee_delegadas.id')
                ->where('vee2_actas.tipo_acta', 1)->where('view_vee_delegadas.tipo', $tipoDelegada)
                ->orderBy('vee2_actas.id', 'asc')->get();
            }
            if($sesion->trabajo->id_rol > 2){
                $datos = ActasModel::where('tipo_acta', 1)->where('id_delegada', $sesion->trabajo->id_delegada)->get();
            }
            return response()->json($datos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function GuardarActasTP(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            if (isset($request->nombreActa)) {
                $date = Carbon::now()->format('YmdHisu');
                if (isset($request->inputActa)) {
                    $request->file('inputActa')->storeAs('vee2_cargados', 'actatp_'.$date.'.'.$request->file('inputActa')->extension());
                    ActasModel::create(array(
                        'id_delegada' => $sesion->trabajo->id_delegada,
                        'tipo_acta' => 1,
                        'descripcion' => $request->nombreActa,
                        'archivo' => 'actatp_'.$date.'.'.$request->file('inputActa')->extension(),
                        'nombre_archivo' => $request->file('inputActa')->getClientOriginalName()
                    ));
                }
                DB::commit();
                return $this->MsjRespuesta(true);
            } else{
                return $this->MsjRespuesta(false, "Archivo no recibido.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function DescargarArchivo(Request $request){
        if (Storage::exists($request->carpeta.'/'.$request->archivo)) {
            return Storage::download($request->carpeta.'/'.$request->archivo);
        } else{
            return $this->MsjRespuesta(false, "Archivo no existe.", 404);
        }
    }

    public function ActivarActa(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            ActasModel::where('id', $request->id)->update(array('activo' => $request->activar));
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function ReemplazarActa(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $acta = ActasModel::where('id', $request->reemplazo)->first();
            $temas = TemasPModel::where('id_acta', $request->id)->where('activo', true)->where('eliminado', false)->get();
            $idsTemas = array();
            foreach($temas as $item){
                array_push($idsTemas, $item->id);
            }
            $idsAcciones = array();
            $accTP = AccionesModel::where('estado', '<', 8)->whereNull('id_temas')->whereIn('id_temap', $idsTemas)->get();
            $accTS = AccionesModel::where('estado', '<', 8)->whereIn('id_temas', $idsTemas)->get();
            foreach($accTP as $item){
                array_push($idsAcciones, $item->id);
            }
            foreach($accTS as $item){
                array_push($idsAcciones, $item->id);
            }
            DocumentosModel::whereIn('id_accion', $idsAcciones)->where('n_tipo', 1)->update(array(
                'archivo' => $acta->archivo,
                'n_original' => $acta->nombre_archivo
            ));
            TemasPModel::whereIn('id', $idsTemas)->update(array(
                'id_acta' => $request->reemplazo
            ));
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    /* ====================================================== TEMAS PRINCIPALES ====================================================== */
    public function TemasPorTipo(Request $request){
        try {
            $sesion = (object)$request->sesion;
            if($sesion->trabajo->id_rol == 1){
                $datos = TemasPModel::where('nivel', $request->tipo)->where('eliminado', false)->orderBy('nombre')->get();
            }
            if($sesion->trabajo->id_rol == 2){
                $tipoDelegada = ($sesion->trabajo->tipo_coord == "PD")?1:4;
                $datos = TemasPModel::select('vee2_temas.*')->join('view_vee_delegadas', 'vee2_temas.id_delegada', 'view_vee_delegadas.id')
                                ->where('vee2_temas.nivel', $request->tipo)->where('vee2_temas.eliminado', false)->where('view_vee_delegadas.tipo', $tipoDelegada)
                                ->orderBy('vee2_temas.nombre', 'asc')->get();
            }
            if($sesion->trabajo->id_rol > 2){
                $datos = TemasPModel::where('nivel', $request->tipo)->where('eliminado', false)->where('id_delegada', $sesion->trabajo->id_delegada)->orderBy('nombre')->get();
            }
            if ($request->tipo == 2) {
                $temasp = TemasPModel::where('nivel', 1)->where('eliminado', false)->where('activo', true)->where('id_delegada', $sesion->trabajo->id_delegada)->orderBy('nombre')->get();
            }else{
                $temasp = [];
            }
            return response()->json(array('datos' => $datos, 'temasp' => $temasp));
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function CrearActualizarTema(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $new = array();
            ($request->nombre!=null)?$new["nombre"]=$request->nombre:null;
            ($request->id_acta!=null)?$new["id_acta"]=$request->id_acta:null;
            ($request->id_padre!=null)?$new["id_padre"]=$request->id_padre:null;
            if ($request->id != 0) {
                TemasPModel::where('id', $request->id)->update($new);
            } else{
                $new["id_delegada"]=$sesion->trabajo->id_delegada;
                $new["nivel"]=$request->nivel;
                TemasPModel::create($new);
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function ActivarTema(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            TemasPModel::where('id', $request->id)->update(array('activo' => $request->activar));
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function CargaMasivaTemas(Request $request){
        try {
            DB::beginTransaction();
            $sesion = (object)$request->sesion;
            TemasPModel::where('id_delegada', $sesion->trabajo->id_delegada)->update(['activo' => false, 'eliminado'=> true]);
            $temasImport = Excel::toCollection(new TemasImport, request()->file('inputCargaMasiva'))[0];
            $noActas = array();
            $noTemaP = array();
            $index = 1;
            foreach($temasImport as $item){
                $index++;
                $acta = ActasModel::where('descripcion', strtoupper(trim($item['acta'])))->where('id_delegada', $sesion->trabajo->id_delegada)->first();
                if(!is_null($acta)){
                    $tema_p = TemasPModel::where('id_delegada', $sesion->trabajo->id_delegada)->where('nivel', 1)
                                            ->where('nombre', strtoupper(trim($item['tema_principal'])))
                                            ->where('activo', true)->where('eliminado', false)->first();
                    $id_tema_p = 0;
                    if(!is_null($tema_p)){
                        $id_tema_p = $tema_p->id;
                    } else{
                        if(strtoupper(trim($item['tema_principal'])) == ""){
                            array_push($noTemaP, $index);
                        } else{
                            $id_tema_p = TemasPModel::create(array(
                                'id_delegada' => $sesion->trabajo->id_delegada,
                                'nombre' => strtoupper(trim($item['tema_principal'])),
                                'nivel' => 1,
                                'id_acta' => $acta->id
                            ))->id;
                        }
                    }
                    TemasPModel::create(array(
                        'id_delegada' => $sesion->trabajo->id_delegada,
                        'nombre' => (strtoupper(trim($item['tema_secundario']))=="")?"NO APLICA":strtoupper(trim($item['tema_secundario'])),
                        'nivel' => 2,
                        'id_acta' => $acta->id,
                        'id_padre' => $id_tema_p
                    ));
                } else{
                    array_push($noActas, $item['acta']);
                }
            }
            if(count($noActas) > 0 || count($noTemaP) > 0){
                DB::rollBack();
                return response()->json(array(
                    'estado' => false,
                    'actas' => $noActas,
                    'temasp' => $noTemaP
                ));
            } else{
                DB::commit();
                return $this->MsjRespuesta(true);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function DocumentosAccion(Request $request){
        try {
            $documentos = DocumentosModel::where('id_accion', $request->id)->orderBy('n_tipo', 'desc')->get();
            return response()->json(array(
                "estado" => true,
                "tipo" => "success",
                "data" => $documentos
            ), 200);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

}
