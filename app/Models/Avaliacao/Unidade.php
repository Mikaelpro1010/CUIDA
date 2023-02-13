<?php

namespace App\Models\Avaliacao;

use App\Models\Secretaria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Unidade de uma Secretaria
 */
class Unidade extends Model
{
    protected $table = "unidades";
    protected $guarded = [];

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class, 'secretaria_id', 'id');
    }

    public function setores(): HasMany
    {
        return $this->hasMany(Setor::class, 'unidade_id');
    }

    public function tiposAvaliacao(): BelongsToMany
    {
        return $this->belongsToMany(TipoAvaliacao::class)->withTrashed();
    }

    public function getResumoAllSetores(): Collection
    {
        $response = [];
        foreach ($this->setores()->where('ativo', true)->get() as $key => $tipo) {
            $response[$key] = $tipo->getResumoFromCache();
        }

        return collect($response);
    }

    public function getResumoSetorById($setorId): Collection
    {
        return $this->setores()->find($setorId)->getResumoFromCache();
    }

    public function getResumo(): Collection
    {
        $resumoAllSetores = $this->getResumoAllSetores();
        $resumoUnidade = collect([
            'qtd' => $resumoAllSetores->sum('qtd'),
            'notas1' => $resumoAllSetores->sum('notas1'),
            'notas2' => $resumoAllSetores->sum('notas2'),
            'notas3' => $resumoAllSetores->sum('notas3'),
            'notas4' => $resumoAllSetores->sum('notas4'),
            'notas5' => $resumoAllSetores->sum('notas5'),
        ]);

        $nota = $resumoUnidade['notas1'] * 2 + $resumoUnidade['notas2'] * 4 + $resumoUnidade['notas3'] * 6 + $resumoUnidade['notas4'] * 8 + $resumoUnidade['notas5'] * 10 /
            ($resumoUnidade['qtd']);

        $this->update([
            'nota' => $nota
        ]);

        return $resumoUnidade;
    }

    public function getResumoFromCache(): Collection
    {
        $cache = Cache::rememberForever('Unidade_' . $this->id, function () {
            return $this->getResumo();
        });

        if ($cache['qtd'] != $this->getResumoAllSetores()->sum('qtd')) {
            Cache::forget('Unidade_' . $this->id);
            return Cache::forever('Unidade_' . $this->id, $this->getResumo());
        } else {
            return $cache;
        }
    }

    public static function getResumoById($unidade_id): Collection
    {
        $unidade = Unidade::find($unidade_id);
        return $unidade->getResumoFromCache();
    }
}
