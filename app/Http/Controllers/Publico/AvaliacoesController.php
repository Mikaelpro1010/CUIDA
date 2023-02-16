<?php

namespace App\Http\Controllers\Publico;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\Unidade;

class AvaliacoesController extends Controller
{
    public function listSetores($unidadeToken)
    {
        $unidade = Unidade::where('token', $unidadeToken)->with(['setores' => function ($query) {
            $query->where('ativo', true);
        }])->first();
        if (is_null($unidade)) {
            return redirect()->route('home');
        } else if ($unidade->setores->count() == 1) {
            return redirect()->route('get-view-avaliacao', $unidade->setores[0]->token);
        }

        return view('public.avaliacoes.list-setores', compact('unidade'));
    }

    public function viewAvaliacao($setorToken)
    {
        $setor = Setor::where('token', $setorToken)->with('unidade', 'tiposAvaliacao')->first();

        if(is_null($setor) || !$setor->ativo || !$setor->unidade->ativo || !$setor->unidade->secretaria->ativo ){
            return redirect()->route('home')->withErrors(['erro' => "Não é possivel avaliar pois a Unidade está desativada!"]);
        }

        return view('public.avaliacoes.avaliacao', compact('setor'));
    }

    public function storeAvaliacao($setorToken, Request $request)
    {
        $request->validate([
            'avaliacao' => 'required|integer|max:10|min:1',
            'comentario' => 'nullable|string',
            'tipo' => 'required|integer'
        ]);

        $setor = Setor::where('token', $setorToken)->first();

        if (is_null($setor) || $setor->tiposAvaliacao->search($request->tipo)) {
            return response()->json([
                'status' => false,
            ]);
        }

        $avaliacao = Avaliacao::create([
            'nota'  => $request->avaliacao,
            'comentario'  => $request->comentario,
            'setor_id'  => $setor->id,
            'tipo_avaliacao_id'  => $request->tipo,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }
}
