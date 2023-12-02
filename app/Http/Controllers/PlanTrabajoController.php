<?php

namespace App\Http\Controllers;

use App\Models\AccionEntidadModel;
use App\Models\AccionesModel;
use App\Models\ActasModel;
use App\Models\DeclaracionesModel;
use App\Models\DeclaracionTablaModel;
use App\Models\DelegadaEntidadModel;
use App\Models\DelegadasModel;
use App\Models\DocumentosModel;
use App\Models\PerfilesModel;
use App\Models\PlanesGestionModel;
use App\Models\PlanesTrabajoModel;
use App\Models\PlaTAccionModel;
use App\Models\RechazosPtModel;
use App\Models\TemasPModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PlanTrabajoController extends Controller
{
    public function AccionesPorPeriodo(Request $request){
        try {
            $sesion = (object)$request->sesion;
            if($sesion->trabajo->id_rol == 1){
                $datos = AccionesModel::where('year', $request->periodo)->orderBy('id', 'asc')->get();
            }
            if($sesion->trabajo->id_rol == 2){
                $tipoDelegada = ($sesion->trabajo->tipo_coord == "PD")?1:4;
                $datos = AccionesModel::select('vee2_acciones.*')->join('view_vee_delegadas', 'vee2_acciones.id_delegada', 'view_vee_delegadas.id')
                ->where('vee2_acciones.year', $request->periodo)->where('view_vee_delegadas.tipo', $tipoDelegada)
                ->orderBy('vee2_acciones.id', 'asc')->get();
            }
            if($sesion->trabajo->id_rol > 2){
                $datos = AccionesModel::where('year', $request->periodo)->where('id_delegada', $sesion->trabajo->id_delegada)->orderBy('id', 'asc')->get();
                $counter = 0;
                foreach ($datos as $item) {
                    $datos[$counter]->dec_firmada = "";
                    if ($item->estado > 1) {
                        $temp = DeclaracionesModel::where('id_accion', $item->id)->where('id_usuario', $sesion->id)->where('activo', true)->where('previa', false)->where('firmado', true)->first();
                        if(!is_null($temp)){
                            $datos[$counter]->dec_firmada = $temp->archivo_firmado;
                        }
                    }
                    $counter++;
                }
            }
            return response()->json($datos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function TemasPorTemap(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $temas = TemasPModel::where('activo', true)->where('eliminado', false)->where('id_delegada', $sesion->trabajo->id_delegada)->where('nivel', 2)->where('id_padre', $request->temap)->get();
            if (count($temas) == 0) {
                $acta = TemasPModel::where('id', $request->temap)->first()->modelActa->archivo;
            } else{
                $acta = $temas->first()->modelActa->archivo;
            }
            return response()->json(array('temas' => $temas, 'acta' => $acta));
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function EntidadesPorDelegada(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $delegadaEntidad = DelegadaEntidadModel::where('id_delegada', $sesion->trabajo->id_delegada)->where('activo', true)->get();
            $entidades = array();
            foreach ($delegadaEntidad as $item) {
                if(!is_null($item->entidad)){
                    array_push($entidades, $item->entidad);
                }
            }
            $key_values = array_column($entidades, 'nombre');
            array_multisort($key_values, SORT_ASC, $entidades);
            return response()->json($entidades);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function CrearActualizarAccion(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $new = array();
            $new["id_actuacion"]=$request->id_actuacion;
            $new["id_temap"]=$request->id_temap;
            $new["id_temas"]=$request->id_temas;
            $new["titulo"]=strtoupper($request->titulo);
            $new["objetivo_general"]=strtoupper($request->objetivo_general);
            $new["fecha_plangestion"]=Carbon::createFromFormat('d/m/Y', $request->fecha_plangestion)->format('Y-m-d');
            $new["numero_profesionales"]=$request->numero_profesionales;
            $new["fecha_inicio"]=Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
            $new["fecha_final"]=Carbon::createFromFormat('d/m/Y', $request->fecha_final)->format('Y-m-d');
            $new["id_padre"]=$request->id_padre;
            $tema = TemasPModel::whereId($request->id_temas)->first();
            if ($request->id != 0) {
                $new["estado"]=1;
                $accion = AccionesModel::where('id', $request->id)->first();
                $accion->update($new);
                AccionEntidadModel::where('id_accion', $request->id)->update(['activo' => false]);
                foreach ($request->entidades as $item) {
                    AccionEntidadModel::create(array(
                        'id_accion' => $request->id,
                        'id_entidad' => $item
                    ));
                }
                DocumentosModel::where('id_accion', $request->id)->where('n_tipo', 1)->update(array(
                    'archivo' => $tema->modelActa->archivo,
                    'n_original' => $tema->modelActa->nombre_archivo,
                    'fecha' => Carbon::now()->format('d/m/Y'),
                    'usuario' => $sesion->nombre,
                    'id_usuario' => $sesion->id,
                ));
                PlanesTrabajoModel::where('id', $accion->idPT)->update(array('estado' => 1));
            } else{
                $new["id_delegada"]=$sesion->trabajo->id_delegada;
                $new["year"]=date("Y");
                $idModelo = AccionesModel::create($new)->id;
                foreach ($request->entidades as $item) {
                    AccionEntidadModel::create(array(
                        'id_accion' => $idModelo,
                        'id_entidad' => $item
                    ));
                }
                DeclaracionesModel::create(array(
                    'id_accion' => $idModelo,
                    'id_usuario' => $sesion->id
                ));
                DocumentosModel::create(array(
                    'id_accion' => $idModelo,
                    'n_tipo' => 1,
                    't_tipo' => 'ACTA TEMA PRINCIPAL',
                    'carpeta' => 'vee2_cargados',
                    'archivo' => $tema->modelActa->archivo,
                    'n_original' => $tema->modelActa->nombre_archivo,
                    'fecha' => Carbon::now()->format('d/m/Y'),
                    'usuario' => $sesion->nombre,
                    'id_usuario' => $sesion->id,
                ));
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function AccionPorId(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $accion = AccionesModel::where('id', $request->id)->first();
            return response()->json($accion);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function GuardarDeclaracion(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $declaracion = DeclaracionesModel::where('id_accion', $request->id_accion)->where('id_usuario', $sesion->id)->where('activo', true)->first();
            $update = [
                'previa' => $request->previa,
                'lugar_expedicion' => $request->lugar_expedicion,
                'funcionario' => $request->funcionario,
                'id_profesion' => $request->id_profesion,
                'cargo' => $request->cargo,
                'contrato' => $request->contrato,
                'conflicto' => $request->conflicto,
                'explicacion' => $request->explicacion,
            ];
            if(!$request->previa){
                $update = [
                    'previa' => $request->previa,
                    'firmado' => true,
                    'lugar_expedicion' => $request->lugar_expedicion,
                    'funcionario' => $request->funcionario,
                    'id_profesion' => $request->id_profesion,
                    'cargo' => $request->cargo,
                    'contrato' => $request->contrato,
                    'conflicto' => $request->conflicto,
                    'explicacion' => $request->explicacion,
                    'archivo_firmado' => "TODO guardar archivo",
                ];
            }
            $declaracion->update($update);
            DeclaracionTablaModel::where('id_declaracion', $declaracion->id)->delete();
            $insert = $this->InsertarTabla(1, $declaracion->id, $request->arrTabla1);
            DeclaracionTablaModel::insert($insert);
            $insert = $this->InsertarTabla(2, $declaracion->id, $request->arrTabla2);
            DeclaracionTablaModel::insert($insert);;
            if(!$request->previa){
                AccionesModel::where('id', $request->id_accion)->update([
                    'estado' => 2
                ]);
                $archivo = $this->PDFDeclaracion($declaracion->id, true);
                DeclaracionesModel::where('id', $declaracion->id)->update([
                    'archivo_firmado' => $archivo
                ]);
                $documento = DocumentosModel::where('id_accion', $request->id_accion)->where('n_tipo', 2)->first();
                if($documento != null){
                    $documento->update(array(
                        'archivo' => $archivo,
                        'n_original' => $archivo,
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                } else{
                    DocumentosModel::create(array(
                        'id_accion' => $request->id_accion,
                        'n_tipo' => ($declaracion->tipo_usuario == 'DELEGADO')?2:5,
                        't_tipo' => 'DECLARACIÓN '.$declaracion->tipo_usuario,
                        'carpeta' => 'vee2_generados',
                        'archivo' => $archivo,
                        'n_original' => $archivo,
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                }
                if($declaracion->tipo_usuario == 'FUNCIONARIO'){
                    if ($this->TodasFirmadas($request->id_accion)) {
                        PlanesGestionModel::where('id_accion', $request->id_accion)->update([
                            'estado' => 3
                        ]);
                        $noti = (object)array(
                            'para' => 'Delegado',
                            'tipo' => 'primary',
                            'texto' => 'Declaraciones del equipo firmadas',
                            'url' => '/plagesg/listar'
                        );
                        $this->Notificar($noti);
                    }
                }
                DB::commit();
                return $this->MsjRespuesta(true);
            } else{
                DB::commit();
                return response()->json(array('id' => $declaracion->id));
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    private function InsertarTabla($tipo, $idDecla, $arrTabla){
        $insert = array();
        for ($i=0; $i < count($arrTabla); $i++) {
            $temp = array(
                'id_declaracion' => $idDecla,
                'tipo' => $tipo,
                'nombres' => $arrTabla[$i][0],
                'cargo' => $arrTabla[$i][1],
                'area' => $arrTabla[$i][2],
                'tipo_relacion' => $arrTabla[$i][3],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            );
            array_push($insert, $temp);
        }
        return $insert;
    }

    public function PreviaDeclaracion(Request $request){
        try {
            $pdf = $this->PDFDeclaracion($request->id, false);
            return $pdf->stream();
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    private function PDFDeclaracion($idDeclaracion, $save){
        $sesion = (object)Session::get('UsuarioVee');
        $declaracion = DeclaracionesModel::where('id', $idDeclaracion)->first();
        $accion = $declaracion->accion;

        $logoT = pathinfo(public_path('img/logo_per.png'), PATHINFO_EXTENSION);
        $logoD = file_get_contents(public_path('img/logo_per.png'));

        $pieT = pathinfo(public_path('img/pie_pag.png'), PATHINFO_EXTENSION);
        $pieD = file_get_contents(public_path('img/pie_pag.png'));

        $perfil = null;
        foreach ($sesion->perfiles as $item) {
            if ($item->id == $sesion->trabajo->id_perfil) {
                $perfil = $item;
            }
        }

        $funcionario = (object)array(
            "imgFirma" => $this->ImagenFirma($sesion->firma),
            "nombre" => $sesion->nombre,
            "cedula" => $sesion->cedula,
            "delegada" => $perfil->delegada->nombre,
            "logo" => 'data:image/'.$logoT.';base64,'.base64_encode($logoD),
            "pie" => 'data:image/'.$pieT.';base64,'.base64_encode($pieD),
        );
        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdfs.imparcialidad_conflicto', compact('declaracion', 'accion', 'funcionario'));
        $pdf->setPaper("letter", 'portrait');
        if($save){
            $date = Carbon::now()->format('YmdHis');
            $nombreArchivo = 'DEC_AC_'.$accion->id.'_CC_'.$sesion->cedula.'_'.$date.'.pdf';
            $pdf->save(storage_path().'/app/vee2_generados/'.$nombreArchivo);
            return $nombreArchivo;
        } else{
            return $pdf;
        }
    }

    public function PlanestabajoPorPeriodo(Request $request){
        try {
            $sesion = (object)$request->sesion;
            if($sesion->trabajo->id_rol == 1){
                $datos = PlanesTrabajoModel::where('year', $request->periodo)->orderBy('id', 'asc')->orderBy('id_delegada', 'asc')->get();
            }
            if($sesion->trabajo->id_rol == 2){
                $tipoDelegada = ($sesion->trabajo->tipo_coord == "PD")?1:4;
                $datos = PlanesTrabajoModel::select('vee2_planes_trabajo.*')->join('view_vee_delegadas', 'vee2_planes_trabajo.id_delegada', 'view_vee_delegadas.id')
                ->where('vee2_planes_trabajo.year', $request->periodo)->where('view_vee_delegadas.tipo', $tipoDelegada)
                ->orderBy('vee2_planes_trabajo.id', 'asc')->orderBy('vee2_planes_trabajo.id_delegada', 'asc')->get();
            }
            if($sesion->trabajo->id_rol > 2){
                $datos = PlanesTrabajoModel::where('year', $request->periodo)->where('id_delegada', $sesion->trabajo->id_delegada)->orderBy('version', 'asc')->get();
            }
            return response()->json($datos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function AccionesPorPeriodoPT(Request $request){
        try {
            $sesion = (object)$request->sesion;
            if($sesion->trabajo->id_rol < 3){
                $datos = AccionesModel::where('year', $request->periodo)->orderBy('id', 'asc')->get();
            } else{
                $datos = AccionesModel::where('year', $request->periodo)->where('id_delegada', $sesion->trabajo->id_delegada)->orderBy('id', 'asc')->get();
            }
            $accionespt = [];
            if ($request->id != 0) {
                $accionespt = PlaTAccionModel::where('id_plantrabajo', $request->id)->get();
            }
            $arr_select = [];
            foreach ($datos as $item) {
                $item->checked = false;
                foreach ($accionespt as $acc) {
                    if($item->id == $acc->id_accion){
                        $item->checked = true;
                        array_push($arr_select, intval($acc->id_accion));
                    }
                }
            }
            return response()->json(array(
                'datos' => $datos,
                'chks' => $arr_select
            ));
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function CrearActualizarPlanTrabajo(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $idModelo = $request->id;
            if ($request->id != 0) {
                $PTAcc = PlaTAccionModel::where('id_plantrabajo', $request->id)->get();
                foreach ($PTAcc as $item) {
                    AccionesModel::where('id', $item->id_accion)->update(['estado' => 2]);
                }
                PlaTAccionModel::where('id_plantrabajo', $request->id)->delete();
            } else{
                PlanesTrabajoModel::where('year', date("Y"))->where('id_delegada', $sesion->trabajo->id_delegada)->update(array('vigente' => false));
                $idModelo = PlanesTrabajoModel::create(array(
                    'year' => date("Y"),
                    'id_delegada' => $sesion->trabajo->id_delegada,
                    'version' => $request->version
                ))->id;
            }
            foreach ($request->arrAcciones as $item) {
                PlaTAccionModel::create([
                    'id_plantrabajo' => $idModelo,
                    'id_accion' => $item
                ]);
                AccionesModel::where('id', $item)->update(['estado' => 3]);
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function FirmarPlanTrabajoDelegado(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $date = Carbon::now()->format('YmdHisu');
            if (isset($request->inputActa)) {
                $request->file('inputActa')->storeAs('vee2_cargados', 'actapt_'.$request->id.'_'.$date.'.'.$request->file('inputActa')->extension());
                $archivo_firmado = $this->PDFPlanTrabajo($request->id, true);
                $plantrabajo = PlanesTrabajoModel::where('id', $request->id)->first();
                $estadoActual = $plantrabajo->estado;
                $plantrabajo->update(array(
                    'estado' => 2,
                    'archivo_firmado' => $archivo_firmado,
                    'archivo_acta' => 'actapt_'.$request->id.'_'.$date.'.'.$request->file('inputActa')->extension(),
                    'original_acta' => $request->file('inputActa')->getClientOriginalName(),
                    'id_delegado' => $sesion->id,
                    'fecha_delegado' => Carbon::now()->format('d/m/Y')
                ));
                if ($estadoActual == 1) {
                    $noti = (object)array(
                        'para' => 'Coordinador',
                        'tipo' => 'primary',
                        'texto' => 'Solicitud de aprobación plan de trabajo',
                        'url' => '/planest/listar'
                    );
                    $this->Notificar($noti);
                }
                foreach ($plantrabajo->acciones as $accion) {
                    $accion->update(array('estado' => 14));
                    $documento1 = DocumentosModel::where('id_accion', $accion->id)->where('n_tipo', 3)->first();
                    if($documento1 != null){
                        $documento1->update(array(
                            'archivo' => 'actapt_'.$request->id.'_'.$date.'.'.$request->file('inputActa')->extension(),
                            'n_original' => $request->file('inputActa')->getClientOriginalName(),
                            'fecha' => Carbon::now()->format('d/m/Y'),
                            'usuario' => $sesion->nombre,
                            'id_usuario' => $sesion->id,
                        ));
                    } else{
                        DocumentosModel::create(array(
                            'id_accion' => $accion->id,
                            'n_tipo' => 3,
                            't_tipo' => 'ACTA APROBACIÓN PLAN DE TRABAJO',
                            'carpeta' => 'vee2_cargados',
                            'archivo' => 'actapt_'.$request->id.'_'.$date.'.'.$request->file('inputActa')->extension(),
                            'n_original' => $request->file('inputActa')->getClientOriginalName(),
                            'fecha' => Carbon::now()->format('d/m/Y'),
                            'usuario' => $sesion->nombre,
                            'id_usuario' => $sesion->id,
                        ));
                    }
                }
                DB::commit();
                return $this->MsjRespuesta(true);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function FirmarPlanTrabajoCoordinador(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $archivo_firmado = $this->PDFPlanTrabajo($request->id, true);
            $plantrabajo = PlanesTrabajoModel::where('id', $request->id)->first();
            $plantrabajo->update(array(
                'estado' => 5,
                'archivo_firmado' => $archivo_firmado,
                'id_coordinador' => $sesion->id,
                'fecha_coordinador' => Carbon::now()->format('d/m/Y')
            ));
            $noti = (object)array(
                'para' => 'Delegado',
                'id_delegada' => $plantrabajo->id_delegada,
                'tipo' => 'success',
                'texto' => 'Plan de trabajo aprobado',
                'url' => '/planest/listar'
            );
            $this->Notificar($noti);
            foreach ($plantrabajo->acciones as $accion) {
                $documento2 = DocumentosModel::where('id_accion', $accion->id)->where('n_tipo', 4)->first();
                if($documento2 != null){
                    $documento2->update(array(
                        'archivo' => $archivo_firmado,
                        'n_original' => $archivo_firmado,
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                } else{
                    DocumentosModel::create(array(
                        'id_accion' => $accion->id,
                        'n_tipo' => 4,
                        't_tipo' => 'PLAN DE TRABAJO FIRMADO',
                        'carpeta' => 'vee2_generados',
                        'archivo' => $archivo_firmado,
                        'n_original' => $archivo_firmado,
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                }
                $accion->update(array('estado' => 5));
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function PreviaPlanTrabajo(Request $request){
        try {
            $pdf = $this->PDFPlanTrabajo($request->id, false);
            return $pdf->stream();
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    private function PDFPlanTrabajo($idPT, $save){
        $sesion = (object)Session::get('UsuarioVee');
        $plantrabajo = PlanesTrabajoModel::where('id', $idPT)->first();

        $PTAcciones = PlaTAccionModel::where('id_plantrabajo', $idPT)->get();

        $checkedT = pathinfo(public_path('img/checked.png'), PATHINFO_EXTENSION);
        $checkedD = file_get_contents(public_path('img/checked.png'));

        $uncheckedT = pathinfo(public_path('img/unchecked.png'), PATHINFO_EXTENSION);
        $uncheckedD = file_get_contents(public_path('img/unchecked.png'));

        if($plantrabajo->estado == 1){
            $datos = (object)array(
                'delegado_nombre' => $sesion->nombre,
                'delegado_firma' => $this->ImagenFirma($sesion->firma),
                'delegado_fecha' => Carbon::now()->format('d/m/Y'),
                'coordinador_nombre' => 'Coordinador',
                'coordinador_firma' => $this->ImagenFirma("Firma"),
                'coordinador_fecha' => 'Fecha',
                "checked" => 'data:image/'.$checkedT.';base64,'.base64_encode($checkedD),
                "unchecked" => 'data:image/'.$uncheckedT.';base64,'.base64_encode($uncheckedD),
            );
        } else{
            $datos = (object)array(
                'delegado_nombre' => $plantrabajo->delegado->nombre,
                'delegado_firma' => $this->ImagenFirma($plantrabajo->delegado->firmas[0]->cnv_firma),
                'delegado_fecha' => $plantrabajo->fecha_delegado,
                'coordinador_nombre' => $sesion->nombre,
                'coordinador_firma' => $this->ImagenFirma($sesion->firma),
                'coordinador_fecha' => Carbon::now()->format('d/m/Y'),
                "checked" => 'data:image/'.$checkedT.';base64,'.base64_encode($checkedD),
                "unchecked" => 'data:image/'.$uncheckedT.';base64,'.base64_encode($uncheckedD),
            );
        }

        //return view('pdfs.plantrabajo', compact('PTAcciones', 'datos'));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdfs.plantrabajo', compact('PTAcciones', 'datos'));
        $pdf->setPaper("c2", 'landscape');
        if($save){
            $date = Carbon::now()->format('YmdHis');
            $nombreArchivo = 'PLAN_T_'.$PTAcciones[0]->plantrabajo->year.'-'.$PTAcciones[0]->plantrabajo->version.'_'.$date.'.pdf';
            $pdf->save(storage_path().'/app/vee2_generados/'.$nombreArchivo);
            return $nombreArchivo;
        } else{
            return $pdf;
        }
    }

    private function TodasFirmadas($id_accion){
        $sinConflicto = DeclaracionesModel::where('id_accion', $id_accion)->where('activo', true)->where('previa', false)->where('firmado', true)->where('conflicto', false)->count();
        $total = DeclaracionesModel::where('id_accion', $id_accion)->where('activo', true)->count();
        if($total == $sinConflicto){
            return true;
        } else{
            return false;
        }
    }

    public function PlanTrabajoVigente(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            PlanesTrabajoModel::where('year', date("Y"))->where('id_delegada', $sesion->trabajo->id_delegada)->update(array('vigente' => false));
            PlanesTrabajoModel::where('id', $request->id)->update(array(
                'vigente' => true,
                'estado' => 1
            ));
            $ptAccions = PlaTAccionModel::where('id_plantrabajo', $request->id)->get();
            foreach($ptAccions as $item){
                $item->accion->update(array('estado' => 3));
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function PlanTrabajoRechazo(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $plantrabajo = PlanesTrabajoModel::where('id', $request->id)->first();
            $plantrabajo->update(array('estado' => 3));
            $ptAccions = PlaTAccionModel::where('id_plantrabajo', $request->id)->get();
            foreach($ptAccions as $item){
                $item->accion->update(array('estado' => 4));
            }
            RechazosPtModel::create(array(
                'id_plant' => $request->id,
                'fecha_rechazo' => Carbon::now(),
                'texto_rechazo' => $request->motivo,
                'nombre_rechazo' => '<span class="de_chat">'.$sesion->nombre.'</span><br>'.$sesion->trabajo->perfil
            ));
            $noti = (object)array(
                'para' => 'Delegado',
                'id_delegada' => $plantrabajo->id_delegada,
                'tipo' => 'danger',
                'texto' => 'El Plan de trabajo no fue aprobado',
                'url' => '/planest/listar'
            );
            $this->Notificar($noti);
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function RechazosPlanTrabajo(Request $request){
        try {
            $rechazos = RechazosPtModel::where('id_plant', $request->id)->orderBy('activo')->orderBy('id', 'desc')->get();
            return response()->json($rechazos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function PlanTrabajoRespuesta(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $plantrabajo = PlanesTrabajoModel::where('id', $request->id)->first();
            $plantrabajo->update(array('estado' => 4));
            $ptAccions = PlaTAccionModel::where('id_plantrabajo', $request->id)->get();
            foreach($ptAccions as $item){
                $item->accion->update(array('estado' => 14));
            }
            $rechazo = RechazosPtModel::where('id_plant', $request->id)->where('activo', true)->first();
            $rechazo->update(array(
                'activo' => false,
                'fecha_respuesta' => Carbon::now(),
                'texto_respuesta' => $request->respuesta,
                'nombre_respuesta' => '<span class="de_chat">'.$sesion->nombre.'</span><br>'.$sesion->trabajo->perfil
            ));
            $noti = (object)array(
                'para' => 'Coordinador',
                'tipo' => 'primary',
                'texto' => 'Ajustes realizados al plan de trabajo',
                'url' => '/planest/listar'
            );
            $this->Notificar($noti);
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function PlanTrabajoPuedeFirmarDelegado(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $plantrabajo = PlanesTrabajoModel::where('id', $request->id)->first();
            $arrSinFirma = array();
            foreach ($plantrabajo->acciones as $accion) {
                if($accion->estado == 1){
                    array_push($arrSinFirma, $accion->numero);
                }
            }
            $estado = true;
            if(count($arrSinFirma)>0){
                $estado = false;
            }
            return response()->json(array(
                'estado' => $estado,
                'sinFirma' => $arrSinFirma
            ));
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

}
