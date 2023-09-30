<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RolMenuModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_rol_menu';
    protected $fillable = [
        'id_rol',
        'id_menu',
        'activo',
        'usuario_crea'
    ];
    protected $guarded = ['id'];

    public function rol() : HasOne {
        return $this->hasOne(RolesModel::class, 'id', 'id_rol');
    }
    public function menu() : HasOne {
        return $this->hasOne(MenusModel::class, 'id', 'id_menu');
    }
}
