<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenusModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_menus';
    protected $fillable = [
        'tipo',
        'icono',
        'nombre',
        'descripcion',
        'url',
        'orden',
        'usuario_crea'
    ];
    protected $guarded = ['id'];

    public function submenus(): HasMany {
        return $this->hasMany(SubMenusModel::class, 'id_menu', 'id')->orderBy('vee2_submenus.orden', 'asc');
    }
}
