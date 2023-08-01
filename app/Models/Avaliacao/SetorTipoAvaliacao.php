<?php

namespace App\Models\Avaliacao;

use CreateSetorTipoAvaliacaoTable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SetorTipoAvaliacao extends Model
{
    // use SoftDeletes;

    protected $table = "setor_tipo_avaliacao";

    protected $guarded = [];


    public function setor(): BelongsTo
    {
        return $this->belongsTo(Setor::class);
    }

    public function tiposAvaliacao(): BelongsTo
    {
        return $this->belongsTo(TipoAvaliacao::class);
    }

    public function avaliacoes(): EloquentCollection
    {
        return Avaliacao::where('setor_id', $this->setor_id)->where('tipo_avaliacao_id', $this->tipo_avaliacao_id)->get();
    }

    // public function getResumoFromCache(): Collection
    // {
    //     return Cache::rememberForever('Setor_Tipo_Avaliacao_' . $this->id, function () {
    //         $thisAvaliacoes = $this->avaliacoes();
    //         $setorTipoAvaliacaoNota = $thisAvaliacoes->avg('nota');
    //         if (!is_null($setorTipoAvaliacaoNota)) {
    //             if ($setorTipoAvaliacaoNota != $this->nota) {
    //                 $this->update([
    //                     'nota' => $setorTipoAvaliacaoNota
    //                 ]);
    //             }

    //             $notas1 = $thisAvaliacoes->where('nota', 2)->count();
    //             $notas2 = $thisAvaliacoes->where('nota', 4)->count();
    //             $notas3 = $thisAvaliacoes->where('nota', 6)->count();
    //             $notas4 = $thisAvaliacoes->where('nota', 8)->count();
    //             $notas5 = $thisAvaliacoes->where('nota', 10)->count();

    //             $qtdAvaliacoes = $notas1 + $notas2 + $notas3 + $notas4 + $notas5;

    //             return collect([
    //                 'qtd' => $qtdAvaliacoes,
    //                 'notas1' => $notas1,
    //                 'notas2' => $notas2,
    //                 'notas3' => $notas3,
    //                 'notas4' => $notas4,
    //                 'notas5' => $notas5,
    //             ]);
    //         } elseif (is_null($setorTipoAvaliacaoNota)) {
    //             return collect([
    //                 'qtd' => 0,
    //                 'notas1' => 0,
    //                 'notas2' => 0,
    //                 'notas3' => 0,
    //                 'notas4' => 0,
    //                 'notas5' => 0,
    //             ]);
    //         }
    //     });
    // }

    // public static function getResumoById($id): Collection
    // {
    //     $setorTipoAvaliacao = SetorTipoAvaliacao::find($id);
    //     return $setorTipoAvaliacao->getResumoFromCache();
    // }

    // public static function updateResumoCache(Avaliacao $avaliacao): void
    // {
    //     $setorTipoAvaliacao = SetorTipoAvaliacao::where('setor_id', $avaliacao->setor_id)
    //         ->where('tipo_avaliacao_id', $avaliacao->tipo_avaliacao_id)
    //         ->first();

    //     $setorTipoAvaliacaoCache =  Cache::get('Setor_Tipo_Avaliacao_' . $setorTipoAvaliacao->id);

    //     if (is_null($setorTipoAvaliacaoCache)) {
    //         $setorTipoAvaliacao->getResumoFromCache();
    //         return;
    //     } else {
    //         switch ($avaliacao->nota) {
    //             case 2:
    //                 $setorTipoAvaliacaoCache['notas1'] += 1;
    //                 break;
    //             case 4:
    //                 $setorTipoAvaliacaoCache['notas2'] += 1;
    //                 break;
    //             case 6:
    //                 $setorTipoAvaliacaoCache['notas3'] += 1;
    //                 break;
    //             case 8:
    //                 $setorTipoAvaliacaoCache['notas4'] += 1;
    //                 break;
    //             case 10:
    //                 $setorTipoAvaliacaoCache['notas5'] += 1;
    //                 break;
    //         }

    //         $media = (($setorTipoAvaliacaoCache['notas1'] * 2) + ($setorTipoAvaliacaoCache['notas2'] * 4) + ($setorTipoAvaliacaoCache['notas3'] * 6) + ($setorTipoAvaliacaoCache['notas4'] * 8) + ($setorTipoAvaliacaoCache['notas5'] * 10))
    //             / ($setorTipoAvaliacaoCache['notas1'] + $setorTipoAvaliacaoCache['notas2'] + $setorTipoAvaliacaoCache['notas3'] + $setorTipoAvaliacaoCache['notas4'] + $setorTipoAvaliacaoCache['notas5']);

    //         $setorTipoAvaliacaoCache['qtd'] += 1;

    //         $setorTipoAvaliacao->update([
    //             'nota' =>  $media
    //         ]);

    //         ::forget('Setor_Tipo_Avaliacao_' . $setorTipoAvaliacao->id);
    //         Cache::forever('Setor_Tipo_Avaliacao_' . $setorTipoAvaliacao->id, $setorTipoAvaliacaoCache);
    //     }
    // }



    public function getResumo(): Collection
    {
        $thisAvaliacoes = $this->avaliacoes();
        $setorTipoAvaliacaoNota = $thisAvaliacoes->avg('nota');

        if (!is_null($setorTipoAvaliacaoNota)) {
            if ($setorTipoAvaliacaoNota != $this->nota) {
                $this->update([
                    'nota' => $setorTipoAvaliacaoNota
                ]);
            }

            $notas1 = $thisAvaliacoes->where('nota', 2)->count();
            $notas2 = $thisAvaliacoes->where('nota', 4)->count();
            $notas3 = $thisAvaliacoes->where('nota', 6)->count();
            $notas4 = $thisAvaliacoes->where('nota', 8)->count();
            $notas5 = $thisAvaliacoes->where('nota', 10)->count();

            $qtdAvaliacoes = $notas1 + $notas2 + $notas3 + $notas4 + $notas5;

            return collect([
                'qtd' => $qtdAvaliacoes,
                'notas1' => $notas1,
                'notas2' => $notas2,
                'notas3' => $notas3,
                'notas4' => $notas4,
                'notas5' => $notas5,
            ]);
        } else {
            return collect([
                'qtd' => 0,
                'notas1' => 0,
                'notas2' => 0,
                'notas3' => 0,
                'notas4' => 0,
                'notas5' => 0,
            ]);
        }
    }

    public static function updateResumo(Avaliacao $avaliacao): void
    {
        $setorTipoAvaliacao = SetorTipoAvaliacao::where('setor_id', $avaliacao->setor_id)
            ->where('tipo_avaliacao_id', $avaliacao->tipo_avaliacao_id)
            ->first();

        $setorTipoAvaliacao->getResumo();

        $notas1 = $setorTipoAvaliacao->notas1 + ($avaliacao->nota == 2 ? 1 : 0);
        $notas2 = $setorTipoAvaliacao->notas2 + ($avaliacao->nota == 4 ? 1 : 0);
        $notas3 = $setorTipoAvaliacao->notas3 + ($avaliacao->nota == 6 ? 1 : 0);
        $notas4 = $setorTipoAvaliacao->notas4 + ($avaliacao->nota == 8 ? 1 : 0);
        $notas5 = $setorTipoAvaliacao->notas5 + ($avaliacao->nota == 10 ? 1 : 0);

        $media = (($notas1 * 2) + ($notas2 * 4) + ($notas3 * 6) + ($notas4 * 8) + ($notas5 * 10))
            / ($notas1 + $notas2 + $notas3 + $notas4 + $notas5);

        $setorTipoAvaliacao->update([
            'nota' => $media
        ]);
    }
}
