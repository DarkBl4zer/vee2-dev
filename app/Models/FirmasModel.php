<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FirmasModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_firmas';
    protected $fillable = [
        'id_usuario',
        'inp_firma',
        'cnv_firma',
        'escala',
        'activo'
    ];
    protected $guarded = ['id'];

    public function usuario() : HasOne {
        return $this->hasOne(UsuariosModel::class, 'id', 'id_usuario');
    }
}
