<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Models\Compartilhamento;
use App\Models\Historico;
use App\Models\Secretaria;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompartilhamentoController extends Controller
{
    public function compartilharManifestacao(Request $request, $id)
    {
        $this->authorize(Permission::MANIFESTACAO_COMPARTILHAMENTO_CREATE);

        $request->validate([
            'secretaria_id' => 'required|integer',
            'texto_compartilhamento' => 'required|string|max:255',
        ]);


        $secretaria = Secretaria::find($request->secretaria_id);

        if (is_null($secretaria)) {
            return redirect()
                ->route('get-view-manifestacao2', $id)
                ->withErrors(['secretaria' => 'Não Foi possivel encontrar a secretaria selecionada para o compartilhamento!']);
        }

        $compartilhamento = Compartilhamento::create([
            'secretaria_id' => $request->secretaria_id,
            'manifestacao_id' => $id,
            'texto_compartilhamento' => $request->texto_compartilhamento,
            'data_inicial' => Carbon::now(),
        ]);

        Historico::create([
            'manifestacao_id' => $compartilhamento->manifestacao_id,
            'etapas' => 'A manifestação foi compartilhada com a secretaria responsável!',
            'alternativo' => auth()->user()->name . ", Compartilhou a Manifestação com a " . $secretaria->nome . " - " . $secretaria->sigla . "!",
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Manifestacao compartilhada com sucesso!');
    }

    public function responderCompartilhamento(Request $request, Compartilhamento $compartilhamento)
    {
        $this->authorize(Permission::MANIFESTACAO_COMPARTILHAMENTO_REPLY);

        $request->validate([
            'resposta' => 'required|string|max:255',
        ]);

        $compartilhamento->update([
            'resposta' => $request->resposta,
        ]);

        Historico::create([
            'manifestacao_id' => $compartilhamento->manifestacao_id,
            'etapas' => 'O compartilhamento da manifestação foi respondido!',
            'alternativo' => "O compartilhamento foi respondido por " . auth()->user()->name . "!",
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Compartilhamento da manifestação respondido com sucesso!');
    }
}
