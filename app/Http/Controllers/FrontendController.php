<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Bogota');

use App\Models\AccionesModel;
use App\Models\ActasModel;
use App\Models\CargosModel;
use App\Models\ConfiguracionesModel;
use App\Models\DelegadasModel;
use App\Models\ListasModel;
use App\Models\RolesModel;
use App\Models\TemasPModel;
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
    public function Logout(){
        Session::forget('UsuarioVee');
        return Redirect::to(route('fake_login'));
    }

    public function Inicio(Request $request){
        $sesion = (object)$request->sesion;
        $slag = '';
        return view('inicio', compact('sesion', 'slag'));
    }

    public function ConfigUsuarios(Request $request){
        $sesion = (object)$request->sesion;
        $usuarios = DB::table('vee2_usuarios')->select(
            'vee2_usuarios.id',
            'vee2_usuarios.cedula',
            'vee2_usuarios.nombre',
            'vee2_usuarios.activo'
        )->get();
        foreach ($usuarios as $item) {
            $perfiles = DB::table('vee2_perfiles')
            ->join('vee2_roles', 'vee2_perfiles.id_rol', 'vee2_roles.id')
            ->leftJoin('view_vee_delegadas', 'vee2_perfiles.id_delegada', 'view_vee_delegadas.id')
            ->select(
                'vee2_perfiles.id as id_perfil',
                'vee2_perfiles.tipo_coord',
                'vee2_roles.id as id_rol',
                'vee2_roles.nombre as nombre_rol',
                'view_vee_delegadas.nombre as nombre_delegada',
            )->where('vee2_perfiles.id_usuario', $item->id)->get();
            $item->perfiles = $perfiles;
        }
        $roles = RolesModel::where('activo', true)->orderBy('nombre', 'asc')->get();
        $delegadas = DelegadasModel::where('activo', true)->orderBy('nombre', 'asc')->get();
        $getUsuariosVEE = ConfiguracionesModel::where('nombre', 'UrlSinproc')->first()->t_valor."config/00_wssinproc/getUsuariosVEE.php";
        $datos = (object)array(
            "usuarios" => $usuarios,
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
        return view('firma', compact('sesion', 'firma', 'slag'));
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
        $permiteNueva = true;
        if($sesion->trabajo->id_rol < 3){
            $permiteNueva = false;
            $actas = ActasModel::where('tipo_acta', 1)->get();
        } else{
            $actas = ActasModel::where('tipo_acta', 1)->where('id_delegada', $sesion->trabajo->id_delegada)->get();
        }
        $slag = 'temasprioritarios';
        return view('listar_actas', compact('sesion', 'slag', 'permiteNueva', 'actas'));
    }

    public function ListarTemas(Request $request){
        $sesion = (object)$request->sesion;
        $permiteNueva = true;
        if($sesion->trabajo->id_rol < 3){
            $permiteNueva = false;
            $actas = ActasModel::where('tipo_acta', 1)->where('activo', true)->get();
            $temasp = TemasPModel::where('nivel', 1)->where('eliminado', false)->where('activo', true)->get();
        } else{
            $actas = ActasModel::where('tipo_acta', 1)->where('activo', true)->where('id_delegada', $sesion->trabajo->id_delegada)->get();
            $temasp = TemasPModel::where('id_delegada', $sesion->trabajo->id_delegada)->where('nivel', 1)->where('eliminado', false)->where('activo', true)->get();
        }
        $slag = 'temasprioritarios';
        return view('listar_temas', compact('sesion', 'slag', 'actas', 'temasp', 'permiteNueva'));
    }

    public function ListarAccionesPyC(Request $request){
        $sesion = (object)$request->sesion;
        $permiteNueva = true;
        if($sesion->trabajo->id_rol < 3){
            $permiteNueva = false;
            $years = AccionesModel::select('year')->where('year', '!=', date("Y"))->groupBy('year')->orderBy('year', 'desc')->get();
        } else{
            $years = AccionesModel::select('year')->where('year', '!=', date("Y"))->where('id_delegada', $sesion->trabajo->id_delegada)->groupBy('year')->orderBy('year', 'desc')->get();
        }
        $acciones = ListasModel::where('tipo', 'actuacion_vee')->where('activo', true)->orderBy('id', 'asc')->get();
        $terminadas = AccionesModel::where('activo', true)->where('id_delegada', $sesion->trabajo->id_delegada)->where('estado', 16)->orderBy('id', 'desc')->take(100)->get();
        $paraSeguimiento = "";
        foreach ($terminadas as $item) {
            $nombre = Str::limit($item->nombre, 50, ' (...)');
            $paraSeguimiento .= '<option value="'.$item->id.'">'.$nombre.'</option>';
        }
        $temasp = TemasPModel::where('activo', true)->where('eliminado', false)->where('id_delegada', $sesion->trabajo->id_delegada)->where('nivel', 1)->get();
        $profesiones = ListasModel::where('tipo', 'profesiones')->where('activo', true)->get();
        $cargos = CargosModel::orderBy('nombre_cargo', 'asc')->get();
        $slag = 'accionesdepyc';
        return view('listar_acciones', compact('sesion', 'slag', 'years', 'permiteNueva', 'acciones', 'paraSeguimiento', 'temasp', 'profesiones', 'cargos'));
    }

    public function ListarPlanesTrabajo(Request $request){
        $sesion = (object)$request->sesion;
        $slag = 'plandetrabajo';
        return view('listar_planest', compact('sesion', 'slag'));
    }
}
