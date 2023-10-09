<?php

namespace Database\Seeders;
date_default_timezone_set('America/Bogota');
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
            array('tipo' => 'SEPAR', 'orden' => 4),
            array('tipo' => 'MENU', 'icono' => 'fas fa-road', 'nombre' => 'Plan de trabajo', 'descripcion' => 'Registro de acciones y de prevención de control', 'orden' => 5),
            array('tipo' => 'SEPAR', 'orden' => 6),
            array('tipo' => 'MENU', 'icono' => 'fas fa-sliders-h', 'nombre' => 'Plan de gestión', 'descripcion' => 'Plan de gestión', 'orden' => 7),
            array('tipo' => 'SEPAR', 'orden' => 8),
            array('tipo' => 'MENU', 'icono' => 'fas fa-walking', 'nombre' => 'Ejecución / Desarrollo', 'descripcion' => 'Acciones ejecución', 'orden' => 9),
            array('tipo' => 'SEPAR', 'orden' => 10),
            array('tipo' => 'MENU', 'icono' => 'fas fa-calendar-check', 'nombre' => 'Actuaciones Posteriores', 'descripcion' => 'Actuaciones Posteriores', 'orden' => 11),
            array('tipo' => 'SEPAR', 'orden' => 12),
            array('tipo' => 'LINK', 'icono' => 'fas fa-history', 'nombre' => 'Historial proceso', 'url' => 'historial', 'orden' => 13)
        ];
        $subs[1]=[
            array('nombre' => 'Usuarios', 'url' => 'config/usuarios', 'orden' => 1),
            array('nombre' => 'Listas', 'url' => 'config/listas', 'orden' => 2),
            array('nombre' => 'Firma', 'url' => 'config/firma', 'orden' => 3),
        ];
        $subs[3]=[
            array('nombre' => 'Actas', 'url' => 'actas/listar', 'orden' => 1),
            array('nombre' => 'Temas', 'url' => 'temasp/listar', 'orden' => 2),
        ];
        $subs[5]=[
            array('nombre' => 'Listar acciones', 'url' => 'accionespyc/listar', 'orden' => 1),
            array('nombre' => 'Listar planes', 'url' => 'planest/listar', 'orden' => 2),
        ];
        $subs[7]=[
            array('nombre' => 'Listar planes', 'url' => 'plagesg/listar', 'orden' => 1),
        ];
        $subs[9]=[
            array('nombre' => 'Ejecuciones / Desarrollos', 'url' => 'ejecuciones/listar', 'orden' => 1),
            array('nombre' => 'Listar actuaciones', 'url' => 'actuacioneseje/listar', 'orden' => 2),
        ];
        $subs[11]=[
            array('nombre' => 'Acciones PyC ejecutadas', 'url' => 'posteriores/listar', 'orden' => 1),
            array('nombre' => 'Listar actuaciones', 'url' => 'posteriores/listar', 'orden' => 2),
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
