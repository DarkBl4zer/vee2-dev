<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UsuarioNotificacionModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_usuario_notificacion';
    protected $fillable = [
        'id_usuario',
        'id_perfil',
        'tipo',
        'texto',
        'url',
        'activo',
        'eliminado',
        'usuario_crea'
    ];
    protected $guarded = ['id'];
    protected $appends = ['creado', 'editado'];

    public function getCreadoAttribute(): String{
        return $this->created_at->format('d/m/Y h:m:s a');
    }
    public function getEditadoAttribute(): String{
        return $this->updated_at->format('d/m/Y h:m:s a');
    }
    public function usuario() : HasOne {
        return $this->hasOne(UsuariosModel::class, 'id', 'id_usuario');
    }
}
