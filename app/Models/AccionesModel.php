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
    protected $appends = ['numero', 'entidades', 'delegada', 'fechas', 'archivoacta', 'nombreestado'];

    public function getNumeroAttribute(): String{
        $actuaciones = ['', 'APC', 'SEG', 'SEGD', 'REVC'];
        return $actuaciones[$this->id_actuacion].$this->id;
    }

    public function getEntidadesAttribute(): Array{
        $entidades = AccionEntidadModel::where('id_accion', $this->id)->where('activo', true)->get();
        $arr = array();
        $string = '<ul style="padding-left: 10px;">';
        foreach ($entidades as $item) {
            $string .= '<li>'.$item->entidad->nombre.'</li>';
            array_push($arr, $item->id_entidad);
        }
        $string .= '</ul>';
        return array('string' => $string, 'arr' => $arr);
    }

    public function getDelegadaAttribute(): String{
        return DelegadasModel::where('id', $this->id_delegada)->first()->nombre;
    }

    public function getFechasAttribute(): String{
        $fechas = "Creado: ".$this->created_at->format('d/m/Y h:m:s a')."<br>";
        $fechas .= "Actualizado: ".$this->updated_at->format('d/m/Y h:m:s a');
        return $fechas;
    }

    public function getArchivoactaAttribute(): String{
        if (!is_null($this->id_temas)) {
            $archivo = TemasPModel::where('id', $this->id_temas)->first()->modelActa->archivo;
        } else {
            $archivo = TemasPModel::where('id', $this->id_temas)->first()->modelActa->archivo;
        }
        return $archivo;
    }

    public function getNombreestadoAttribute(): String{
        $estado = ListasModel::where('tipo', 'estados_acciones')->where('valor_numero', $this->estado)->first();
        return '<span class="badge badge-'.$estado->valor_texto.'">'.$estado->nombre.'</span>';
    }

}
