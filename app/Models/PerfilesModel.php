<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PerfilesModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_perfiles';
    protected $fillable = [
        'id_usuario',
        'id_rol',
        'id_delegada',
        'activo',
        'tipo_coord',
        'usuario_crea'
    ];
    protected $guarded = ['id'];
    protected $appends = ['aprol', 'apdelegada'];

    public function usuario() : HasOne {
        return $this->hasOne(UsuariosModel::class, 'id', 'id_usuario');
    }
    public function rol() : HasOne {
        return $this->hasOne(RolesModel::class, 'id', 'id_rol');
    }
    public function delegada() : HasOne {
        return $this->hasOne(DelegadasModel::class, 'id', 'id_delegada');
    }

    public function getAprolAttribute(): String{
        return RolesModel::where('id', $this->id_rol)->first()->nombre;
    }

    public function getApdelegadaAttribute(): String{
        if (!is_null($this->id_delegada)) {
            $delegada = DelegadasModel::where('id', $this->id_delegada)->first();
            if (!is_null($delegada)) {
                return DelegadasModel::where('id', $this->id_delegada)->first()->nombre;
            }else{
                return "";
            }
        } else{
            return '';
        }
    }
}
