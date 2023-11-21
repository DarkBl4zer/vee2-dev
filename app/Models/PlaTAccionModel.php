<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlaTAccionModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_plan_t_accion';
    protected $fillable = [
        'id_plantrabajo',
        'id_accion'
    ];
    protected $guarded = ['id'];

    public function plantrabajo() : HasOne {
        return $this->hasOne(PlanesTrabajoModel::class, 'id', 'id_plantrabajo');
    }

    public function accion() : HasOne {
        return $this->hasOne(AccionesModel::class, 'id', 'id_accion');
    }

}
