<?php

namespace Database\Seeders;

use App\Models\PerfilesModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            array('id_usuario' => 1, 'id_rol' => 3, 'id_delegada' => 37),
            array('id_usuario' => 1, 'id_rol' => 4, 'id_delegada' => 60),
            array('id_usuario' => 1, 'id_rol' => 2, 'tipo_coord' => 'LOCALES'),
        ];
        for ($i=0; $i < count($datos); $i++) {
            PerfilesModel::create($datos[$i]);
        }
    }
}
