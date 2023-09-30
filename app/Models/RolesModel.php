<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RolesModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_roles';
    protected $fillable = [
        'nombre',
        'activo',
        'usuario_crea'
    ];
    protected $guarded = ['id'];

    public function usuarios(): BelongsToMany {
        return $this->belongsToMany(UsuariosModel::class, 'vee2_usuario_rol', 'id_rol', 'id_usuario')
                    ->where('vee2_usuario_rol.activo', true)
                    ->orderBy('vee2_usuarios.nombre', 'asc');
    }
    public function menus(): BelongsToMany {
        return $this->belongsToMany(MenusModel::class, 'vee2_rol_menu', 'id_rol', 'id_menu')
                    ->where('vee2_rol_menu.activo', true)
                    ->orderBy('vee2_menus.orden', 'asc');
    }
}
