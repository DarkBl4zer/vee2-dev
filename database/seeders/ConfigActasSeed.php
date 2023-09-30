<?php

namespace Database\Seeders;

use App\Models\ConfigActasModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigActasSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            array('tipo_acta' => 1),
            array('tipo_acta' => 2),
            array('tipo_acta' => 3),
            array('tipo_acta' => 4),
        ];
        for ($i=0; $i < count($datos); $i++) {
            ConfigActasModel::create($datos[$i]);
        }
    }
}
