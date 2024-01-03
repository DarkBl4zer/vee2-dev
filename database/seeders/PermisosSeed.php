<?php

namespace Database\Seeders;

use App\Models\PermisosModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermisosSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            array('url' => 'actas/listar', 'accion' => 'nuevo', 'id_rol' => 3),
            array('url' => 'actas/listar', 'accion' => 'activar', 'id_rol' => 3),
            array('url' => 'actas/listar', 'accion' => 'reemplazar', 'id_rol' => 3),
            array('url' => 'temasp/listar', 'accion' => 'nuevo', 'id_rol' => 3),
            array('url' => 'temasp/listar', 'accion' => 'masiva', 'id_rol' => 3),
            array('url' => 'temasp/listar', 'accion' => 'activar', 'id_rol' => 3),
            array('url' => 'temasp/listar', 'accion' => 'editar', 'id_rol' => 3),
            array('url' => 'accionespyc/listar', 'accion' => 'nuevo', 'id_rol' => 3),
            array('url' => 'accionespyc/listar', 'accion' => 'editar', 'id_rol' => 3, 'estados' => '[1,2,3,4,7]'),
            array('url' => 'accionespyc/listar', 'accion' => 'conflicto', 'id_rol' => 3),
            array('url' => 'accionespyc/listar', 'accion' => 'cambiod', 'id_rol' => 2, 'estados' => '[2]'),
            array('url' => 'planest/listar', 'accion' => 'nuevo', 'id_rol' => 3),
            array('url' => 'planest/listar', 'accion' => 'editar', 'id_rol' => 3, 'estados' => '[1,2,3]'),
            array('url' => 'planest/listar', 'accion' => 'respuesta', 'id_rol' => 3, 'estados' => '[2]'),
            array('url' => 'planest/listar', 'accion' => 'aprobar', 'id_rol' => 2, 'estados' => '[2,4]'),
            array('url' => 'planest/listar', 'accion' => 'modificar', 'id_rol' => 3, 'estados' => '[5]'),
            array('url' => 'plagesg/listar', 'accion' => 'nuevo', 'id_rol' => 3),
            array('url' => 'plagesg/listar', 'accion' => 'editar', 'id_rol' => 5, 'estados' => '[1,2,3,6]'),
            array('url' => 'plagesg/listar', 'accion' => 'conflicto', 'id_rol' => 5),
            array('url' => 'plagesg/listar', 'accion' => 'aprobar', 'id_rol' => 3, 'estados' => '[3]'),
            array('url' => 'plagesg/listar', 'accion' => 'aprobar', 'id_rol' => 4, 'estados' => '[4]'),
            array('url' => 'plagesg/listar', 'accion' => 'aprobar', 'id_rol' => 2, 'estados' => '[5]'),
            array('url' => 'plagesg/listar', 'accion' => 'equipo', 'id_rol' => 3, 'estados' => '[1,2,3,4,5,6,7]'),
        ];
        for ($i=0; $i < count($datos); $i++) {
            PermisosModel::create($datos[$i]);
        }
    }
}
