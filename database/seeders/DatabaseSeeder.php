<?php

namespace Database\Seeders;


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
        $this->call(EntidadesSeed::class);
        $this->call(DelegadaEntidadSeed::class);
        $this->call(PermisosSeed::class);
    }
}
