<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

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
        'archivo_acta',
        'original_acta',
        'vigente',
        'id_delegado',
        'fecha_delegado',
        'id_coordinador',
        'fecha_coordinador'
    ];
    protected $guarded = ['id'];
    protected $appends = ['str_acciones', 'fechas', 'nombreestado', 'delegada', 'rechazos'];

    public function acciones() : BelongsToMany {
        return $this->belongsToMany(AccionesModel::class, 'vee2_plan_t_accion', 'id_plantrabajo', 'id_accion');
    }

    public function getStrAccionesAttribute(): String{
        $pt_acc = PlaTAccionModel::where('id_plantrabajo', $this->id)->get();
        $string = '<ul style="padding-left: 15px;">';
        foreach ($pt_acc as $item) {
            $string .= '<li>['.$item->accion->numero.'] '.Str::limit($item->accion->titulo, 150, ' (...)').' ';
            $string .= '<i class="fas fa-stamp" data-toggle="tooltip" data-placement="top" title="Documentos" onclick="DocumentosAccion('.$item->accion->id.');" style="font-size: 14px;"></i>';
            $string .= '<i class="fas fa-file-invoice" data-toggle="tooltip" data-placement="top" title="Ver detalle" onclick="VerDetalle('.$item->accion->id.');" style="font-size: 14px;"></i></li>';
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
        $retorno  = '<span class="badge badge-danger">RECHAZADO</span>';
        $rechazos = RechazosPtModel::where('id_plant', $this->id)->where('activo', true)->count();
        if ($rechazos == 0) {
            $estado = ListasModel::where('tipo', 'estados_plant')->where('valor_numero', $this->estado)->first();
            $retorno = '<span class="badge badge-'.$estado->valor_texto.'">'.$estado->nombre.'</span>';
        }
        return $retorno;
    }

    public function getDelegadaAttribute(): Array{
        $delegada = DelegadasModel::where('id', $this->id_delegada)->first();
        return array(
            'nombre' => $delegada->nombre,
            'tipo' => $delegada->tipo
        );
    }

    public function getRechazosAttribute(): Array{
        $rechazos = RechazosPtModel::where('id_plant', $this->id)->count();
        $activos = RechazosPtModel::where('id_plant', $this->id)->where('activo', true)->count();
        return array(
            ($rechazos>0)?true:false,
            ($activos>0)?true:false
        );
    }

    public function delegado() : HasOne {
        return $this->hasOne(UsuariosModel::class, 'id', 'id_delegado');
    }

    public function coordinador() : HasOne {
        return $this->hasOne(UsuariosModel::class, 'id', 'id_coordinador');
    }

}
