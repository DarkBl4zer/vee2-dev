<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubMenusModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_submenus';
    protected $fillable = [
        'id_menu',
        'nombre',
        'url',
        'orden',
        'usuario_crea'
    ];
    protected $guarded = ['id'];

    public function menu() : HasOne {
        return $this->hasOne(MenusModel::class, 'id', 'id_menu');
    }
}
