<?php

namespace App\Models\Avaliacao;

use App\Models\Secretaria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Unidade de uma Secretaria
 */
class Unidade extends Model
{
    protected $table = "unidades_secr";
    protected $guarded = [];

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class, 'secretaria_id', 'id');
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'unidade_id', 'id');
    }
}
