<?php

namespace App\Http\Controllers;

use App\Models\EstadosProcesso;
use App\Models\Manifestacoes;
use App\Models\Motivacao;
use App\Models\Situacao;
use App\Models\TiposManifestacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Manifests2Controller extends Controller
{
    public function list(Request $request){
        $manifestacoes = Manifestacoes::query()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            return view('admin.manifestacoes.listagem', compact('manifestacoes'));
    }

    public function create()
    {
        $tipos_manifestacao = TiposManifestacao::where('ativo', true)->get();

        $situacoes = Situacao::where('ativo', true)->get();

        $estados_processo = EstadosProcesso::where('ativo', true)->get();

        $motivacoes = Motivacao::where('ativo', true)->get();
        return view('admin.manifestacoes.vis-cadastro-manifest', compact('tipos_manifestacao', 'situacoes', 'estados_processo', 'motivacoes'));
    }

    public function storeManifest(Request $request)
    {
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

        if($request->anonimo == 0){
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
        

        if($request->has('anexo')){
            foreach($request->file('anexo') as $file){
                $uploadedFile = $file;
                $filename = time(). $uploadedFile->getClientOriginalName();
                // $caminho = ;

                Storage::disk('local')->putFileAs(
                    // $caminho,
                    $uploadedFile,
                    $filename
                );

            };
        }

        $manifestacao->save();

        return redirect()->route('manifestacoes2')->with('mensagem', 'Usuario cadastrado com sucesso');
    }
}
