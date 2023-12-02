<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemasImportModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'tema_principal',
        'acta',
        'tema_secundario'
    ];
}
