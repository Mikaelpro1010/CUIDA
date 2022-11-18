<?php

namespace App\Http\Controllers\Publico;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Unidade;

class AvaliacoesController extends Controller
{

    public function viewAvaliacao($token)
    {
        $unidade = Unidade::where('token', $token)->first();

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
        ]);

        $unidade = Unidade::where('token', $token)->first();

        if (is_null($unidade)) {
            return redirect()->back()->withErrors(['unidade' => 'Unidade não encontrada!']);
        }

        $avaliacao = Avaliacao::create([
            'nota'  => $request->avaliacao,
            'comentario'  => $request->comentario,
            'unidade_secr_id'  => $unidade->id,
        ]);

        return redirect()->route('agradecimento-avaliacao')->with(["success" => 'Avaliação cadastrada com sucesso!']);
    }
}
