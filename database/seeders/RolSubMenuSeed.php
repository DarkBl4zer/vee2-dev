<?php

namespace Database\Seeders;

use App\Models\RolSubMenuModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSubMenuSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i < 23; $i++) {
            for ($x=1; $x < 6; $x++) {
                RolSubMenuModel::create(array('id_rol' => $x, 'id_submenu' => $i));
            }
        }
    }
}
