<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TemasPModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_temas';
    protected $fillable = [
        'id_delegada',
        'nombre',
        'nivel',
        'activo',
        'eliminado',
        'id_acta',
        'id_padre'
    ];
    protected $guarded = ['id'];
    protected $appends = ['padre', 'acta'];

    public function getPadreAttribute(): String{
        if (!is_null($this->id_padre)) {
            return TemasPModel::where('id', $this->id_padre)->first()->nombre;
        } else {
            return "";
        }
    }

    public function getActaAttribute(): String{
        return ActasModel::where('id', $this->id_acta)->first()->descripcion;
    }

    public function modelActa() : HasOne {
        return $this->hasOne(ActasModel::class, 'id', 'id_acta');
    }
}
