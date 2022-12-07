<?php

namespace App\Http\Controllers;

use App\Models\Manifestacoes;
use App\Models\Prorrogacao;
use Illuminate\Http\Request;

class ProrrogacaoController extends Controller
{
    public function create(Request $request, $id)
    {
        $request->validate([
            'motivo_solicitacao' => 'required|nullable|string',
            // 'max' => 'required|string|max:255',
        ]);

        $manifestacao = Manifestacoes::find($request->id);

        if ($manifestacao == null) {
            return redirect()
                ->route('visualizar-manifestacao')
                ->withErrors(['Erro' => 'Manifestacao nao existe']);
        } else {
            $prorrogacao = new Prorrogacao();
            $prorrogacao->motivo_solicitacao = $request->motivo_solicitacao;
            $prorrogacao->user_id = auth()->user()->id;
            $prorrogacao->manifestacao_id = $manifestacao->id;
            $prorrogacao->situacao = Prorrogacao::SITUACAO_ESPERA;
            $prorrogacao->save();
            return redirect()->route('visualizarManifest', $prorrogacao->id)->with('mensagem', 'Pedido de prorrogação realizado com sucesso!');
        }
    }
}
