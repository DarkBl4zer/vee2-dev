<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigActasModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_config_actas';
    protected $fillable = [
        'tipo_acta',
        'require_aprobacion',
        'rol_aprueba',
        'require_firma',
        'posicion_firma'
    ];
    protected $guarded = ['id'];
}
