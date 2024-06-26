<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AudEtapasDocumentos extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'nome', 'icone', 'lado_timeline', 'usuario_id',
    ];

    // Relacionamento com o modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relação "Um para Um" com aud_documentos
    public function documento()
    {
        return $this->hasOne(AudDocumentos::class, 'aud_etapa_documento_id');
    }
}
