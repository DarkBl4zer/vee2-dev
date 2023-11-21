<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CronogramaModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_cronogramas';
    protected $fillable = [
        'id_accion',
        'id_etapa',
        'actividad'
    ];
    protected $guarded = ['id'];
    protected $appends = ['semanas'];

    public function getSemanasAttribute(): Array{
        $semanas = array();
        $temp = CronogramaSemanaModel::where('id_cronograma', $this->id)->get();
        foreach ($temp as $item) {
            array_push($semanas, $item->semana);
        }
        return $semanas;
    }
}
