<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisosModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_permisos';
    protected $fillable = [
        'url',
        'accion',
        'id_rol',
        'id_usuario',
        'estados'
    ];
    protected $guarded = ['id'];
}
