<?php

namespace App\Models\Avaliacao;

use App\Constants\Permission;
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

    public function scopeSecretariaWithoutPermissionCheck(Builder $query, $secretaria = []): Builder
    {
        return
            $query->whereHas('setor', function ($query) use ($secretaria) {
                $query->whereHas('unidade', function ($query) use ($secretaria) {
                    $query->whereIn('secretaria_id', $secretaria)
                        ->ativo();
                })->ativo();
            });
    }

    public function scopeSecretaria(Builder $query, $secretaria_id = null): Builder
    {
        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            if ($secretaria_id == null) {
                return $query;
            }
            return $query->secretariaWithoutPermissionCheck([$secretaria_id]);
        } else {
            $secretarias = auth()->user()->secretarias->pluck('id');
            if ($secretaria_id != null && in_array($secretaria_id, $secretarias->toArray())) {
                return $query->secretariaWithoutPermissionCheck([$secretaria_id]);
            } else {
                return $query->secretariaWithoutPermissionCheck($secretarias);
            }
        }
    }

    public function scopeSetor(Builder $query, $setor_id = null): Builder
    {
        return $query->where('setor_id', $setor_id);
    }
}
