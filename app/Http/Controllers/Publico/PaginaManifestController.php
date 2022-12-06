<?php

namespace App\Http\Controllers\Publico;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EstadosProcesso;
use App\Models\Historico;
use App\Models\Manifestacoes;
use App\Models\Manifestacoes\Manifest;
use App\Models\Motivacao;
use App\Models\Situacao;
use App\Models\TiposManifestacao;
use Illuminate\Support\Facades\Validator;

class PaginaManifestController extends Controller
{
    public function visualizarPagina()
    {
        $tipos_manifestacao = TiposManifestacao::where('ativo', true)->get();
        return view('public.pagina-manifestacao', ['tipos_manifestacao' => $tipos_manifestacao]);
    }

    public function cadastrar(Request $request)
    {
        if ($request->anonimo == 0) {
            $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|nullable|string',
                'numero_telefone' => 'required|nullable|string',
                'endereco' => 'required|nullable|string',
                'bairro' => 'required|nullable|string',
                'manifestacao' => 'required|nullable|string',
                'tipo_manifestacao' => 'required|integer',

            ]);
        } else {
            $request->validate([
                'endereco' => 'required|nullable|string',
                'bairro' => 'required|nullable|string',
                'manifestacao' => 'required|nullable|string',
            ]);
        }
  
        do{ 
            $new_protocolo = random_int(10000, 99999);
            $new_senha = random_int(10000, 99999);
            
            $manifestacao = Manifestacoes::where('protocolo', $new_protocolo)->orWhere('senha', $new_senha)->first();
        }while(!is_null($manifestacao));
        

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
            $manifestacao->tipo_manifestacao_id = $request->tipo_manifestacao;
            $manifestacao->manifestacao = $request->manifestacao;
            $manifestacao->situacao_id = Situacao::where('nome', 'Aberta')->first()->id;
            $manifestacao->estado_processo_id = EstadosProcesso::where('nome', 'Inicial')->first()->id;
            $manifestacao->motivacao_id = Motivacao::where('nome', 'Manifestação')->first()->id;

        $manifestacao->save();

        $msg ='Manifestação cadastrada com sucesso, para poder consulta-la com as informações de  Protocolo: '.$manifestacao->protocolo.' e Senha: '.$manifestacao->senha.' preencha as mesmas no campo de acompanhar manifestação.';

        $historico = Historico::create([
            'manifestacao_id' => $manifestacao->id,
            'etapas' => 'A manifestação foi criada!',
            'created_at' => now()
        ]);

        return redirect()
            ->route('pagina-inicial')
            ->with('success', $msg);
       
    }

    public function visualizarManifestacao(Request $request){
            $request->validate([
                'protocolo' => 'required|digits:5',
                'senha' => 'required|digits:5',
            ]);

        $manifestacao = Manifestacoes::where('protocolo', $request->protocolo)->where('senha', $request->senha)->first();
        if(is_null($manifestacao)){
            return redirect()->back()->withErrors(['error'=>'não foi possível encontrar essa manifestação!']);
        } else{
            return view('public.vis-manifestacao',  compact('manifestacao'));
        }
        
    }
}
