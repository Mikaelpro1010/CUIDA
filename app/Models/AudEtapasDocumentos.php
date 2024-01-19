<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudEtapasDocumentos extends Model
{
    protected $fillable = [
        'nome', 'icone', 'lado_timeline', 'cadastrado_por',
    ];
}
