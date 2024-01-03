<?php

namespace App\Http\Controllers;

use App\Models\AccionesModel;
use App\Models\ActasModel;
use App\Models\CargosModel;
use App\Models\ConfiguracionesModel;
use App\Models\DelegadasModel;
use App\Models\ListasModel;
use App\Models\PlanesGestionModel;
use App\Models\PlanesTrabajoModel;
use App\Models\RolesModel;
use App\Models\TemasPModel;
use App\Models\TerminadasModel;
use App\Models\UsuarioNotificacionModel;
use App\Models\UsuariosModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FrontendController extends Controller
{

    public function Info(){
        return view('infophp');
    }

    public function Logout(){
        Session::forget('UsuarioVee');
        //return Redirect::to(route('fake_login'));
        $config = ConfiguracionesModel::where('nombre', 'UrlSinproc')->first();
        return redirect($config->t_valor."menuBootstrap.php");
    }

    public function Inicio(Request $request){
        $sesion = (object)$request->sesion;
        $slag = '';
        $conteo = array(
            'acciones' => AccionesModel::where('activo', true)->count(),
            'planest' => PlanesTrabajoModel::where('activo', true)->count(),
            'planesg' => PlanesGestionModel::where('activo', true)->count(),
            'ejecucion' => 0
        );
        return view('inicio', compact('sesion', 'slag', 'conteo'));
    }

    public function ConfigUsuarios(Request $request){
        $sesion = (object)$request->sesion;
        $roles = RolesModel::where('activo', true)->orderBy('nombre', 'asc')->get();
        $delegadas = DelegadasModel::where('activo', true)->orderBy('nombre', 'asc')->get();
        $getUsuariosVEE = ConfiguracionesModel::where('nombre', 'UrlSinproc')->first()->t_valor."config/00_wssinproc/getUsuariosVEE.php";
        $datos = (object)array(
            "roles" => $roles,
            "delegadas" => $delegadas,
            "getUsuariosVEE" => $getUsuariosVEE
        );
        $slag = 'configuraciones';
        return view('usuarios', compact('sesion', 'datos', 'slag'));
    }

    public function Notificaciones(Request $request){
        $sesion = (object)$request->sesion;
        $notificaciones = UsuarioNotificacionModel::where('id_usuario', $sesion->id)
                                                ->where('eliminado', false)
                                                ->where('id_perfil', $sesion->trabajo->id_perfil)
                                                ->orderBy('created_at', 'desc')->get();
        $slag = '';
        return view('notificaciones', compact('sesion', 'notificaciones', 'slag'));
    }

    public function ConfigFirma(Request $request){
        $sesion = (object)$request->sesion;
        $slag = 'configuraciones';
        $firmas = UsuariosModel::where('id', $sesion->id)->first()->firmas;
        if (isset($firmas[0])) {
            $firma = $firmas[0];
            $data = Storage::get('vee2_firmas/'.$firma->inp_firma);
            $exInpFirma = pathinfo(storage_path('vee2_firmas/'.$firma->inp_firma), PATHINFO_EXTENSION);
            $flInpFirma = file_get_contents(storage_path('app/vee2_firmas/'.$firma->inp_firma));
            $firma = (object)array(
                "inpFirma" => 'data:image/'.$exInpFirma.';base64,'.base64_encode($flInpFirma),
                "escFirma" => $firma->escala
            );
        } else {
            $firma = (object)array(
                "inpFirma" => null,
                "cnvFirma" => null,
                "escFirma" => null
            );
        }
        $retorno = (isset($request->retorno))?$request->retorno:"";
        return view('firma', compact('sesion', 'firma', 'slag', 'retorno'));
    }

    public function ConfigListas(Request $request){
        $sesion = (object)$request->sesion;
        $listas = ListasModel::where('activo', true)->where('tipo', 'tipo_listas')->orderBy('nombre', 'asc')->get();
        $tipoValor = ListasModel::where('activo', true)->where('tipo', 'tipo_valor_listas')->orderBy('id', 'asc')->get();
        $slag = 'configuraciones';
        return view('listas', compact('sesion', 'listas', 'tipoValor', 'slag'));
    }

    public function ListarActas(Request $request){
        $sesion = (object)$request->sesion;
        $slag = 'temasprioritarios';
        $permisos = $this->PermisosPorPagina($request->path());
        return view('listar_actas', compact('sesion', 'slag', 'permisos'));
    }

    public function ListarTemas(Request $request){
        $sesion = (object)$request->sesion;
        if($sesion->trabajo->id_rol < 3){
            $tipoDelegada = ($sesion->trabajo->tipo_coord == "PD")?1:4;
            $actas = ActasModel::select('vee2_actas.*')->join('view_vee_delegadas', 'vee2_actas.id_delegada', 'view_vee_delegadas.id')
                                ->where('vee2_actas.tipo_acta', 1)->where('vee2_actas.activo', true)->where('view_vee_delegadas.tipo', $tipoDelegada)
                                ->orderBy('vee2_actas.descripcion', 'asc')->get();
            $temasp = TemasPModel::select('vee2_temas.*')->join('view_vee_delegadas', 'vee2_temas.id_delegada', 'view_vee_delegadas.id')
                                ->where('vee2_temas.nivel', 1)->where('vee2_temas.eliminado', false)->where('view_vee_delegadas.tipo', $tipoDelegada)
                                ->orderBy('vee2_temas.nombre', 'asc')->get();
        } else{
            $actas = ActasModel::where('tipo_acta', 1)->where('activo', true)->where('id_delegada', $sesion->trabajo->id_delegada)->get();
            $temasp = TemasPModel::where('id_delegada', $sesion->trabajo->id_delegada)->where('nivel', 1)->where('eliminado', false)->where('activo', true)->get();
        }
        $slag = 'temasprioritarios';
        $permisos = $this->PermisosPorPagina($request->path());
        return view('listar_temas', compact('sesion', 'slag', 'actas', 'temasp', 'permisos'));
    }

    public function ListarAccionesPyC(Request $request){
        $sesion = (object)$request->sesion;
        $terminadas = array();
        $temasp = array();
        if($sesion->trabajo->id_rol == 1){
            $years = AccionesModel::select('year')->where('year', '!=', date("Y"))->groupBy('year')->orderBy('year', 'desc')->get();
        }
        if($sesion->trabajo->id_rol == 2){
            $tipoDelegada = ($sesion->trabajo->tipo_coord == "PD")?1:4;
            $years = AccionesModel::select('vee2_acciones.year')->join('view_vee_delegadas', 'vee2_acciones.id_delegada', 'view_vee_delegadas.id')
                                ->where('vee2_acciones.year', '!=', date("Y"))->where('view_vee_delegadas.tipo', $tipoDelegada)
                                ->groupBy('vee2_acciones.year')->orderBy('vee2_acciones.year', 'desc')->get();
        }
        if($sesion->trabajo->id_rol > 2){
            $terminadas = TerminadasModel::where('id_delegada', $sesion->trabajo->id_delegada)->get();
            // AccionesModel::where('activo', false)->where('id_delegada', $sesion->trabajo->id_delegada)->where('estado', 13)->orderBy('id', 'desc')->take(100)->get();
            $temasp = TemasPModel::where('activo', true)->where('eliminado', false)->where('id_delegada', $sesion->trabajo->id_delegada)->where('nivel', 1)->get();
            $years = AccionesModel::select('year')->where('year', '!=', date("Y"))->where('id_delegada', $sesion->trabajo->id_delegada)->groupBy('year')->orderBy('year', 'desc')->get();
        }
        $acciones = ListasModel::where('tipo', 'actuacion_vee')->where('activo', true)->orderBy('id', 'asc')->get();

        $paraSeguimiento = "";
        foreach ($terminadas as $item) {
            $nombre = Str::limit($item->titulo, 150, ' (...)');
            $paraSeguimiento .= '<option value="'.$item->id_accion.'">APC'.$item->id_accion.' - '.$nombre.'</option>';
        }
        $profesiones = ListasModel::where('tipo', 'profesiones')->where('activo', true)->get();
        $cargos = CargosModel::orderBy('nombre_cargo', 'asc')->get();
        $slag = 'plandetrabajo';
        $permisos = $this->PermisosPorPagina($request->path());
        return view('listar_acciones', compact('sesion', 'slag', 'years', 'permisos', 'acciones', 'paraSeguimiento', 'temasp', 'profesiones', 'cargos'));
    }

    public function ListarPlanesTrabajo(Request $request){
        $sesion = (object)$request->sesion;
        if($sesion->trabajo->id_rol == 1){
            $years = AccionesModel::select('year')->where('year', '!=', date("Y"))->groupBy('year')->orderBy('year', 'desc')->get();
        }
        if($sesion->trabajo->id_rol == 2){
            $tipoDelegada = ($sesion->trabajo->tipo_coord == "PD")?1:4;
            $years = AccionesModel::select('vee2_acciones.year')->join('view_vee_delegadas', 'vee2_acciones.id_delegada', 'view_vee_delegadas.id')
                                ->where('vee2_acciones.year', '!=', date("Y"))->where('view_vee_delegadas.tipo', $tipoDelegada)
                                ->groupBy('vee2_acciones.year')->orderBy('vee2_acciones.year', 'desc')->get();
        }
        if($sesion->trabajo->id_rol > 2){
            $years = AccionesModel::select('year')->where('year', '!=', date("Y"))->where('id_delegada', $sesion->trabajo->id_delegada)->groupBy('year')->orderBy('year', 'desc')->get();
        }
        $slag = 'plandetrabajo';
        $permisos = $this->PermisosPorPagina($request->path());
        return view('listar_planest', compact('sesion', 'slag', 'years', 'permisos'));
    }

    public function ListarPlanesGestion(Request $request){
        $sesion = (object)$request->sesion;
        $profesiones = ListasModel::where('tipo', 'profesiones')->where('activo', true)->get();
        $cargos = CargosModel::orderBy('nombre_cargo', 'asc')->get();
        $slag = 'plandegestin';
        $permisos = $this->PermisosPorPagina($request->path());
        return view('listar_planesg', compact('sesion', 'slag', 'permisos', 'profesiones', 'cargos'));
    }
}
