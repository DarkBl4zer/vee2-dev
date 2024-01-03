<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeclaracionesModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_declaraciones';
    protected $fillable = [
        'previa',
        'id_accion',
        'id_usuario',
        'tipo_usuario',
        'firmado',
        'lugar_expedicion',
        'funcionario',
        'id_profesion',
        'cargo',
        'contrato',
        'conflicto',
        'explicacion',
        'activo',
        'archivo_firmado',
        'motivo_rechazo'
    ];
    protected $guarded = ['id'];
    protected $appends = ['usuario', 'profesion'];

    public function accion() : HasOne {
        return $this->hasOne(AccionesModel::class, 'id', 'id_accion');
    }

    public function tablas(): HasMany {
        return $this->hasMany(DeclaracionTablaModel::class, 'id_declaracion', 'id');
    }

    public function getProfesionAttribute(): String{
        $profesion = '';
        if (!is_null($this->id_profesion)) {
            $profesion = ListasModel::where('tipo', 'profesiones')->where('valor_numero', $this->id_profesion)->first()->nombre;
        }
        return $profesion;
    }

    public function getUsuarioAttribute(): Array{
        $usuario = UsuariosModel::where('id', $this->id_usuario)->first();
        return array(
            'nombre' => $usuario->nombre,
            'cedula' => $usuario->cedula,
            'firma' => (isset($usuario->firmas[0]))?$usuario->firmas[0]->cnv_firma:'Firma'
        );
    }
}
