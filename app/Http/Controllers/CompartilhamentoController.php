<?php

namespace App\Http\Controllers;

use App\Models\Compartilhamento;
use App\Models\Historico;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompartilhamentoController extends Controller
{
    public function compartilharManifestacao(Request $request, $id)
    {
        $compartilhamento = new Compartilhamento();
        $request->validate([
                'secretaria_id' => 'required|integer',
                'texto_compartilhamento' => 'required|string|max:255',
            ]);

        $compartilhamento->secretaria_id = $request->secretaria_id;
        $compartilhamento->manifestacao_id = $id;
        $compartilhamento->texto_compartilhamento = $request->texto_compartilhamento;
        $compartilhamento->data_inicial = Carbon::now();

        $compartilhamento->save();

        Historico::create([
            'manifestacao_id' => $compartilhamento->manifestacao_id,
            'etapas' => 'A manifestação foi compartilhada!',
            'alternativo' => "A manifestação foi criada por ". auth()->user()->name ."!",
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Manifestacao compartilhada com sucesso!');
    }

    public function responderCompartilhamento(Request $request, Compartilhamento $compartilhamento){
        $request->validate([
            'resposta' => 'required|string|max:255',
        ]);

        $compartilhamento->resposta = $request->resposta;

        $compartilhamento->save();

        Historico::create([
            'manifestacao_id' => $compartilhamento->manifestacao_id,
            'etapas' => 'O compartilhamento da manifestação foi respondido!',
            'alternativo' => "A manifestação foi criada por ". auth()->user()->name ."!",
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Compartilhamento da manifestação respondido com sucesso!');
    }
}
