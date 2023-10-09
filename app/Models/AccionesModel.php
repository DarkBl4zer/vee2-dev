<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AccionesModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_acciones';
    protected $fillable = [
        'id_delegada',
        'id_actuacion',
        'id_temap',
        'id_temas',
        'titulo',
        'objetivo_general',
        'fecha_plangestion',
        'numero_profesionales',
        'fecha_inicio',
        'fecha_final',
        'estado',
        'activo',
        'year',
        'id_padre'
    ];
    protected $guarded = ['id'];
    protected $appends = ['numero', 'entidades', 'delegada', 'creado'];

    public function getNumeroAttribute(): String{
        $actuaciones = ['', 'APC', 'SEG', 'SEGD', 'REVC'];
        return $actuaciones[$this->id_actuacion].$this->id;
    }

    public function getEntidadesAttribute(): String{
        $entidades = AccionEntidadModel::where('id_accion', $this->id)->where('activo', true)->get();
        $string = '<ul style="padding-left: 10px;">';
        foreach ($entidades as $item) {
            $string .= '<li>'.$item->entidad->nombre.'</li>';
        }
        $string .= '</ul>';
        return $string;
    }

    public function getDelegadaAttribute(): String{
        return DelegadasModel::where('id', $this->id_delegada)->first()->nombre;
    }

    public function getCreadoAttribute(): String{
        return $this->created_at->format('d/m/Y h:m:s a');
    }
}
