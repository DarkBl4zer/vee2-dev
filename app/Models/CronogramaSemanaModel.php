<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronogramaSemanaModel extends Model
{
    use HasFactory;
    protected $table = 'vee2_cronograma_semana';
    protected $fillable = [
        'id_cronograma',
        'semana'
    ];
    protected $guarded = ['id'];
}
