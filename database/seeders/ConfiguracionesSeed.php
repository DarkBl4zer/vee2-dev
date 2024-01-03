<?php

namespace Database\Seeders;

use App\Models\ConfiguracionesModel;
use Illuminate\Database\Seeder;

class ConfiguracionesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            array("nombre" => "UrlApp", "descripcion" => "Url de publicación de la aplicación.", "t_valor" => "http://localhost:8000/"),
            array("nombre" => "UrlSinproc", "descripcion" => "Url de ambiente de la aplicación de SINPROC a la cual apunta la app.", "t_valor" => "https://dev.personeriabogota.gov.co/sinproc_P4/")
        ];
        for ($i=0; $i < count($datos); $i++) {
            ConfiguracionesModel::create($datos[$i]);
        }
    }
}
