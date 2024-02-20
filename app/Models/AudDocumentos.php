<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudDocumentos extends Model
{
    // Relação "Um para Um" com aud_etapas_documentos
    public function etapaDocumento()
    {
        return $this->belongsTo(AudEtapasDocumentos::class, 'aud_etapa_documento_id');
    }

    // Relação "Um para Um" com aud_tipos_documentos
    public function tipoDocumento()
    {
        return $this->belongsTo(AudTiposDocumentos::class, 'aud_tipo_documento_id');
    }

    // Relação "Um para Um" com aud_status_documentos
    public function statusDocumento()
    {
        return $this->belongsTo(AudStatusDocumentos::class, 'aud_status_documento_id');
    }

    // Relação "Um para Um" com aud_prazos_documentos
    public function prazosDocumento()
    {
        return $this->belongsTo(AudStatusDocumentos::class, 'aud_prazo_documento_id');
    }

    // Relação "Um para Um" com aud_secretarias
    public function Secretaria()
    {
        return $this->belongsTo(AudStatusDocumentos::class, 'aud_secretaria_id');
    }
}
