<?php

namespace App\Http\Controllers;

use App\Models\AccionesModel;
use App\Models\CronogramaModel;
use App\Models\CronogramaSemanaModel;
use App\Models\DeclaracionesModel;
use App\Models\DocumentosModel;
use App\Models\PerfilesModel;
use App\Models\PlanesGestionModel;
use App\Models\PlanesTrabajoModel;
use App\Models\PlanGTextoModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;


class PlanGestionController extends Controller
{
    public function Planesgestion(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $diaUno = Carbon::createFromFormat('Y-m-d H:i:s', (intval(date("Y"))-1).'-01-01 00:00:00', 'America/Bogota');
            if($sesion->trabajo->id_rol == 1){
                $datos = PlanesGestionModel::where('created_at', '>', $diaUno)->where('activo', true)->orderBy('id', 'asc')->get();
            }
            if($sesion->trabajo->id_rol == 2){
                $tipoDelegada = ($sesion->trabajo->tipo_coord == "PD")?1:4;
                $datos = PlanesGestionModel::select('vee2_planes_gestion.*')->join('view_vee_delegadas', 'vee2_planes_gestion.id_delegada', 'view_vee_delegadas.id')
                ->where('vee2_planes_gestion.created_at', '>', $diaUno)->where('vee2_planes_gestion.activo', true)->where('view_vee_delegadas.tipo', $tipoDelegada)
                ->orderBy('vee2_planes_gestion.id', 'asc')->get();
            }
            if($sesion->trabajo->id_rol == 3 || $sesion->trabajo->id_rol == 4){
                $datos = PlanesGestionModel::where('created_at', '>', $diaUno)->where('activo', true)->where('id_delegada', $sesion->trabajo->id_delegada)->orderBy('id', 'asc')->get();
            }
            if($sesion->trabajo->id_rol == 5){
                $datos = PlanesGestionModel::select('vee2_planes_gestion.*', 'vee2_declaraciones.archivo_firmado as dec_firmada')->join('vee2_declaraciones', 'vee2_planes_gestion.id_accion', 'vee2_declaraciones.id_accion')
                            ->where('vee2_planes_gestion.created_at', '>', $diaUno)->where('vee2_planes_gestion.activo', true)->where('vee2_declaraciones.tipo_usuario', 'FUNCIONARIO')
                            ->where('vee2_declaraciones.id_usuario', $sesion->id)->where('vee2_declaraciones.activo', true)->orderBy('vee2_planes_gestion.id', 'asc')->get();
            }
            return response()->json($datos);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function UsuariosPlanesgestion(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $usuariosIn = [];
            $usuariosOut = [];
            $perfiles = PerfilesModel::where('activo', true)->where('id_rol', 5)->where('id_delegada', $sesion->trabajo->id_delegada)
                                    ->where('id_usuario', '!=', $sesion->id)->get();
            $equipo = [];
            if ($request->id != 0) {
                $equipo = DeclaracionesModel::where('id_accion', $request->id)->where('activo', true)->get();
            }
            $arrUsuarios = [];
            $arrNombres = [];
            foreach ($perfiles as $item) {
                $checked = false;
                foreach ($equipo as $eq) {
                    if($item->usuario->id == $eq->id_usuario){
                        $checked = true;
                        array_push($arrUsuarios, intval($eq->id_usuario));
                        $arrNombres["N".$eq->id_usuario] = $item->usuario->nombre;
                    }
                }
                $usuario = array(
                    'id' => $item->usuario->id,
                    'cedula' => $item->usuario->cedula,
                    'nombre' => $item->usuario->nombre,
                    'delegada' => $item->apdelegada,
                    'checked' => $checked
                );
                if ($item->id_delegada == $sesion->trabajo->id_delegada ) {
                    array_push($usuariosIn, $usuario);
                } else{
                    //array_push($usuariosOut, $usuario);
                }
            }
            $Tacciones = AccionesModel::where('id_delegada', $sesion->trabajo->id_delegada)->where('estado', 5)->where('activo', true)->get();
            $acciones = [];
            foreach ($Tacciones as $item) {
                array_push($acciones, array(
                    'id' => $item->id,
                    'fecha_final' => $this->DescontarDiasHabiles($item->fecha_final, 10),
                    'nombre' => '['.$item->numero.'] '. Str::limit($item->titulo, 70, '...')
                ));
            }
            $titulo = "";
            if($request->id != 0){
                $accion = AccionesModel::where('id', $request->id)->first();
                $titulo = '['.$accion->numero.'] '. Str::limit($accion->titulo, 70, '...');
            }
            return response()->json(array(
                'usuariosIn' => $usuariosIn,
                'usuariosOut' => $usuariosOut,
                'acciones' => $acciones,
                'arrUsuarios' => $arrUsuarios,
                'arrNombres' => $arrNombres,
                'titulo' => $titulo
            ));
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function GuardarEquipoPlanesgestion(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            if ($request->id != 0) {
                $declaraciones = DeclaracionesModel::where('id_accion', $request->id)->where('activo', true)->get();
                $olds = array();
                foreach ($declaraciones as $item) {
                    array_push($olds, $item->id_usuario);
                    if(!in_array($item->id_usuario, $request->arrUsuarios)){
                        DeclaracionesModel::where('id_accion', $request->id)->where('id_usuario', $item->id_usuario)->update(array('activo' => false));
                    }
                }
                foreach ($request->arrUsuarios as $item) {
                    if(!in_array($item, $olds)){
                        DeclaracionesModel::create([
                            'id_accion' => $request->id,
                            'id_usuario' => $item,
                            'tipo_usuario' => 'FUNCIONARIO'
                        ]);
                        $noti = (object)array(
                            'para' => 'Funcionarios',
                            'funcionarios' => [$item],
                            'id_delegada' => $sesion->trabajo->id_delegada,
                            'tipo' => 'primary',
                            'texto' => 'Asignado(a) a equipo de trabajo',
                            'url' => '/plagesg/listar'
                        );
                        $this->Notificar($noti);
                    }
                }
                if ($this->TodasFirmadas($request->id)) {
                    $plang = PlanesGestionModel::where('id_accion', $request->id)->first();
                    if($plang->estado < 3){
                        $plang->update(array('estado' => 3));
                    }
                    $noti = (object)array(
                        'para' => 'Delegado',
                        'tipo' => 'primary',
                        'texto' => 'Declaraciones del equipo firmadas',
                        'url' => '/plagesg/listar'
                    );
                    $this->Notificar($noti);
                } else{
                    $plang = PlanesGestionModel::where('id_accion', $request->id)->first();
                    if($plang->estado > 1){
                        $plang->update(array('estado' => 1));
                    }
                }
            } else{
                AccionesModel::where('id', $request->accion)->update(array('estado' => 6));
                PlanesGestionModel::create([
                    'id_accion' => $request->accion,
                    'id_delegada' => $sesion->trabajo->id_delegada,
                    'fecha_informe' => Carbon::createFromFormat('d/m/Y', $request->fecha_informe)->format('Y-m-d')
                ]);
                foreach ($request->arrUsuarios as $item) {
                    DeclaracionesModel::create([
                        'id_accion' => $request->accion,
                        'id_usuario' => $item,
                        'tipo_usuario' => 'FUNCIONARIO'
                    ]);
                }
                $noti = (object)array(
                    'para' => 'Funcionarios',
                    'funcionarios' => $request->arrUsuarios,
                    'id_delegada' => $sesion->trabajo->id_delegada,
                    'tipo' => 'primary',
                    'texto' => 'Asignado(a) a equipo de trabajo',
                    'url' => '/plagesg/listar'
                );
                $this->Notificar($noti);
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function GuardarPasoPlanesgestion(Request $request){
        DB::beginTransaction();
        try {
            $plang = PlanGTextoModel::where('id_accion', $request->id)->where('tipo', $request->tipo)->first();
            if(!is_null($plang)){
                $plang->update([
                    'texto' => $request->texto
                ]);
            }else{
                PlanGTextoModel::create([
                    'id_accion' => $request->id,
                    'tipo' => $request->tipo,
                    'texto' => $request->texto
                ]);
            }
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function ObtenerPasoPlanesgestion(Request $request){
        try {
            $texto = PlanGTextoModel::where('id_accion', $request->id)->where('tipo', $request->tipo)->first();
            return response()->json($texto);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function GuardarCronograma(Request $request){
        DB::beginTransaction();
        try {
            foreach ($request->cronograma as $item) {
                $id_cron = CronogramaModel::create(array(
                    'id_accion' => $request->id,
                    'id_etapa' => $item['etapa'],
                    'actividad' => $item['actividad']
                ))->id;
                $insert = array();
                foreach ($item['semanas'] as $sem) {
                    $semana = array(
                        'id_cronograma' => $id_cron,
                        'semana' => $sem,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    );
                    array_push($insert, $semana);
                }
                CronogramaSemanaModel::insert($insert);
            }
            PlanesGestionModel::where('id_accion', $request->id)->update(['estado' => 2]);
            DB::commit();
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function ObtenerCronograma(Request $request){
        try {
            $temp = CronogramaModel::where('id_accion', $request->id)->get();
            $cronograma = array();
            foreach ($temp as $item) {
                array_push($cronograma, array(
                    'actividad' => $item->actividad,
                    'etapa' => intval($item->id_etapa),
                    'semanas' => $item->semanas
                ));
            }
            return response()->json($cronograma);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function PreviaPlanGestion(Request $request){
        try {
            $pdf = $this->PDFPlanGestion($request->id, false);
            return $pdf->stream();
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    private function PDFPlanGestion($idAcc, $save){
        $sesion = (object)Session::get('UsuarioVee');
        $date = Carbon::now()->format('YmdHis');
        $plangestion = PlanesGestionModel::where('id_accion', $idAcc)->first();
        $funcionarios = array();
        foreach ($plangestion->declaraciones as $item) {
            if (!$item['conflicto'] && $item['firmado']) {
                $item['base64Firma'] = $this->ImagenFirma($item['firma']);
            } else{
                $item['base64Firma'] = "";
            }
            array_push($funcionarios, $item);
        }

        if ($plangestion->estado == 2 || $plangestion->estado == 3) {
            $datos = (object)array(
                'delegado_nombre' => 'Delegado',
                'delegado_firma' => $this->ImagenFirma("Firma"),
                'delegado_fecha' => 'Fecha',
                'delegado_empleo' => 'Empleo',
                'coordinador_nombre' => 'Coordinador',
                'coordinador_firma' => $this->ImagenFirma("Firma"),
                'coordinador_fecha' => 'Fecha',
                'coordinador_empleo' => 'Empleo',
            );
            if($sesion->trabajo->id_rol == 3){
                $datos->delegado_nombre = $sesion->nombre;
                $datos->delegado_firma = $this->ImagenFirma($sesion->firma);
                $datos->delegado_fecha = Carbon::now()->format('d/m/Y');
            }
        }

        if ($plangestion->estado > 3) {
            $datos = (object)array(
                'delegado_nombre' => $plangestion->delegado->nombre,
                'delegado_firma' => $this->ImagenFirma($plangestion->delegado->firmas[0]->cnv_firma),
                'delegado_fecha' => $plangestion->fecha_delegado,
                'delegado_empleo' => 'Empleo',
                'coordinador_nombre' => 'Coordinador',
                'coordinador_firma' => $this->ImagenFirma("Firma"),
                'coordinador_fecha' => 'Fecha',
                'coordinador_empleo' => 'Empleo',
            );
            if($sesion->trabajo->id_rol == 2){
                $datos->coordinador_nombre = $sesion->nombre;
                $datos->coordinador_firma = $this->ImagenFirma($sesion->firma);
                $datos->coordinador_fecha = Carbon::now()->format('d/m/Y');
            }
        }

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

        $oMerger = PDFMerger::init();
        $oMerger->addPDF(storage_path().'/app/vee2_temp/'.$archivoPG, 'all');
        $oMerger->addPDF(storage_path().'/app/vee2_temp/'.$archivoCRON, 'all');
        $oMerger->merge();
        if($save){
            $nombreArchivo = 'plan_g_'.$idAcc.'_'.$date.'.pdf';
            $oMerger->save(storage_path().'/app/vee2_generados/'.$nombreArchivo);
            return $nombreArchivo;
        } else{
            return $oMerger;
        }
    }

    public function FirmarPlanGestionDelegado(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $date = Carbon::now()->format('YmdHisu');
            if (isset($request->inputActaD)) {
                $request->file('inputActaD')->storeAs('vee2_cargados', 'actav_'.$request->id.'_'.$date.'.'.$request->file('inputActaD')->extension());
                $plangestion = PlanesGestionModel::where('id_accion', $request->id)->first();
                $plangestion->update(array(
                    'estado' => 4,
                    'archivo_acta_d' => 'actav_'.$request->id.'_'.$date.'.'.$request->file('inputActaD')->extension(),
                    'original_acta_d' => $request->file('inputActaD')->getClientOriginalName(),
                    'id_delegado' => $sesion->id,
                    'fecha_delegado' => Carbon::now()->format('d/m/Y')
                ));
                $noti = (object)array(
                    'para' => 'Enlace',
                    'tipo' => 'primary',
                    'texto' => 'Solicitud mesa de trabajo enlace',
                    'url' => '/plagesg/listar'
                );
                $this->Notificar($noti);
                $documento1 = DocumentosModel::where('id_accion', $request->id)->where('n_tipo', 6)->first();
                if($documento1 != null){
                    $documento1->update(array(
                        'archivo' => 'actav_'.$request->id.'_'.$date.'.'.$request->file('inputActaD')->extension(),
                        'n_original' => $request->file('inputActaD')->getClientOriginalName(),
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                } else{
                    DocumentosModel::create(array(
                        'id_accion' => $request->id,
                        'n_tipo' => 6,
                        't_tipo' => 'ACTA VIABILIDAD DELEGADO PLAN DE GESTIÓN',
                        'carpeta' => 'vee2_cargados',
                        'archivo' => 'actav_'.$request->id.'_'.$date.'.'.$request->file('inputActaD')->extension(),
                        'n_original' => $request->file('inputActaD')->getClientOriginalName(),
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                }
                DB::commit();
                return $this->MsjRespuesta(true);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function FirmarPlanGestionEnlace(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            $date = Carbon::now()->format('YmdHisu');
            if (isset($request->inputActaE)) {
                $request->file('inputActaE')->storeAs('vee2_cargados', 'actae_'.$request->id.'_'.$date.'.'.$request->file('inputActaE')->extension());
                $plangestion = PlanesGestionModel::where('id_accion', $request->id)->first();
                $plangestion->update(array(
                    'estado' => 5,
                    'archivo_acta_e' => 'actae_'.$request->id.'_'.$date.'.'.$request->file('inputActaE')->extension(),
                    'original_acta_e' => $request->file('inputActaE')->getClientOriginalName()
                ));
                $noti = (object)array(
                    'para' => 'Coordinador',
                    'tipo' => 'primary',
                    'texto' => 'Evaluación plan de gestión',
                    'url' => '/plagesg/listar'
                );
                $this->Notificar($noti);
                $documento1 = DocumentosModel::where('id_accion', $request->id)->where('n_tipo', 7)->first();
                if($documento1 != null){
                    $documento1->update(array(
                        'archivo' => 'actae_'.$request->id.'_'.$date.'.'.$request->file('inputActaE')->extension(),
                        'n_original' => $request->file('inputActaE')->getClientOriginalName(),
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                } else{
                    DocumentosModel::create(array(
                        'id_accion' => $request->id,
                        'n_tipo' => 7,
                        't_tipo' => 'ACTA MESA DE TRABAJO CON ENLACE',
                        'carpeta' => 'vee2_cargados',
                        'archivo' => 'actae_'.$request->id.'_'.$date.'.'.$request->file('inputActaE')->extension(),
                        'n_original' => $request->file('inputActaE')->getClientOriginalName(),
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                }
                DB::commit();
                return $this->MsjRespuesta(true);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

    public function FirmarPlanGestionCoordinador(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            if($request->aproboPg == 'Si'){
                $archivo_firmado = $this->PDFPlanGestion($request->id, true);
                $plangestion = PlanesGestionModel::where('id_accion', $request->id)->first();
                $plangestion->update(array(
                    'estado' => 8,
                    'archivo_firmado' => $archivo_firmado,
                    'id_coordinador' => $sesion->id,
                    'fecha_coordinador' => Carbon::now()->format('d/m/Y')
                ));
                $noti = (object)array(
                    'para' => 'Delegado',
                    'id_delegada' => $plangestion->id_delegada,
                    'tipo' => 'success',
                    'texto' => 'Plan de gestión aprobado',
                    'url' => '/plagesg/listar'
                );
                $this->Notificar($noti);
                $noti2 = (object)array(
                    'para' => 'Funcionarios',
                    'funcionarios' => $plangestion->idsUsuariosDecla,
                    'id_delegada' => $plangestion->id_delegada,
                    'tipo' => 'success',
                    'texto' => 'Plan de gestión aprobado',
                    'url' => '/plagesg/listar'
                );
                $this->Notificar($noti2);
                $documento1 = DocumentosModel::where('id_accion', $request->id)->where('n_tipo', 8)->first();
                if($documento1 != null){
                    $documento1->update(array(
                        'archivo' => $archivo_firmado,
                        'n_original' => $archivo_firmado,
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                } else{
                    DocumentosModel::create(array(
                        'id_accion' => $request->id,
                        'n_tipo' => 8,
                        't_tipo' => 'PLAN DE GESTIÓN FIRMADO',
                        'carpeta' => 'vee2_generados',
                        'archivo' => $archivo_firmado,
                        'n_original' => $archivo_firmado,
                        'fecha' => Carbon::now()->format('d/m/Y'),
                        'usuario' => $sesion->nombre,
                        'id_usuario' => $sesion->id,
                    ));
                }
                AccionesModel::where('id', $request->id)->update(array('estado' => 8));
                DB::commit();
                return $this->MsjRespuesta(true);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->MsjRespuesta(false, $ex->getTrace());
        }
    }

}
