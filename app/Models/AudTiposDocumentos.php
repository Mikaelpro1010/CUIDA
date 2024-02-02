<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudTiposDocumentos extends Model
{
    protected $fillable = [
        'nome', 'interno',
    ];

    // Relacionamento com o modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
