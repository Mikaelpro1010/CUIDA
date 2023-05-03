<?php

namespace App\Http\Controllers\Avaliacoes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Unidade;

class RelatoriosUnidadeController extends Controller
{
    public function relatorio($unidade_id)
    {
        $unidade = Unidade::with(['setores', 'setores.avaliacoes' =>  function ($query) {

            $query->when(request()->mes_pesq, function ($query) {
                $query->whereMonth('avaliacoes.created_at', request()->mes_pesq);
               
            });
            $query->when(request()->ano_pesq, function ($query) {
                $query->whereYear('avaliacoes.created_at', request()->ano_pesq);
               
            });
        }, 'secretaria'])

            

            // ->when(request()->ano_pesq, function ($query) {
            //     $query->whereYear('created_at', request()->ano_pesq);
            // })
            // ->orderBy('ativo', 'desc')
            // ->orderBy('updated_at', 'desc')
            ->find($unidade_id);
        // dd($unidade);


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
