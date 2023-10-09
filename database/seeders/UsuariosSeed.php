<?php

namespace Database\Seeders;
date_default_timezone_set('America/Bogota');
use App\Models\UsuariosModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuariosSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            array('cedula' => '1010', 'nombre' => 'ADMINISTRADOR ADMINISTRADOR', 'email' => 'fdmanjarres@personeriabogota.gov.co'),
            array('cedula' => '52883582', 'nombre' => 'NINI YOHANA LOPEZ BENAVIDES', 'email' => 'nylopez@personeriabogota.gov.co')
        ];
        for ($i=0; $i < count($datos); $i++) {
            UsuariosModel::create($datos[$i]);
        }
    }
}
