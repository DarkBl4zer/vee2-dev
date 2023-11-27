<?php

namespace Database\Seeders;

use App\Models\RolMenuModel;
use Illuminate\Database\Seeder;

class RolMenuSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i < 14; $i++) {
            for ($x=1; $x < 6; $x++) {
                RolMenuModel::create(array('id_rol' => $x, 'id_menu' => $i));
            }
        }
    }
}
