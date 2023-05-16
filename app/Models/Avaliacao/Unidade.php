<?php

namespace App\Models\Avaliacao;

use App\Constants\Permission;
use App\Models\Secretaria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Unidade de uma Secretaria
 */
class Unidade extends Model
{
    protected $table = "unidades";
    protected $guarded = [];

    public function userCanAccess()
    {
        if (auth()->user()->cant(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $abort = true;
            foreach (auth()->user()->secretarias as $secretaria) {
                if ($secretaria->is($this->secretaria)) {
                    $abort = false;
                    break;
                }
            }

            abort_if($abort, Response::HTTP_FORBIDDEN);
        }
    }

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

    public function avaliacoes(): HasManyThrough
    {
        return $this->hasManyThrough(Avaliacao::class, Setor::class, 'unidade_id', 'setor_id');
    }

    /**
     * Query Scope apenas para as Unidades ativas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAtivo($query)
    {
        return $query->where('ativo', 1);
    }

    public function getResumoAllSetores(): Collection
    {
        $response = [];
        foreach ($this->setores()->ativo()->get() as $key => $setor) {
            $response[$key] = $setor->getResumoFromCache();
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

        if ($resumoUnidade['qtd'] > 0) {
            $nota = ($resumoUnidade['notas1'] * 2 + $resumoUnidade['notas2'] * 4 + $resumoUnidade['notas3'] * 6 + $resumoUnidade['notas4'] * 8 + $resumoUnidade['notas5'] * 10) /
                $resumoUnidade['qtd'];
        } else {
            $nota = 0;
        }

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
        return collect($cache);
        // return collect([
        //     'qtd' => 0,
        //     'notas1' => 0,
        //     'notas2' => 0,
        //     'notas3' => 0,
        //     'notas4' => 0,
        //     'notas5' => 0,
        // ]);

        // if ($cache['qtd'] != $this->getResumoAllSetores()->sum('qtd')) {
        //     Cache::forget('Unidade_' . $this->id);
        //     return Cache::forever('Unidade_' . $this->id, $this->getResumo());
        // } else {
        //     return $cache;
        // }
    }

    public static function getResumoById($unidade_id): Collection
    {
        $unidade = Unidade::find($unidade_id);
        return $unidade->getResumoFromCache();
    }
}
