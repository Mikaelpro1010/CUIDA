<?php

namespace App\Models\Avaliacao;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avaliacao extends Model
{
    protected $table = "avaliacoes";
    protected $guarded = [];

    public function setor(): BelongsTo
    {
        return $this->belongsTo(Setor::class);
    }

    public function tipoAvaliacao(): BelongsTo
    {
        return $this->belongsTo(TipoAvaliacao::class);
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoAvaliacao::class, 'tipo_avaliacao_id');
    }

    public function scopeTipoAvaliacao(Builder $query, $tipoAvaliacaoId): Builder
    {
        return $query->where('tipo_avaliacao_id', $tipoAvaliacaoId);
    }

    public function scopeMes(Builder $query, $mes): Builder
    {
        return $query->whereMonth('created_at', $mes);
    }

    public function scopeAno(Builder $query, $ano): Builder
    {
        return $query->whereYear('created_at', $ano);
    }

    public function scopeResumeFilter(Builder $query, $tipoAvaliacaoId = null, $mes = null, $ano = null): Builder
    {
        $ano = $ano ?? now()->format('Y');

        return $query
            ->when($tipoAvaliacaoId, function ($query) use ($tipoAvaliacaoId) {
                $query->tipoAvaliacao($tipoAvaliacaoId);
            })
            ->when($mes, function ($query) use ($mes) {
                $query->mes($mes);
            })
            ->ano($ano);
    }
}
