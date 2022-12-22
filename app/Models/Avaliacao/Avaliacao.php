<?php

namespace App\Models\Avaliacao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Avaliacao extends Model
{
    protected $table = "avaliacoes";
    protected $guarded = [];

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class, 'unidade_secr_id', 'id');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoAvaliacao::class, 'tipos_avaliacao_id');
    }
}
