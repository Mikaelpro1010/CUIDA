<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudTiposDocumentos extends Model
{
    protected $fillable = [
        'nome', 'interno',
    ];
}
