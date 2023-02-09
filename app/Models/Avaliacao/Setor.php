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

    public function getResumoFromCache(): Collection
    {
        return Cache::rememberForever('Setor_' . $this->id, function () {
            $setorNota = $this->avaliacoes()->avg('nota');
            if (!is_null($setorNota)) {
                if ($setorNota != $this->nota) {
                    $this->update([
                        'nota' => $setorNota
                    ]);
                }

                $notas1 = $this->avaliacoes->where('nota', 2)->count();
                $notas2 = $this->avaliacoes->where('nota', 4)->count();
                $notas3 = $this->avaliacoes->where('nota', 6)->count();
                $notas4 = $this->avaliacoes->where('nota', 8)->count();
                $notas5 = $this->avaliacoes->where('nota', 10)->count();

                $qtdAvaliacoes = $notas1 + $notas2 + $notas3 + $notas4 + $notas5;

                return collect([
                    'qtd' => $qtdAvaliacoes,
                    'notas1' => $notas1,
                    'notas2' => $notas2,
                    'notas3' => $notas3,
                    'notas4' => $notas4,
                    'notas5' => $notas5,
                ]);
            } elseif (is_null($setorNota)) {
                return collect([
                    'qtd' => 0,
                    'notas1' => 0,
                    'notas2' => 0,
                    'notas3' => 0,
                    'notas4' => 0,
                    'notas5' => 0,
                ]);
            }
        });
    }

    public static function getResumoById($id): Collection
    {
        $setor = Setor::find($id);
        return $setor->getResumoFromCache();
    }
}
