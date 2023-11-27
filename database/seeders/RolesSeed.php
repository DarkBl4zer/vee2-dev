<?php

namespace Database\Seeders;

use App\Models\RolesModel;
use Illuminate\Database\Seeder;

class RolesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            array('nombre' => 'ADMINISTRADOR(A)'),
            array('nombre' => 'COORDINADOR(A)'),
            array('nombre' => 'DELEGADO(A)'),
            array('nombre' => 'ENLACE'),
            array('nombre' => 'FUNCIONARIO(A)'),
        ];
        for ($i=0; $i < count($datos); $i++) {
            RolesModel::create($datos[$i]);
        }
    }
}
