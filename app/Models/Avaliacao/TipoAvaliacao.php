<?php

namespace App\Models\Avaliacao;

use App\Models\Secretaria;
use Illuminate\Database\Eloquent\Builder;
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

    public function setor(): BelongsToMany  // TODO: verificar se é BelongsToMany ou BelongsTo
    {
        return $this->belongsToMany(Setor::class); // TODO: verificar se é Setor ou SetorTipoAvaliacao
    }

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class);  // secretaria_id fa
    }

    public function setorTipoAvaliacao(): HasMany
    {
        return $this->hasMany(SetorTipoAvaliacao::class);
    }

    public function scopeAtivo(Builder $query): Builder        //  TODO: verificar se é Builder ou \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('ativo', true);
    }
}
