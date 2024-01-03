<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminadasModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_terminadas';
    protected $fillable = [
        'id_accion',
        'id_delegada',
        'nom_delegada',
        'id_actuacion',
        'nom_actuacion',
        'id_temap',
        'nom_temap',
        'id_temas',
        'nom_temas',
        'titulo',
        'objetivo_general',
        'fecha_plangestion',
        'numero_profesionales',
        'fecha_inicio',
        'fecha_final',
        'year',
        'cordis',
        'id_padre'
    ];
    protected $guarded = ['id'];
}
