<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechazosPtModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_rechazos_pt';
    protected $fillable = [
        'id_plant',
        'fecha_rechazo',
        'texto_rechazo',
        'nombre_rechazo',
        'fecha_respuesta',
        'texto_respuesta',
        'nombre_respuesta',
        'activo'
    ];
    protected $guarded = ['id'];
    protected $casts = [
        'fecha_rechazo' => 'datetime:d/m/Y h:m:sa',
        'fecha_respuesta' => 'datetime:d/m/Y h:m:sa'
    ];

}
