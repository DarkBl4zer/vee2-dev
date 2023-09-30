<?php

use App\Http\Controllers\BackendController;
use App\Http\Controllers\FrontendController;
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
Route::get('prueba', [BackendController::class, 'prueba'])->name('prueba');

Route::get('/login', function () {
    return view('fake_login');
})->name('fake_login');
Route::get('sinregistro', function () {
    return view('sinregistro');
})->name('sinregistro');
Route::get('/logout', [FrontendController::class, 'Logout'])->name('logout');
Route::post('login', [BackendController::class, 'Login'])->name('login');
Route::middleware('vee')->group(function () {
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

    /* ====================================================== BACKEND ====================================================== */
    /* ******************* Base ******************* */
    Route::post('back/trabajo', [BackendController::class, 'VariablesTrabajo']);
    Route::get('back/notificaciones', [BackendController::class, 'Notificaciones']);
    Route::post('back/notificacion_vista', [BackendController::class, 'NotificacionesVista']);
    /* ******************* Notificaciones ******************* */
    Route::post('back/eliminar_notificacion', [BackendController::class, 'EliminarNotificacion']);
    /* ******************* Configuraciones / Usuarios ******************* */
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
    /* ******************* Temas principales ******************* */
    Route::get('back/temas_por_tipo', [BackendController::class, 'TemasPorTipo']);

});
