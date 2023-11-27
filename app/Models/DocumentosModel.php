<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentosModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_documentos';
    protected $fillable = [
        'id_accion',
        'n_tipo',
        't_tipo',
        'carpeta',
        'archivo',
        'n_original',
        'fecha',
        'usuario',
        'id_usuario'
    ];
    protected $guarded = ['id'];
}
