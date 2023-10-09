<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadesModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_entidades';
    protected $fillable = [
        'nombre',
        'tipo',
        'activo'
    ];
    protected $guarded = ['id'];
}
