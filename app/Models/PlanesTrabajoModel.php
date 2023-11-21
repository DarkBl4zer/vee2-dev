<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class PlanesTrabajoModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_planes_trabajo';
    protected $fillable = [
        'year',
        'id_delegada',
        'descripcion',
        'estado',
        'activo',
        'version',
        'archivo_firmado',
        'vigente'
    ];
    protected $guarded = ['id'];
    protected $appends = ['str_acciones', 'fechas', 'nombreestado', 'delegada'];

    public function acciones() : BelongsToMany {
        return $this->belongsToMany(AccionesModel::class, 'vee2_plan_t_accion', 'id_plantrabajo', 'id_accion');
    }

    public function getStrAccionesAttribute(): String{
        $pt_acc = PlaTAccionModel::where('id_plantrabajo', $this->id)->get();
        $string = '<ul style="padding-left: 10px;">';
        foreach ($pt_acc as $item) {
            $string .= '<li>['.$item->accion->numero.'] '.Str::limit($item->accion->titulo, 150, ' (...)').'</li>';
        }
        $string .= '</ul>';
        return $string;
    }

    public function getFechasAttribute(): String{
        $fechas = "Creado: ".$this->created_at->format('d/m/Y h:m:s a')."<br>";
        $fechas .= "Actualizado: ".$this->updated_at->format('d/m/Y h:m:s a');
        return $fechas;
    }

    public function getNombreestadoAttribute(): String{
        $estado = ListasModel::where('tipo', 'estados_plant')->where('valor_numero', $this->estado)->first();
        return '<span class="badge badge-'.$estado->valor_texto.'">'.$estado->nombre.'</span>';
    }

    public function getDelegadaAttribute(): Array{
        $delegada = DelegadasModel::where('id', $this->id_delegada)->first();
        return array(
            'nombre' => $delegada->nombre,
            'tipo' => $delegada->tipo
        );
    }

}
