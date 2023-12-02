<?php

namespace Database\Seeders;

use App\Models\UsuariosModel;
use Illuminate\Database\Seeder;

class UsuariosSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            array('cedula' => '1010', 'nombre' => 'FERNANDO DARÍO MANJARRÉS PATERNINA', 'email' => 'fdmanjarres@personeriabogota.gov.co'),
            array('cedula' => '52883582', 'nombre' => 'NINI YOHANA LOPEZ BENAVIDES', 'email' => 'nylopez@personeriabogota.gov.co')
        ];
        for ($i=0; $i < count($datos); $i++) {
            UsuariosModel::create($datos[$i]);
        }
    }
}
