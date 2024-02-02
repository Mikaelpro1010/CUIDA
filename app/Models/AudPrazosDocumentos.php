<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudPrazosDocumentos extends Model
{
    protected $fillable = [
        'nome',
    ];

    // Relacionamento com o modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
