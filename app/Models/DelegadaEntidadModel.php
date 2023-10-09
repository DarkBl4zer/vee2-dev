<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DelegadaEntidadModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_delegada_entidad';
    protected $fillable = [
        'id_delegada',
        'id_entidad',
        'activo'
    ];
    protected $guarded = ['id'];

    public function delegada() : HasOne {
        return $this->hasOne(DelegadasModel::class, 'id', 'id_delegada');
    }
    public function entidad() : HasOne {
        return $this->hasOne(EntidadesModel::class, 'id', 'id_entidad');
    }
}
