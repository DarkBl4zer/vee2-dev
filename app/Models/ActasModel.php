<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActasModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_actas';
    protected $fillable = [
        'id_delegada',
        'tipo_acta',
        'descripcion',
        'id_accion',
        'aprobada',
        'archivo',
        'nombre_archivo',
        'activo'
    ];
    protected $guarded = ['id'];
    protected $appends = ['creado'];

    public function getCreadoAttribute(): String{
        return $this->created_at->format('d/m/Y');
    }
}
