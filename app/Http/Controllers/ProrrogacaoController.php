<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use App\Models\Manifestacoes;
use App\Models\Prorrogacao;
use App\Models\Situacao;
use Illuminate\Http\Request;

class ProrrogacaoController extends Controller
{
    public function create(Request $request, Manifestacoes $manifestacao)
    {
        $request->validate([
            'motivo_solicitacao' => 'required|string|max:255',
        ]);

        foreach ($manifestacao->prorrogacao as $prorrogacao) {
            if ($prorrogacao->situacao == Prorrogacao::SITUACAO_ESPERA) {
                return redirect()->route('visualizarManifest', $manifestacao->id)->with('warning', 'Existe uma prorrogação pendente!');
            }
        }

        $prorrogacao = new Prorrogacao();
        $prorrogacao->motivo_solicitacao = $request->motivo_solicitacao;
        $prorrogacao->user_id = auth()->user()->id;
        $prorrogacao->manifestacao_id = $manifestacao->id;
        $prorrogacao->situacao = Prorrogacao::SITUACAO_ESPERA;
        $prorrogacao->save();

        $manifestacao->situacao_id = Situacao::where('nome', 'Aguardando Porrogação')->first()->id;
        $manifestacao->update();

        Historico::create([
            'manifestacao_id' => $prorrogacao->manifestacao_id,
            'etapas' => 'Houve uma prorrogação relacionada a manifestação!',
            'alternativo' => "A manifestação foi criada por ". auth()->user()->name ."!",
            'created_at' => now()
        ]);

        return redirect()->route('visualizarManifest', $manifestacao->id)->with('success', 'Pedido de prorrogação realizado com sucesso!');
    }

    public function responseProrrogacao(Request $request, Manifestacoes $manifestacao,  Prorrogacao $prorrogacao){

        // dd($request->all(), $prorrogacao);

        $prorrogacao->resposta = $request->resposta;

        if($request->situacao == Prorrogacao::SITUACAO_APROVADO){
            $prorrogacao->situacao = Prorrogacao::SITUACAO_APROVADO;
        }
        elseif($request->situacao == Prorrogacao::SITUACAO_REPROVADO){
            $prorrogacao->situacao = Prorrogacao::SITUACAO_REPROVADO;
        }
        
        $prorrogacao->update();

        Historico::create([
            'manifestacao_id' => $prorrogacao->manifestacao_id,
            'etapas' => 'A prorrogação relacionada a manifestação foi respondido!',
            'alternativo' => "A manifestação foi criada por ". auth()->user()->name ."!",
            'created_at' => now()
        ]);

        return redirect()->route('visualizarManifest', $manifestacao->id)->with('success', 'Resposta referente a prorrogação realizada com sucesso!');
    }
}
