<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RolSubMenuModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_rol_submenu';
    protected $fillable = [
        'id_rol',
        'id_submenu',
        'editar',
        'usuario_crea'
    ];
    protected $guarded = ['id'];

    public function rol() : HasOne {
        return $this->hasOne(RolesModel::class, 'id', 'id_rol');
    }
    public function submenu() : HasOne {
        return $this->hasOne(SubMenusModel::class, 'id', 'id_submenu');
    }
}
