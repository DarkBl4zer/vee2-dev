<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AccionEntidadModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_accion_entidad';
    protected $fillable = [
        'id_accion',
        'id_entidad',
        'activo'
    ];
    protected $guarded = ['id'];

    public function accion() : HasOne {
        return $this->hasOne(AccionesModel::class, 'id', 'id_accion');
    }
    public function entidad() : HasOne {
        return $this->hasOne(EntidadesModel::class, 'id', 'id_entidad');
    }
}
