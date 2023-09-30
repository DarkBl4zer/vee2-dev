<?php

namespace Database\Seeders;

use App\Models\MenusModel;
use App\Models\SubMenusModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenusSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            array('tipo' => 'MENU', 'icono' => 'fas fa-cog', 'nombre' => 'Configuraciones', 'descripcion' => 'Configuraciones', 'orden' => 1),
            array('tipo' => 'SEPAR', 'orden' => 2),
            array('tipo' => 'MENU', 'icono' => 'fas fa-bell', 'nombre' => 'Temas prioritarios', 'descripcion' => 'Identificación de temas prioritarios', 'orden' => 3),
            array('tipo' => 'TITLE', 'nombre' => 'Plan de trabajo', 'orden' => 4),
            array('tipo' => 'MENU', 'icono' => 'fas fa-clipboard-list', 'nombre' => 'Acciones de PyC', 'descripcion' => 'Registro de acciones y de prevención de control', 'orden' => 5),
            array('tipo' => 'MENU', 'icono' => 'fas fa-road', 'nombre' => 'Plan de trabajo', 'descripcion' => 'Plan de trabajo', 'orden' => 6),
            array('tipo' => 'SEPAR', 'orden' => 7),
            array('tipo' => 'MENU', 'icono' => 'fas fa-sliders-h', 'nombre' => 'Plan de gestión', 'descripcion' => 'Plan de gestión', 'orden' => 8),
            array('tipo' => 'SEPAR', 'orden' => 9),
            array('tipo' => 'MENU', 'icono' => 'fas fa-walking', 'nombre' => 'Ejecución / Desarrollo', 'descripcion' => 'Acciones ejecución', 'orden' => 10),
            array('tipo' => 'SEPAR', 'orden' => 11),
            array('tipo' => 'MENU', 'icono' => 'fas fa-calendar-check', 'nombre' => 'Actuaciones Posteriores', 'descripcion' => 'Actuaciones Posteriores', 'orden' => 12),
            array('tipo' => 'SEPAR', 'orden' => 13),
            array('tipo' => 'LINK', 'icono' => 'fas fa-history', 'nombre' => 'Historial proceso', 'url' => 'historial', 'orden' => 14)
        ];
        $subs[1]=[
            array('nombre' => 'Usuarios', 'url' => 'config/usuarios', 'orden' => 1),
            array('nombre' => 'Listas', 'url' => 'config/listas', 'orden' => 2),
            array('nombre' => 'Firma', 'url' => 'config/firma', 'orden' => 3),
        ];
        $subs[3]=[
            array('nombre' => 'Temas', 'url' => 'temasp/listar', 'orden' => 1),
            array('nombre' => 'Actas', 'url' => 'actas/listar', 'orden' => 2),
        ];
        $subs[5]=[
            array('nombre' => 'Listar acciones', 'url' => 'accionespyc/listar', 'orden' => 1),
            //array('nombre' => 'Crear acción', 'url' => 'accionespyc/crear', 'orden' => 2),
            //array('nombre' => 'Editar acción', 'url' => 'accionespyc/editar', 'orden' => 3),
        ];
        $subs[6]=[
            array('nombre' => 'Listar planes', 'url' => 'planest/listar', 'orden' => 1),
            //array('nombre' => 'Crear plan', 'url' => 'planest/crear', 'orden' => 2),
            //array('nombre' => 'Editar plan', 'url' => 'planest/editar', 'orden' => 3),
        ];
        $subs[8]=[
            array('nombre' => 'Listar planes', 'url' => 'plagesg/listar', 'orden' => 1),
            //array('nombre' => 'Crear plan', 'url' => 'plagesg/crear', 'orden' => 2),
            //array('nombre' => 'Editar plan', 'url' => 'plagesg/editar', 'orden' => 3),
        ];
        $subs[10]=[
            array('nombre' => 'Ejecuciones / Desarrollos', 'url' => 'ejecuciones/listar', 'orden' => 1),
            array('nombre' => 'Listar actuaciones', 'url' => 'actuacioneseje/listar', 'orden' => 2),
            //array('nombre' => 'Crear actuación', 'url' => 'actuacioneseje/crear', 'orden' => 3),
            //array('nombre' => 'Editar actuación', 'url' => 'actuacioneseje/editar', 'orden' => 4),
        ];
        $subs[12]=[
            array('nombre' => 'Acciones PyC ejecutadas', 'url' => 'posteriores/listar', 'orden' => 1),
            array('nombre' => 'Listar actuaciones', 'url' => 'posteriores/listar', 'orden' => 2),
            //array('nombre' => 'Crear actuación', 'url' => 'posteriores/crear', 'orden' => 3),
            //array('nombre' => 'Editar actuación', 'url' => 'posteriores/editar', 'orden' => 4),
        ];

        for ($i=0; $i < count($datos); $i++) {
            $id = MenusModel::create($datos[$i])->id;
            if ($datos[$i]['tipo']=='MENU') {
                $temp = $subs[$datos[$i]['orden']];
                for ($x=0; $x < count($temp); $x++) {
                    $temp[$x]['id_menu'] = $id;
                    SubMenusModel::create($temp[$x]);
                }
            }
        }
    }
}
