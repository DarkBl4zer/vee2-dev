<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlanesGestionModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_planes_gestion';
    protected $fillable = [
        'id_accion',
        'id_delegada',
        'pdf_ob_es',
        'pdf_met',
        'pdf_mue',
        'pdf_ctx',
        'pdf_info',
        'estado',
        'activo',
        'archivo_firmado',
        'archivo_cronograma',
        'archivo_acta',
        'original_acta',
        'fecha_informe'
    ];

    protected $guarded = ['id'];
    protected $appends = ['accion', 'declaraciones', 'delegada', 'fechas', 'nombreestado', 'finforme'];

    public function cronograma() : HasMany {
        return $this->hasMany(CronogramaModel::class, 'id_accion', 'id_accion')->orderBy('vee2_cronogramas.id_etapa', 'asc');
    }

    public function textos(): HasMany {
        return $this->hasMany(PlanGTextoModel::class, 'id_accion', 'id_accion')->orderBy('vee2_plang_textos.tipo', 'asc');
    }

    public function getAccionAttribute(): Array{
        $accion = AccionesModel::where('id', $this->id_accion)->first();
        return array(
            'numero' => $accion->numero,
            'titulo' => $accion->titulo,
            'entidades' => $accion->entidades['string'],
            'objetivo_general' => $accion->objetivo_general
        );
    }

    public function getDeclaracionesAttribute(): Array{
        $declaraciones = DeclaracionesModel::where('id_accion', $this->id_accion)->where('tipo_usuario', 'FUNCIONARIO')->get();
        $funcionarios = [];
        foreach ($declaraciones as $item) {
            array_push($funcionarios, array(
                'nombre' => $item->usuario['nombre'],
                'firma' => $item->usuario['firma'],
                'profesion' => $item->profesion,
                'cargo' => $item->cargo,
                'firmado' => ($item->firmado)?true:false,
            ));
        }
        return $funcionarios;
    }

    public function getDelegadaAttribute(): String{
        return DelegadasModel::where('id', $this->id_delegada)->first()->nombre;
    }

    public function getFechasAttribute(): String{
        $fechas = "Creado: ".$this->created_at->format('d/m/Y h:m:s a')."<br>";
        $fechas .= "Actualizado: ".$this->updated_at->format('d/m/Y h:m:s a');
        return $fechas;
    }

    public function getNombreestadoAttribute(): String{
        $estado = ListasModel::where('tipo', 'estados_acciones')->where('valor_numero', $this->estado)->first();
        return '<span class="badge badge-'.$estado->valor_texto.'">'.$estado->nombre.'</span>';
    }

    public function getFinformeAttribute(): String{
        $finforme = Carbon::createFromFormat('Y-m-d h:m:s', $this->fecha_informe)->format('d/m/Y');
        return $finforme;
    }

}
