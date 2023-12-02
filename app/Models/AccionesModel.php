<?php

namespace App\Models;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Mockery\Matcher\Any;
use Ramsey\Uuid\Type\Integer;

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
        'cordis',
        'id_padre'
    ];
    protected $guarded = ['id'];
    protected $appends = ['numero', 'entidades', 'delegada', 'fechas', 'archivoacta', 'nombreestado', 'actuacion', 'padre', 'idPT'];

    public function getNumeroAttribute(): String{
        $actuaciones = ['', 'APC', 'SEG', 'SEGD', 'REVC'];
        return $actuaciones[$this->id_actuacion].$this->id;
    }

    public function getEntidadesAttribute(): Array{
        $entidades = AccionEntidadModel::where('id_accion', $this->id)->where('activo', true)->get();
        $arr = array();
        $string = '<ul style="padding-left: 15px;">';
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

    public function getArchivoactaAttribute(): Array{
        if (!is_null($this->id_temas)) {
            $archivo = TemasPModel::where('id', $this->id_temas)->first();
        } else {
            $archivo = TemasPModel::where('id', $this->id_temap)->first();
        }
        return array(
            'archivo' => $archivo->modelActa->archivo,
            'tema' => $archivo->nombre,
            'padre' => $archivo->padre
        );
    }

    public function getNombreestadoAttribute(): String{

        if ($this->estado == 1 and $this->created_at != $this->updated_at) {
            return '<span class="badge badge-info">EDITADO</span>';
        } else{
            $estado = ListasModel::where('tipo', 'estados_acciones')->where('valor_numero', $this->estado)->first();
            return '<span class="badge badge-'.$estado->valor_texto.'">'.$estado->nombre.'</span>';
        }
    }

    public function getActuacionAttribute(): String{
        $actuacion = ListasModel::where('tipo', 'actuacion_vee')->where('valor_numero', $this->id_actuacion)->first();
        return $actuacion->nombre;
    }

    public function getPadreAttribute(): Array{
        $padre = array();
        if (!is_null($this->id_padre)) {
            $temp = AccionesModel::where('id', $this->id_padre)->first();
            $padre = array(
                'titulo' => $temp->titulo,
                'cordis' => $temp->cordis
            );
        }
        return $padre;
    }

    public function getIdPTAttribute(){
        $id = null;
        $ptAcciones = PlaTAccionModel::where('id_accion', $this->id)->get();
        foreach ($ptAcciones as $item) {
            if($item->plantrabajo->vigente == true){
                $id = $item->plantrabajo->id;
            }
        }
        return $id;
    }

}
