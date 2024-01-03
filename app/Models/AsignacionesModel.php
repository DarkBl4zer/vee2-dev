<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AsignacionesModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_asignaciones';
    protected $fillable = [
        'tipo',
        'id_accion',
        'id_usuario',
        'activo'
    ];
    protected $guarded = ['id'];

    public function accion() : HasOne {
        return $this->hasOne(AccionesModel::class, 'id', 'id_accion');
    }

    public function usuario() : HasOne {
        return $this->hasOne(UsuariosModel::class, 'id', 'id_usuario');
    }

}
