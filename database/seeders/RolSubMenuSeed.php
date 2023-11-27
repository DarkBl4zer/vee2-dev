<?php

namespace Database\Seeders;

use App\Models\RolSubMenuModel;
use Illuminate\Database\Seeder;

class RolSubMenuSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RolSubMenuModel::create(array('id_rol' => 1, 'id_submenu' => 1));
        RolSubMenuModel::create(array('id_rol' => 1, 'id_submenu' => 2));
        RolSubMenuModel::create(array('id_rol' => 1, 'id_submenu' => 4));
        RolSubMenuModel::create(array('id_rol' => 3, 'id_submenu' => 4));
        for ($i=1; $i < 13; $i++) {
            if ($i == 3 || $i > 4) {
                for ($x=1; $x < 6; $x++) {
                    RolSubMenuModel::create(array('id_rol' => $x, 'id_submenu' => $i));
                }
            }
        }
    }
}
