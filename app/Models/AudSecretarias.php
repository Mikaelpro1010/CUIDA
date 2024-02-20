<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudSecretarias extends Model
{
    // Relacionamento com o modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relação "Um para Um" com aud_documentos
    public function documento()
    {
        return $this->hasOne(AudDocumentos::class, 'aud_secretaria_id');
    }
}
