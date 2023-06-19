<?php

namespace App\Models\Avaliacao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Setor extends Model
{
    use SoftDeletes;

    protected $table = "setores";

    protected $guarded = [];

    public function tiposAvaliacao(): BelongsToMany
    {
        return $this->belongsToMany(TipoAvaliacao::class);
    }

    public function setorTipoAvaliacao(): HasMany
    {
        return $this->hasMany(SetorTipoAvaliacao::class);
    }

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class);
    }

    /**
     * Query Scope apenas para os setores ativos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAtivo($query)
    {
        return $query->where('ativo', 1);
    }

    public function getResumoAllTiposAvaliacao(): Collection
    {
        $response = [];
        foreach ($this->setorTipoAvaliacao as $key => $tipo) {
            $response[$key] = $tipo->getResumo();
        }

        return collect($response);
    }

    public function getResumoSetorTiposAvaliacaoById($tipoAvaliacaoId): Collection
    {
        return $this->setorTipoAvaliacao()->where('tipo_avaliacao_id', $tipoAvaliacaoId)->first()->getResumo();
    }

    public function getResumo(): Collection
    {
        $setorTiposAvaliacao = $this->getResumoAllTiposAvaliacao();
        $resumoSetor = collect([
            'qtd' => $setorTiposAvaliacao->sum('qtd'),
            'notas1' => $setorTiposAvaliacao->sum('notas1'),
            'notas2' => $setorTiposAvaliacao->sum('notas2'),
            'notas3' => $setorTiposAvaliacao->sum('notas3'),
            'notas4' => $setorTiposAvaliacao->sum('notas4'),
            'notas5' => $setorTiposAvaliacao->sum('notas5'),
        ]);

        if ($resumoSetor['qtd'] > 0) {
            $nota = ($resumoSetor['notas1'] * 2 + $resumoSetor['notas2'] * 4 + $resumoSetor['notas3'] * 6 + $resumoSetor['notas4'] * 8 + $resumoSetor['notas5'] * 10) /
                $resumoSetor['qtd'];
        } else {
            $nota = 0;
        }

        $this->update([
            'nota' => $nota
        ]);

        return $resumoSetor;
    }


    public static function getResumoById($id): Collection
    {
        $setor = Setor::find($id);
        return $setor->getResumo();
    }
}
