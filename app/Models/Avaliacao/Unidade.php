<?php

namespace App\Models\Avaliacao;

use App\Models\Secretaria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

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
        return $this->hasMany(Avaliacao::class, 'unidade_secr_id', 'id');
    }

    public static function getResumoUnidadeId($id): array
    {
        $unidade = Unidade::find($id);
        return $unidade->getResumoFromCache();
    }

    public function getResumoFromCache(): array
    {
        return
            Cache::rememberForever('Unidade_' . $this->id, function () {
                $this->update([
                    'nota' => $this->avaliacoes->avg('nota')
                ]);

                $notas1 = $this->avaliacoes->where('nota', 2)->count();
                $notas2 = $this->avaliacoes->where('nota', 4)->count();
                $notas3 = $this->avaliacoes->where('nota', 6)->count();
                $notas4 = $this->avaliacoes->where('nota', 8)->count();
                $notas5 = $this->avaliacoes->where('nota', 10)->count();

                $qtdAvaliacoes = $notas1 + $notas2 + $notas3 + $notas4 + $notas5;

                return [
                    'qtd' => $qtdAvaliacoes,
                    'notas1' => $notas1,
                    'notas2' => $notas2,
                    'notas3' => $notas3,
                    'notas4' => $notas4,
                    'notas5' => $notas5,
                ];
            });
    }

    public function updateResumoCache(Avaliacao $avaliacao): void
    {
        if (!is_null(Cache::get('Unidade_' . $avaliacao->unidade_secr_id))) {
            $unidadeInfo =  Cache::get('Unidade_' . $avaliacao->unidade_secr_id);

            switch ($avaliacao->nota) {
                case 2:
                    $unidadeInfo['notas1']++;
                    break;
                case 4:
                    $unidadeInfo['notas2']++;
                    break;
                case 6:
                    $unidadeInfo['notas3']++;
                    break;
                case 8:
                    $unidadeInfo['notas4']++;
                    break;
                case 10:
                    $unidadeInfo['notas5']++;
                    break;
            }

            $media = (($unidadeInfo['notas1'] * 2) + ($unidadeInfo['notas2'] * 4) + ($unidadeInfo['notas3'] * 6) + ($unidadeInfo['notas4'] * 8) + ($unidadeInfo['notas5'] * 10))
                / ($unidadeInfo['notas1'] + $unidadeInfo['notas2'] + $unidadeInfo['notas3'] + $unidadeInfo['notas4'] + $unidadeInfo['notas5']);

            $unidadeInfo['qtd']++;

            $this->update([
                'nota' =>  $media
            ]);

            Cache::forget('Unidade_' . $avaliacao->unidade_secr_id);
            Cache::forever('Unidade_' . $avaliacao->unidade_secr_id, $unidadeInfo);
        } else {
            $this->getResumoFromCache();
        }
    }
}
