<?php

namespace App\Http\Controllers\Publico;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\Unidade;

class AvaliacoesController extends Controller
{
    public function listSetores($token)
    {
        $unidade = Unidade::where('token', $token)->with('setores', 'setores.tipoAvaliacao')->first();


        if (is_null($unidade)) {
            return redirect()->route('home');
        }

        return view('public.unidade_secr.list-setores', compact('unidade'));
    }

    public function viewAvaliacao($token)
    {
        $unidade = Unidade::where('token', $token)->with('tiposAvaliacao')->first();

        if (is_null($unidade)) {
            return redirect()->route('home');
        }

        return view('public.unidade_secr.avaliacao', compact('unidade'));
    }

    public function storeAvaliacao($token, Request $request)
    {
        $request->validate([
            'avaliacao' => 'required|integer|max:10|min:1',
            'comentario' => 'nullable|string',
            'tipo' => 'required|integer'
        ]);

        $unidade = Unidade::where('token', $token)->first();

        if (is_null($unidade) || $unidade->tiposAvaliacao->search($request->tipo)) {
            return response()->json([
                'status' => false,
            ]);
        }

        $avaliacao = Avaliacao::create([
            'nota'  => $request->avaliacao,
            'comentario'  => $request->comentario,
            'unidade_secr_id'  => $unidade->id,
            'tipos_avaliacao_id'  => $request->tipo,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }
}
