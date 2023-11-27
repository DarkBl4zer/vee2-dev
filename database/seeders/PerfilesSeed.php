<?php

namespace Database\Seeders;

use App\Models\PerfilesModel;
use Illuminate\Database\Seeder;

class PerfilesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            array('id_usuario' => 1, 'id_rol' => 1),
            array('id_usuario' => 1, 'id_rol' => 2, 'tipo_coord' => 'PD'),
            array('id_usuario' => 1, 'id_rol' => 2, 'tipo_coord' => 'LOCALES'),
            array('id_usuario' => 1, 'id_rol' => 3, 'id_delegada' => 57),
            array('id_usuario' => 1, 'id_rol' => 3, 'id_delegada' => 16),
            array('id_usuario' => 1, 'id_rol' => 4, 'id_delegada' => 57),
            array('id_usuario' => 1, 'id_rol' => 4, 'id_delegada' => 16),
            array('id_usuario' => 1, 'id_rol' => 5, 'id_delegada' => 57),
            array('id_usuario' => 1, 'id_rol' => 5, 'id_delegada' => 16),
        ];
        for ($i=0; $i < count($datos); $i++) {
            PerfilesModel::create($datos[$i]);
        }
    }
}
