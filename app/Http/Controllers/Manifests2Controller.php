<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Models\Compartilhamento;
use App\Models\EstadosProcesso;
use App\Models\Historico;
use App\Models\Manifest\Recurso;
use App\Models\Manifestacoes;
use App\Models\Motivacao;
use App\Models\Secretaria;
use App\Models\Situacao;
use App\Models\TiposManifestacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Manifests2Controller extends Controller
{
    public function list(Request $request)
    {
        $this->authorize(Permission::MANIFESTACAO_LIST);
        $manifestacoes = Manifestacoes::with('autor')
            ->when(is_numeric(request()->protocolo), function ($query) {
                $query->where('protocolo', request()->protocolo);
            })
            ->when(request()->data_inicio, function ($query) {
                $query->where('created_at', '>=', request()->data_inicio);
            })
            ->when(request()->data_fim, function ($query) {
                $query->where('updated_at', '<=', Carbon::parse(request()->data_fim)->addDay());
            })
            ->when(request()->motivacao, function ($query) {
                $query->where('motivacao_id', request()->motivacao);
            })
            ->when(request()->tipo, function ($query) {
                $query->where('tipo_manifestacao_id', request()->tipo);
            })
            ->when(request()->situacao, function ($query) {
                $query->where('situacao_id', request()->situacao);
            })
            ->when(request()->estado_processo, function ($query) {
                $query->where('estado_processo_id', request()->estado_processo);
            });

        $manifestacoes =
            $manifestacoes
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends([
                "protocolo" => request()->protocolo,
                "data_inicio" => request()->data_inicio,
                "data_fim" => request()->data_fim,
                "motivacao" => request()->motivacao,
                "tipo" => request()->tipo,
                "situacao" => request()->situacao,
                "estado_processo" => request()->estado_processo,
            ]);

        $resposta = [
            "manifestacoes" => $manifestacoes,
            // "aguardandoResposta" => $aguardandoResposta,
            // "respondido" => $respondido,
            // "encerrado" => $encerrado,
            // "totalCanaisMsg" => $totalCanaisMsg,
        ];

        return view('admin.manifestacoes.manifestacoes-listar', $resposta);
    }

    public function create()
    {
        $this->authorize(Permission::MANIFESTACAO_CREATE);

        $tipos_manifestacao = TiposManifestacao::where('ativo', true)->get();

        $situacoes = Situacao::where('ativo', true)->get();

        $estados_processo = EstadosProcesso::where('ativo', true)->get();

        $motivacoes = Motivacao::where('ativo', true)->get();
        return view('admin.manifestacoes.vis-cadastro-manifest', compact('tipos_manifestacao', 'situacoes', 'estados_processo', 'motivacoes'));
    }

    public function storeManifest(Request $request)
    {
        $this->authorize(Permission::MANIFESTACAO_CREATE);

        if ($request->anonimo == 0) {
            $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|string',
                'numero_telefone' => 'required|string',
                'endereco' => 'required|string',
                'bairro' => 'required|string',
                'manifestacao' => 'required|string',
                'tipo_manifestacao' => 'required|integer',
                'situacao' => 'required|integer',
                'estado_processo' => 'required|integer',
                'motivacao' => 'required|integer',
            ]);
        } else {
            $request->validate([
                'endereco' => 'required|string',
                'bairro' => 'required|string',
                'manifestacao' => 'required|string',
            ]);
        }

        do {
            $new_protocolo = random_int(10000, 99999);
            $new_senha = random_int(10000, 99999);

            $manifestacao = Manifestacoes::where('protocolo', $new_protocolo)->orWhere('senha', $new_senha)->first();
        } while (!is_null($manifestacao));

        $manifestacao = new Manifestacoes();

        $manifestacao->protocolo = $new_protocolo;
        $manifestacao->senha = $new_senha;

        if ($request->anonimo == 0) {
            $manifestacao->nome = $request->nome;
            $manifestacao->email = $request->email;
            $manifestacao->numero_telefone = $request->numero_telefone;
            $manifestacao->tipo_telefone = $request->tipo_telefone;
        }

        $manifestacao->anonimo = $request->anonimo;
        $manifestacao->endereco = $request->endereco;
        $manifestacao->bairro = $request->bairro;
        $manifestacao->manifestacao = $request->manifestacao;

        $manifestacao->tipo_manifestacao_id = $request->tipo_manifestacao;
        $manifestacao->situacao_id = $request->situacao;
        $manifestacao->estado_processo_id = $request->estado_processo;
        $manifestacao->motivacao_id = $request->motivacao;


        if ($request->has('anexo')) {
            foreach ($request->file('anexo') as $file) {
                $uploadedFile = $file;
                $filename = time() . $uploadedFile->getClientOriginalName();
                // $caminho = ;

                Storage::disk('local')->putFileAs(
                    // $caminho,
                    $uploadedFile,
                    $filename
                );
            };
        }

        $manifestacao->save();

        $historico = Historico::create([
            'manifestacao_id' => $manifestacao->id,
            'etapas' => 'A manifestação foi criada!',
            'alternativo' => "A manifestação foi criada por " . auth()->user()->name . "!",
            'created_at' => now()
        ]);

        return redirect()->route('get-list-manifestacoes2')->with('success', 'Usuario cadastrado com sucesso');
    }

    public function viewManifest($id)
    {
        $this->authorize(Permission::MANIFESTACAO_VIEW);

        $manifestacao = Manifestacoes::with('recursos', 'compartilhamento')->find($id);

        $podeCriarCompartilhamento = false;

        if ($manifestacao->compartilhamento->count() == 0 || $manifestacao->compartilhamento->where('resposta', null)->isEmpty()) {
            $podeCriarCompartilhamento = true;
        }

        $secretarias = Secretaria::query()->orderBy('updated_at', 'desc')->get();

        $situacaoAguardandoProrrogacao = Situacao::where('nome', 'Aguardando Porrogação')->first()->id;


        return view('admin.manifestacoes.visualizarManifestacoes', compact('manifestacao', 'situacaoAguardandoProrrogacao', 'secretarias', 'podeCriarCompartilhamento'));
    }

    public function responderRecurso(Request $request, Manifestacoes $manifestacao, Recurso $recurso)
    {
        $this->authorize(Permission::MANIFESTACAO_RECURSOS_REPLY);

        $request->validate([
            'respostaRecurso' => 'required|string|max:255',
        ]);


        $recurso->resposta = $request->respostaRecurso;
        $recurso->autor_resposta = auth()->user()->id;
        $recurso->data_resposta = now();

        $recurso->update();

        // Historico::create([
        //     'manifestacao_id' => $manifestacao_id->id,
        //     'etapas' => 'O recurso relacionado a manifestação foi respondido!',
        //     'alternativo' => "O recurso relacionado a manifestação foi respondido por ". auth()->user()->name ."!",
        //     'created_at' => now(),
        // ]);

        return redirect()->route('get-list-manifestacoes2', $manifestacao->id)->with('success', 'Resposta referente ao recurso realizada com sucesso!');
        // return redirect()->back()->with('success', 'Resposta referente ao recurso realizada com sucesso!');
    }
}
