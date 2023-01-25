<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Models\Historico;
use App\Models\Manifestacoes;
use App\Models\Prorrogacao;
use App\Models\Situacao;
use Illuminate\Http\Request;

class ProrrogacaoController extends Controller
{
    public function create(Request $request, Manifestacoes $manifestacao)
    {
        $this->authorize(Permission::MANIFESTACAO_PRORROGACAO_REQUEST);
        $request->validate([
            'motivo_solicitacao' => 'required|string|max:255',
        ]);

        foreach ($manifestacao->prorrogacao as $prorrogacao) {
            if ($prorrogacao->situacao == Prorrogacao::SITUACAO_ESPERA) {
                return redirect()->route('get-view-manifestacao2', $manifestacao->id)->with('warning', 'Existe uma prorrogação pendente!');
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
            'etapas' => 'Solicitação de prorrogação de prazo de resposta!',
            'alternativo' => auth()->user()->name . ", Solicitou prorrogação de prazo de resposta para a Manifestação!",
            'created_at' => now()
        ]);

        return redirect()->route('get-view-manifestacao2', $manifestacao->id)->with('success', 'Pedido de prorrogação realizado com sucesso!');
    }

    public function responseProrrogacao(Request $request, Manifestacoes $manifestacao,  Prorrogacao $prorrogacao)
    {
        $this->authorize(Permission::MANIFESTACAO_PRORROGACAO_ACCEPT);

        $request->validate([
            'resposta' => 'required|string|max:255',
        ]);

        $prorrogacao->resposta = $request->resposta;

        if ($request->situacao == Prorrogacao::SITUACAO_APROVADO) {
            $prorrogacao->situacao = Prorrogacao::SITUACAO_APROVADO;
        } elseif ($request->situacao == Prorrogacao::SITUACAO_REPROVADO) {
            $prorrogacao->situacao = Prorrogacao::SITUACAO_REPROVADO;
        }

        $prorrogacao->update();

        $manifestacao = Manifestacoes::find($prorrogacao->manifestacao_id);

        $manifestacao->update([
            'situacao_id' => Situacao::where('nome', 'Respondida da Porrogação')->first()->id //respondido da prorrogaçao
        ]);

        $etapa = $prorrogacao->situacao == Prorrogacao::SITUACAO_APROVADO ?
            'A solicitação de prorrogação de prazo foi Aprovada!'
            :
            'A solicitação de prorrogação de prazo foi Reprovada!';

        $alternativo = $prorrogacao->situacao == Prorrogacao::SITUACAO_APROVADO
            ?
            auth()->user()->name . ', aprovou a solicitação de prorrogação de prazo!'
            :
            auth()->user()->name . ', negou a solicitação de prorrogação de prazo!';

        Historico::create([
            'manifestacao_id' => $prorrogacao->manifestacao_id,
            'etapas' => $etapa,
            'alternativo' => $alternativo,
            'created_at' => now()
        ]);

        return redirect()->route('get-view-manifestacao2', $manifestacao->id)->with('success', 'Resposta referente a prorrogação realizada com sucesso!');
    }
}
