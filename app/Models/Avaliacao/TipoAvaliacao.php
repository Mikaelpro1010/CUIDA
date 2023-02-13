<?php

namespace App\Models\Avaliacao;

use App\Models\Secretaria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAvaliacao extends Model
{
    use SoftDeletes;

    protected $table = "tipo_avaliacoes";

    protected $guarded = [];

    public function setor(): BelongsToMany
    {
        return $this->belongsToMany(Setor::class);
    }

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class);
    }

    public function setorTipoAvaliacao(): HasMany
    {
        return $this->hasMany(SetorTipoAvaliacao::class);
    }
}
