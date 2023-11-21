<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsuariosModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_usuarios';
    protected $fillable = [
        'cedula',
        'nombre',
        'email',
        'activo',
        'usuario_crea'
    ];
    protected $guarded = ['id'];
    protected $appends = ['apperfiles'];

    public function firmas(): HasMany {
        return $this->hasMany(FirmasModel::class, 'id_usuario', 'id')->where('vee2_firmas.activo', true)->orderBy('vee2_firmas.activo', 'desc');
    }
    public function perfiles(): HasMany {
        return $this->hasMany(PerfilesModel::class, 'id_usuario', 'id')->where('vee2_perfiles.activo', true)->orderBy('vee2_perfiles.id_rol', 'asc');
    }
    public function notificaciones(): HasMany {
        return $this->hasMany(UsuarioNotificacionModel::class, 'id_usuario', 'id')->where('vee2_usuario_notificacion.activo', true)->where('vee2_usuario_notificacion.created_at', 'desc');
    }

    public function getApperfilesAttribute(): Array{
        $retorno = [];
        $perfiles = PerfilesModel::where('id_usuario', $this->id)->where('activo', true)->get();
        foreach ($perfiles as $item) {
            array_push($retorno, array(
                'id' => $item->id,
                'id_rol' => $item->id_rol,
                'rol' => $item->aprol,
                'tipo_coord' => $item->tipo_coord,
                'delegada' => $item->apdelegada
            ));
        }
        return $retorno;
    }
}
