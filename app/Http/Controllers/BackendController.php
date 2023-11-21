<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Bogota');

use App\Imports\TemasImport;
use App\Models\ActasModel;
use App\Models\FestivosModel;
use App\Models\FirmasModel;
use App\Models\ListasModel;
use App\Models\PerfilesModel;
use App\Models\PlanesGestionModel;
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

class BackendController extends Controller
{
    public function prueba(Request $request){
        $sesion = (object)$request->sesion;
        $date = Carbon::now()->format('YmdHis');
        $plangestion = PlanesGestionModel::where('id', 1)->first();
        $funcionarios = array();
        foreach ($plangestion->declaraciones as $item) {
            if ($item['firmado']) {
                $item['base64Firma'] = $this->ImagenFirma($item['firma']);
            } else{
                $item['base64Firma'] = "";
            }
            array_push($funcionarios, $item);
        }

        $datos = (object)array(
            'delegado_nombre' => $sesion->nombre,
            'delegado_firma' => $this->ImagenFirma($sesion->firma),
            'delegado_fecha' => Carbon::now()->format('d/m/Y'),
            'delegado_empleo' => 'Empleo',
            'coordinador_nombre' => 'Coordinador',
            'coordinador_firma' => $this->ImagenFirma("Firma"),
            'coordinador_fecha' => 'Fecha',
            'coordinador_empleo' => 'Empleo',
        );

        //return view('pdfs.plangestion', compact('plangestion', 'funcionarios'));
        $pdf_pg = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'isPhpEnabled' => true])->loadView('pdfs.plangestion', compact('plangestion', 'funcionarios', 'datos'));
        $pdf_pg->setPaper("letter", 'portrait');
        $pdf_pg->output();
        $dom_pdf_pg = $pdf_pg->getDomPDF();
        $canvas_pg = $dom_pdf_pg->get_canvas();
        $canvas_pg->page_text(500, 51, "{PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0, 0, 0));
        //return $pdf_pg->stream();
        $archivoPG = 'PG_1_'.$date.'.pdf';
        $pdf_pg->save(storage_path().'/app/vee2_temp/'.$archivoPG);

        $cronograma = $plangestion->cronograma;
        $pdf_cron = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdfs.cronograma', compact('cronograma', 'funcionarios', 'datos'));
        $pdf_cron->setPaper("A3", 'landscape');
        $pdf_cron->output();
        $dom_pdf_cron = $pdf_cron->getDomPDF();
        $canvas_cron = $dom_pdf_cron->get_canvas();
        $canvas_cron->page_text(1080, 62, "{PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0, 0, 0));
        //return $pdf_cron->stream();
        $archivoCRON = 'CRON_1_'.$date.'.pdf';
        $pdf_cron->save(storage_path().'/app/vee2_temp/'.$archivoCRON);


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
                        "id_rol" => $usuario->perfiles[0]->id_rol,
                        "id_delegada" => $usuario->perfiles[0]->id_delegada,
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
            $perfil = PerfilesModel::where('id',$request->id)->first();
            $sesion->trabajo->id_perfil = $request->id;
            $sesion->trabajo->id_rol = $perfil->id_rol;
            $sesion->trabajo->id_delegada = $perfil->id_delegada;
            $sesion->menu = $this->MenusPorRol($perfil->id_rol);
            Session::put('UsuarioVee', $sesion);
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
                $idModelo = PerfilesModel::create($new)->id;
                $this->Auditoria($sesion->id, "INSERT", "PerfilesModel", $idModelo, null, $new);
                return $this->MsjRespuesta(true);
            }
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function EliminarPerfil(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $old = PerfilesModel::where('id', $request->id)->first();
            $new = array('activo' => false);
            PerfilesModel::where('id', $request->id)->update($new);
            $this->Auditoria($sesion->id, "DELETE", "PerfilesModel", $request->id, $old, $new);
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function ActivarUsuario(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $old = UsuariosModel::where('id', $request->id)->first();
            $new = array('activo' => $request->activar);
            UsuariosModel::where('id', $request->id)->update($new);
            $this->Auditoria($sesion->id, "UPDATE", "UsuariosModel", $request->id, $old, $new);
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
                    $idModelo = FirmasModel::create($firma)->id;
                    $this->Auditoria($sesion->id, "INSERT", "FirmasModel", $idModelo, null, $firma);
                    $XSesion = (object)Session::get('UsuarioVee');
                    $XSesion->firma = "cnv_".$date.".".$extension;
                    Session::put('UsuarioVee', $XSesion);
                } else{
                    $old = $firmas[0];
                    $new = array('cnv_firma' => 'cnv_'.$date.'.'.$extension, 'escala' => $request->escalaFirma);
                    FirmasModel::where('id', $firmas[0]->id)->update($new);
                    $this->Auditoria($sesion->id, "UPDATE", "FirmasModel", $firmas[0]->id, $old, $new);
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    /* ====================================================== CONFIGURACIONES / LISTAS ====================================================== */
    public function DatosConfigLista(Request $request){
        try {
            $datos = ListasModel::where('tipo', $request->valor)->get();
            return response()->json($datos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
                $old = ListasModel::where('id', $request->id)->first();
                ListasModel::where('id', $request->id)->update($new);
                $this->Auditoria($sesion->id, "UPDATE", "ListasModel", $request->id, $old, $new);
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
                    $idModelo = ListasModel::create($new)->id;
                    $this->Auditoria($sesion->id, "INSERT", "ListasModel", $idModelo, null, $new);
                } else{
                    DB::rollBack();
                    return $this->MsjRespuesta(false, "El valor para este item ya se encuentra registrado.", 200);
                }
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function ActivarItemLista(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $old = ListasModel::where('id', $request->id)->first();
            $new = array('activo' => $request->activar);
            ListasModel::where('id', $request->id)->update($new);
            $this->Auditoria($sesion->id, "UPDATE", "ListasModel", $request->id, $old, $new);
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    /* ====================================================== ACTAS PRINCIPALES ====================================================== */

    public function ActasTP(Request $request){
        try {
            $sesion = (object)$request->sesion;
            if($sesion->trabajo->id_rol < 3){
                $datos = ActasModel::where('tipo_acta', 1)->get();
            } else{
                $datos = ActasModel::where('tipo_acta', 1)->where('id_delegada', $sesion->trabajo->id_delegada)->get();
            }
            return response()->json($datos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
                    $acta = array(
                        'id_delegada' => $sesion->trabajo->id_delegada,
                        'tipo_acta' => 1,
                        'descripcion' => $request->nombreActa,
                        'archivo' => 'actatp_'.$date.'.'.$request->file('inputActa')->extension(),
                        'nombre_archivo' => $request->file('inputActa')->getClientOriginalName()
                    );
                    $idModelo = ActasModel::create($acta)->id;
                    $this->Auditoria($sesion->id, "INSERT", "ActasModel", $idModelo, null, $acta);
                }
                DB::commit();
                return $this->MsjRespuesta(true);
            } else{
                return $this->MsjRespuesta(false, "Archivo no recibido.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            $old = ActasModel::where('id', $request->id)->first();
            $new = array('activo' => $request->activar);
            ActasModel::where('id', $request->id)->update($new);
            $this->Auditoria($sesion->id, "UPDATE", "ActasModel", $request->id, $old, $new);
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function ReemplazarActa(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            //TODO Reemplazar el número de acta en cada uno de los temas principales.
            //DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    /* ====================================================== TEMAS PRINCIPALES ====================================================== */
    public function TemasPorTipo(Request $request){
        try {
            $sesion = (object)$request->sesion;
            if($sesion->trabajo->id_rol < 3){
                $datos = TemasPModel::where('nivel', $request->tipo)->where('eliminado', false)->get();
            } else{
                $datos = TemasPModel::where('nivel', $request->tipo)->where('eliminado', false)->where('id_delegada', $sesion->trabajo->id_delegada)->get();
            }
            if ($request->tipo == 2) {
                $temasp = TemasPModel::where('nivel', 1)->where('eliminado', false)->where('activo', true)->where('id_delegada', $sesion->trabajo->id_delegada)->get();
            }else{
                $temasp = [];
            }
            return response()->json(array('datos' => $datos, 'temasp' => $temasp));
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
                $old = TemasPModel::where('id', $request->id)->first();
                TemasPModel::where('id', $request->id)->update($new);
                $this->Auditoria($sesion->id, "UPDATE", "TemasPModel", $request->id, $old, $new);
            } else{
                $new["id_delegada"]=$sesion->trabajo->id_delegada;
                $new["nivel"]=$request->nivel;
                $idModelo = TemasPModel::create($new)->id;
                $this->Auditoria($sesion->id, "INSERT", "TemasPModel", $idModelo, null, $new);
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function ActivarTema(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $old = TemasPModel::where('id', $request->id)->first();
            $new = array('activo' => $request->activar);
            TemasPModel::where('id', $request->id)->update($new);
            $this->Auditoria($sesion->id, "UPDATE", "TemasPModel", $request->id, $old, $new);
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function CargaMasivaTemas(Request $request){
        try {
            DB::beginTransaction();
            $sesion = (object)$request->sesion;
            TemasPModel::where('id_delegada', $sesion->trabajo->id_delegada)->where('eliminado', false)->update(['activo' =>false, 'eliminado'=>true]);
            $date = Carbon::now()->format('YmdHisu');
            $ext = $request->file('inputCargaMasiva')->extension();
            $request->file('inputCargaMasiva')->storeAs('vee2_temp', 'temas_'.$date.'.'.$ext);
            Excel::import(new TemasImport($sesion->trabajo->id_delegada), storage_path().'/app/vee2_temp/temas_'.$date.'.'.$ext, null, \Maatwebsite\Excel\Excel::XLSX);
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            /*foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }*/
            return $this->MsjRespuesta(false, $failures);
        }
    }

}
