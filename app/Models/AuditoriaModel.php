<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AuditoriaModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_auditoria';
    protected $fillable = [
        'id_usuario',
        'tipo',
        'modelo',
        'id_modelo',
        'old_json',
        'new_json'
    ];
    protected $guarded = ['id'];

    public function usuario() : HasOne {
        return $this->hasOne(UsuariosModel::class, 'id', 'id_usuario');
    }
}
