<?php

namespace App\Http\Controllers;

use App\Models\AccionesModel;
use App\Models\CronogramaModel;
use App\Models\CronogramaSemanaModel;
use App\Models\DeclaracionesModel;
use App\Models\PerfilesModel;
use App\Models\PlanesGestionModel;
use App\Models\PlanGTextoModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class PlanGestionController extends Controller
{
    public function Planesgestion(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $diaUno = Carbon::createFromFormat('Y-m-d H:i:s', date("Y").'-01-01 00:00:00', 'America/Bogota');
            if($sesion->trabajo->id_rol < 3){
                $datos = PlanesGestionModel::where('created_at', '>', $diaUno)->orderBy('id', 'asc')->get();
            } else{
                $datos = PlanesGestionModel::where('created_at', '>', $diaUno)->where('id_delegada', $sesion->trabajo->id_delegada)->orderBy('id', 'asc')->get();
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

    public function UsuariosPlanesgestion(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $usuariosIn = [];
            $usuariosOut = [];
            $perfiles = PerfilesModel::where('activo', true)->where('id_rol', 5)->get();
            foreach ($perfiles as $item) {
                $usuario = array(
                    'id' => $item->usuario->id,
                    'cedula' => $item->usuario->cedula,
                    'nombre' => $item->usuario->nombre,
                    'delegada' => $item->apdelegada
                );
                if ($item->id_delegada == $sesion->trabajo->id_delegada ) {
                    array_push($usuariosIn, $usuario);
                } else{
                    array_push($usuariosOut, $usuario);
                }
            }
            $Tacciones = AccionesModel::where('id_delegada', $sesion->trabajo->id_delegada)->get();
            $acciones = [];
            foreach ($Tacciones as $item) {
                array_push($acciones, array(
                    'id' => $item->id,
                    'nombre' => '['.$item->numero.'] '. Str::limit($item->titulo, 70, '...')
                ));
            }
            return response()->json(array(
                'usuariosIn' => $usuariosIn,
                'usuariosOut' => $usuariosOut,
                'acciones' => $acciones
            ));
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function GuardarEquipoPlanesgestion(Request $request){
        DB::beginTransaction();
        try {
            $sesion = (object)$request->sesion;
            if ($request->id != 0) {
                //TODO actualizar equipo
            } else{
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function ObtenerPasoPlanesgestion(Request $request){
        try {
            $texto = PlanGTextoModel::where('id_accion', $request->id)->where('tipo', $request->tipo)->first();
            return response()->json($texto);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function PreviaPlanGestion(Request $request){
        try {
            $pdf = $this->PDFPlanGestion($request->id, false);
            return $pdf->stream();
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    private function PDFPlanGestion($idAcc, $save){
        $sesion = (object)Session::get('UsuarioVee');
        $plangestion = PlanesGestionModel::where('id_accion', $idAcc)->first();
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
        $dom_pdf = $pdf_pg->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(500, 51, "{PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0, 0, 0));
        if($save){
            $date = Carbon::now()->format('YmdHis');
            $nombreArchivo = 'PLAN_G_'.$idAcc.'_'.$date.'.pdf';
            $pdf_pg->save(storage_path().'/app/vee2_generados/'.$nombreArchivo);
            return $nombreArchivo;
        } else{
            return $pdf_pg;
        }
    }



}