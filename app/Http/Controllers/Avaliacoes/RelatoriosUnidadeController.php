<?php

namespace App\Http\Controllers\Avaliacoes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Unidade;

class RelatoriosUnidadeController extends Controller
{
    public function relatorio($unidade_id)
    {
        $unidade = Unidade::with('setores', 'setores.avaliacoes', 'secretaria')->find($unidade_id);

        $setores = [];
        $totalAvaliacoes = 0;
        foreach ($unidade->setores as $setor) {
            $total = $setor->avaliacoes->count();
            $totalAvaliacoes += $total;
            $setores[$setor->id] = [
                'nome' => $setor->nome,
                'total' => $total,
                2 => $setor->avaliacoes->where('nota', 2)->count(),
                4 => $setor->avaliacoes->where('nota', 4)->count(),
                6 => $setor->avaliacoes->where('nota', 6)->count(),
                8 => $setor->avaliacoes->where('nota', 8)->count(),
                10 => $setor->avaliacoes->where('nota', 10)->count(),
            ];
        }
        return view('admin.avaliacoes.unidades-secr.unidade-relatorio', compact('setores', 'unidade', 'totalAvaliacoes'));
    }
}
