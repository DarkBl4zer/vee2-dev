<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListasModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_listas';
    protected $fillable = [
        'tipo',
        'nombre',
        'valor_texto',
        'valor_numero',
        'tipo_valor',
        'activo'
    ];
    protected $guarded = ['id'];
    protected $appends = ['tvalorn'];

    public function getTvalornAttribute(): String{
        return ListasModel::where('id', $this->tipo_valor)->first()->nombre;
    }
}
