<?php

namespace App\Http\Controllers;

use App\Models\AccionEntidadModel;
use App\Models\AccionesModel;
use App\Models\DeclaracionesModel;
use App\Models\DeclaracionTablaModel;
use App\Models\DelegadaEntidadModel;
use App\Models\PlanesTrabajoModel;
use App\Models\PlaTAccionModel;
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
            if($sesion->trabajo->id_rol < 3){
                $datos = AccionesModel::where('year', $request->periodo)->orderBy('id', 'asc')->get();
            } else{
                $datos = AccionesModel::where('year', $request->periodo)->where('id_delegada', $sesion->trabajo->id_delegada)->orderBy('id', 'asc')->get();
                //****Consultar Declaraciones firmadas
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            $new["titulo"]=$request->titulo;
            $new["objetivo_general"]=$request->objetivo_general;
            $new["fecha_plangestion"]=Carbon::createFromFormat('d/m/Y', $request->fecha_plangestion)->format('Y-m-d');
            $new["numero_profesionales"]=$request->numero_profesionales;
            $new["fecha_inicio"]=Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
            $new["fecha_final"]=Carbon::createFromFormat('d/m/Y', $request->fecha_final)->format('Y-m-d');
            $new["id_padre"]=$request->id_padre;
            if ($request->id != 0) {
                $old = AccionesModel::where('id', $request->id)->first();
                AccionesModel::where('id', $request->id)->update($new);
                $this->Auditoria($sesion->id, "UPDATE", "AccionesModel", $request->id, $old, $new);
                AccionEntidadModel::where('id_accion', $request->id)->update(['activo' => false]);
                $this->Auditoria($sesion->id, "UPDATE", "AccionEntidadModel", $request->id, 'activo:true ALL', 'activo:false ALL');
                foreach ($request->entidades as $item) {
                    $new2 = [
                        'id_accion' => $request->id,
                        'id_entidad' => $item,
                    ];
                    $idAcEnt = AccionEntidadModel::create($new2)->id;
                    $this->Auditoria($sesion->id, "INSERT", "AccionEntidadModel", $idAcEnt, null, $new2);
                }
            } else{
                $new["id_delegada"]=$sesion->trabajo->id_delegada;
                $new["year"]=date("Y");
                $idModelo = AccionesModel::create($new)->id;
                $this->Auditoria($sesion->id, "INSERT", "AccionesModel", $idModelo, null, $new);
                foreach ($request->entidades as $item) {
                    $new2 = [
                        'id_accion' => $idModelo,
                        'id_entidad' => $item,
                    ];
                    $idAcEnt = AccionEntidadModel::create($new2)->id;
                    $this->Auditoria($sesion->id, "INSERT", "AccionEntidadModel", $idAcEnt, null, $new2);
                }
                $declara = array(
                    'id_accion' => $idModelo,
                    'id_usuario' => $sesion->id
                );
                $idDeclara = DeclaracionesModel::create($declara)->id;
                $this->Auditoria($sesion->id, "INSERT", "DeclaracionesModel", $idDeclara, null, $declara);
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

    public function AccionPorId(Request $request){
        try {
            $sesion = (object)$request->sesion;
            $accion = AccionesModel::where('id', $request->id)->first();
            return response()->json($accion);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            DB::commit();
            if(!$request->previa){
                AccionesModel::where('id', $request->id_accion)->update([
                    'estado' => 2
                ]);
                DeclaracionesModel::where('id', $declaracion->id)->update([
                    'archivo_firmado' => $this->PDFDeclaracion($declaracion->id, true)
                ]);
                return $this->MsjRespuesta(true);
            } else{
                return response()->json(array('id' => $declaracion->id));
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
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
            if ($item->id = $sesion->trabajo->id_perfil) {
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
            if($sesion->trabajo->id_rol < 3){
                $datos = PlanesTrabajoModel::where('year', $request->periodo)->orderBy('id', 'asc')->orderBy('id_delegada', 'asc')->get();
            } else{
                $datos = PlanesTrabajoModel::where('year', $request->periodo)->where('id_delegada', $sesion->trabajo->id_delegada)->orderBy('version', 'asc')->get();
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
                        array_push($arr_select, $acc->id_accion);
                    }
                }
            }
            return response()->json(array(
                'datos' => $datos,
                'chks' => $arr_select
            ));
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
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
                $new = [
                    'year' => date("Y"),
                    'id_delegada' => $sesion->trabajo->id_delegada,
                    'version' => $request->version
                ];
                $idModelo = PlanesTrabajoModel::create($new)->id;
                $this->Auditoria($sesion->id, "INSERT", "PlanesTrabajoModel", $idModelo, null, $new);
            }
            foreach ($request->arrAcciones as $item) {
                PlaTAccionModel::create([
                    'id_plantrabajo' => $idModelo,
                    'id_accion' => $item
                ]);
                AccionesModel::where('id', $item)->update(['estado' => 6]);
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

    public function FirmarPlanTrabajo(Request $request){
        try {
            PlanesTrabajoModel::where('id', $request->id)->update(array(
                'estado' => 2,
                'archivo_firmado' => $this->PDFPlanTrabajo($request->id, true)
            ));
            return $this->MsjRespuesta(true);
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    public function PreviaPlanTrabajo(Request $request){
        try {
            $pdf = $this->PDFPlanTrabajo($request->id, false);
            return $pdf->stream();
        } catch (Exception $ex) {
            return $this->MsjRespuesta(false, [
                'getFile' => $ex->getFile(),
                'getLine' => $ex->getLine(),
                'getMessage' => $ex->getMessage()
            ]);
        }
    }

    private function PDFPlanTrabajo($idPT, $save){
        $sesion = (object)Session::get('UsuarioVee');
        $PTAcciones = PlaTAccionModel::where('id_plantrabajo', $idPT)->get();

        $checkedT = pathinfo(public_path('img/checked.png'), PATHINFO_EXTENSION);
        $checkedD = file_get_contents(public_path('img/checked.png'));

        $uncheckedT = pathinfo(public_path('img/unchecked.png'), PATHINFO_EXTENSION);
        $uncheckedD = file_get_contents(public_path('img/unchecked.png'));

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

}
