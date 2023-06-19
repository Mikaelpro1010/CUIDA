<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Unidade;

class RelatoriosUnidadeController extends Controller
{
    public function relatorio($unidade_id)
    {
        $unidade = Unidade::with(['setores' =>  function ($query) {
            $query->withCount([
                'avaliacoes as notas_10' => function ($query) {
                    $query->resumeFilter(request()->tipos_avaliacao_pesq, request()->mes_pesq, request()->ano_pesq);
                    $query->where('nota', 10);
                },
                'avaliacoes as notas_8' => function ($query) {
                    $query->resumeFilter(request()->tipos_avaliacao_pesq, request()->mes_pesq, request()->ano_pesq);
                    $query->where('nota', 8);
                },
                'avaliacoes as notas_6' => function ($query) {
                    $query->resumeFilter(request()->tipos_avaliacao_pesq, request()->mes_pesq, request()->ano_pesq);
                    $query->where('nota', 6);
                },
                'avaliacoes as notas_4' => function ($query) {
                    $query->resumeFilter(request()->tipos_avaliacao_pesq, request()->mes_pesq, request()->ano_pesq);
                    $query->where('nota', 4);
                },
                'avaliacoes as notas_2' => function ($query) {
                    $query->resumeFilter(request()->tipos_avaliacao_pesq, request()->mes_pesq, request()->ano_pesq);
                    $query->where('nota', 2);
                }
            ]);
        }, 'secretaria', 'secretaria.tiposAvaliacao' => function ($query) {
            $query->ativo();
        }])
            ->find($unidade_id);

        $unidade->userCanAccess();

        $setores = [];
        $totalAvaliacoes = 0;
        foreach ($unidade->setores as $setor) {
            $total = $setor->notas_2 + $setor->notas_4 + $setor->notas_6 + $setor->notas_8 + $setor->notas_10;  // Total de avaliações por setor
            $totalAvaliacoes += $total;
            $setores[$setor->id] = [
                'nome' => $setor->nome, //  setor  
                'total' => $total,
                2 => $setor->notas_2,
                4 => $setor->notas_4,
                6 => $setor->notas_6,
                8 => $setor->notas_8,
                10 => $setor->notas_10,
            ];
        }

        return view('admin.avaliacoes.unidades-secr.unidade-relatorio', compact('setores', 'unidade', 'totalAvaliacoes'));
    }
}
