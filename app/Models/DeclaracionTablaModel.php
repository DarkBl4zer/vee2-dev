<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeclaracionTablaModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_declaracion_tabla';
    protected $fillable = [
        'id_declaracion',
        'tipo',
        'nombres',
        'cargo',
        'area',
        'tipo_relacion'
    ];
    protected $guarded = ['id'];
}
