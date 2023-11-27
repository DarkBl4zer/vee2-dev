<?php

namespace Database\Seeders;

use App\Models\UsuarioNotificacionModel;
use Illuminate\Database\Seeder;

class UsuarioNotificacionSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            array('id_usuario' => 1, 'id_perfil' => 1, 'tipo' => 'success', 'texto' => 'Plan de trabajo aprobado', 'url' => 'https://translate.google.com.co/?sl=auto&tl=es&text=success&op=translate'),
            array('id_usuario' => 1, 'id_perfil' => 1, 'tipo' => 'danger', 'texto' => 'Solicitud de modificación plan de gestión', 'url' => 'https://translate.google.com.co/?sl=auto&tl=es&text=danger&op=translate'),
            array('id_usuario' => 1, 'id_perfil' => 1, 'tipo' => 'primary', 'texto' => 'Solicitud de aprobación plan de trabajo', 'url' => 'https://translate.google.com.co/?sl=auto&tl=es&text=primary&op=translate'),
        ];
        for ($i=0; $i < count($datos); $i++) {
            //UsuarioNotificacionModel::create($datos[$i]);
        }
    }
}
