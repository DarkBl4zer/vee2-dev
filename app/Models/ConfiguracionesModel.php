<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionesModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_configuraciones';
    protected $fillable = [
        'nombre',
        'descripcion',
        'n_valor',
        't_valor'
    ];
    protected $guarded = ['id'];
}
