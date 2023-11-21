<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanGTextoModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_plang_textos';
    protected $fillable = [
        'id_accion',
        'tipo',
        'texto'
    ];
    protected $guarded = ['id'];
}
