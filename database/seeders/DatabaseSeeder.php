<?php

namespace Database\Seeders;
date_default_timezone_set('America/Bogota');

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ConfiguracionesSeed::class);
        $this->call(UsuariosSeed::class);
        $this->call(RolesSeed::class);
        $this->call(PerfilesSeed::class);
        $this->call(UsuarioNotificacionSeed::class);
        $this->call(MenusSeed::class);
        $this->call(RolMenuSeed::class);
        $this->call(RolSubMenuSeed::class);
        $this->call(ListasSeed::class);
        $this->call(ConfigActasSeed::class);
    }
}
