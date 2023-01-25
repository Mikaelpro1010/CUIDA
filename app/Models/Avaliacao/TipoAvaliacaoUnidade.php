<?php

namespace App\Models\Avaliacao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoAvaliacaoUnidade extends Model
{
    protected $table = 'tipo_avaliacao_unidade';

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }

    public function tipoAvaliacao(): BelongsTo
    {
        return $this->belongsTo(TipoAvaliacao::class);
    }
}
