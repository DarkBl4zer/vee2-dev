<?php

use App\Http\Controllers\BackendController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PlanGestionController;
use App\Http\Controllers\PlanTrabajoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', function () {
    return view('fake_login');
})->name('fake_login');
Route::get('sinregistro', function () {
    return view('sinregistro');
})->name('sinregistro');
Route::get('/logout', [FrontendController::class, 'Logout'])->name('logout');
Route::post('login', [BackendController::class, 'Login'])->name('login');
Route::middleware('vee')->group(function () {
    Route::get('prueba', [BackendController::class, 'prueba'])->name('prueba');
    /* ====================================================== FRONTEND ====================================================== */
    Route::get('/', [FrontendController::class, 'Inicio'])->name('inicio');
    Route::get('notificaciones', [FrontendController::class, 'Notificaciones'])->name('notificaciones');
    Route::get('config/usuarios', [FrontendController::class, 'ConfigUsuarios'])->name('config_usuarios');
    Route::get('config/firma', [FrontendController::class, 'ConfigFirma'])->name('config_firma');
    Route::get('config/listas', [FrontendController::class, 'ConfigListas'])->name('config_listas');
    Route::get('temasp/listar', [FrontendController::class, 'ListarTemas'])->name('listar_temas');
    Route::get('actas/listar', [FrontendController::class, 'ListarActas'])->name('listar_actas');
    Route::get('accionespyc/listar', [FrontendController::class, 'ListarAccionesPyC'])->name('listar_accionespyc');
    Route::get('planest/listar', [FrontendController::class, 'ListarPlanesTrabajo'])->name('listar_planest');
    Route::get('plagesg/listar', [FrontendController::class, 'ListarPlanesGestion'])->name('listar_planesg');

    /* ====================================================== BACKEND ====================================================== */
    /* ******************* Base ******************* */
    Route::get('back/descargar_archivo', [BackendController::class, 'DescargarArchivo']);
    Route::post('back/trabajo', [BackendController::class, 'VariablesTrabajo']);
    Route::get('back/notificaciones', [BackendController::class, 'Notificaciones']);
    Route::post('back/notificacion_vista', [BackendController::class, 'NotificacionesVista']);
    Route::get('back/documentos_accion', [BackendController::class, 'DocumentosAccion']);
    /* ******************* Notificaciones ******************* */
    Route::post('back/eliminar_notificacion', [BackendController::class, 'EliminarNotificacion']);
    /* ******************* Configuraciones / Usuarios ******************* */
    Route::get('back/usuarios', [ConfigController::class, 'Usuarios']);
    Route::post('back/agregar_perfil', [BackendController::class, 'AgregarPerfil']);
    Route::post('back/eliminar_perfil', [BackendController::class, 'EliminarPerfil']);
    Route::post('back/activar_usuario', [BackendController::class, 'ActivarUsuario']);
    Route::post('back/sincronizar_usuarios', [BackendController::class, 'SincronizarUsuarios']);
    /* ******************* Configuraciones / Firma ******************* */
    Route::post('back/guardar_frima', [BackendController::class, 'GuardarFirma']);
    /* ******************* Configuraciones / Listas ******************* */
    Route::get('back/datos_config_lista', [BackendController::class, 'DatosConfigLista']);
    Route::post('back/crear_actualizar_item_lista', [BackendController::class, 'CrearActualizarItemLista']);
    Route::post('back/activar_item', [BackendController::class, 'ActivarItemLista']);
    /* ******************* Temas Actas ******************* */
    Route::get('back/actas_tp', [BackendController::class, 'ActasTP']);
    Route::post('back/guardar_actatp', [BackendController::class, 'GuardarActasTP']);
    Route::post('back/activar_acta', [BackendController::class, 'ActivarActa']);
    Route::post('back/reemplazar_acta', [BackendController::class, 'ReemplazarActa']);
    /* ******************* Temas principales ******************* */
    Route::get('back/temas_por_tipo', [BackendController::class, 'TemasPorTipo']);
    Route::post('back/crear_actualizar_tema', [BackendController::class, 'CrearActualizarTema']);
    Route::post('back/activar_tema', [BackendController::class, 'ActivarTema']);
    Route::post('back/carga_masiva_temas', [BackendController::class, 'CargaMasivaTemas']);

    /* ******************* PlanesTrabajo ******************* */
    Route::get('back/acciones_por_periodo', [PlanTrabajoController::class, 'AccionesPorPeriodo']);
    Route::get('back/temas_por_temap', [PlanTrabajoController::class, 'TemasPorTemap']);
    Route::get('back/entidades_por_delegada', [PlanTrabajoController::class, 'EntidadesPorDelegada']);
    Route::post('back/crear_actualizar_accion', [PlanTrabajoController::class, 'CrearActualizarAccion']);
    Route::get('back/accion_por_id', [PlanTrabajoController::class, 'AccionPorId']);
    Route::post('back/guardar_declaracion', [PlanTrabajoController::class, 'GuardarDeclaracion']);
    Route::get('back/previa_declaracion', [PlanTrabajoController::class, 'PreviaDeclaracion']);
    Route::get('back/planest_por_periodo', [PlanTrabajoController::class, 'PlanestabajoPorPeriodo']);
    Route::get('back/acciones_por_periodo_pt', [PlanTrabajoController::class, 'AccionesPorPeriodoPT']);
    Route::post('back/crear_actualizar_plantrabajo', [PlanTrabajoController::class, 'CrearActualizarPlanTrabajo']);
    Route::post('back/firmar_plantrabajo_d', [PlanTrabajoController::class, 'FirmarPlanTrabajoDelegado']);
    Route::post('back/firmar_plantrabajo_c', [PlanTrabajoController::class, 'FirmarPlanTrabajoCoordinador']);
    Route::get('back/previa_plantrabajo', [PlanTrabajoController::class, 'PreviaPlanTrabajo']);
    Route::post('back/plantrabajo_vigente', [PlanTrabajoController::class, 'PlanTrabajoVigente']);
    Route::post('back/plantrabajo_rechazo', [PlanTrabajoController::class, 'PlanTrabajoRechazo']);
    Route::get('back/rechazos_pt', [PlanTrabajoController::class, 'RechazosPlanTrabajo']);
    Route::post('back/plantrabajo_respuesta', [PlanTrabajoController::class, 'PlanTrabajoRespuesta']);
    Route::get('back/puede_firmar_delegado', [PlanTrabajoController::class, 'PlanTrabajoPuedeFirmarDelegado']);

    /* ******************* PlanesGestion ******************* */
    Route::get('back/planes_gestion', [PlanGestionController::class, 'Planesgestion']);
    Route::get('back/usuarios_plan_gestion', [PlanGestionController::class, 'UsuariosPlanesgestion']);
    Route::post('back/crear_actualizar_equipo_plangestion', [PlanGestionController::class, 'GuardarEquipoPlanesgestion']);
    Route::post('back/guardar_paso_plangestion', [PlanGestionController::class, 'GuardarPasoPlanesgestion']);
    Route::post('back/obtener_paso_plangestion', [PlanGestionController::class, 'ObtenerPasoPlanesgestion']);
    Route::post('back/guardar_cronograma', [PlanGestionController::class, 'GuardarCronograma']);
    Route::post('back/obtener_cronograma', [PlanGestionController::class, 'ObtenerCronograma']);
    Route::get('back/previa_plangestion', [PlanGestionController::class, 'PreviaPlanGestion']);
    Route::post('back/firmar_plangestion_d', [PlanGestionController::class, 'FirmarPlanGestionDelegado']);
    Route::post('back/firmar_plangestion_e', [PlanGestionController::class, 'FirmarPlanGestionEnlace']);
    Route::post('back/firmar_plangestion_c', [PlanGestionController::class, 'FirmarPlanGestionCoordinador']);

});
