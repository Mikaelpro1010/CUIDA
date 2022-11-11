<?php

namespace App\Http\Controllers\Configs;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Situacao;
use Illuminate\Support\Facades\Validator;

class SituacaoController extends Controller
{
    public function listSituacao()
    {
        $this->authorize(Permission::GERENCIAR_SITUACOES_LIST);
        $situacoes = Situacao::query()
            ->when(request()->pesquisa, function($query){
                $query->where('nome', 'like', "%". request()->pesquisa."%");
            })  
            ->orderBy('ativo', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->appends(
                ['pesquisa'=>request()->pesquisa]
            );

            return view('admin.config.situacao.situacao-listar', compact('situacoes'));
    }

    public function createSituacao()
    {
        $this->authorize(Permission::GERENCIAR_SITUACOES_CREATE);
        return view('admin.config.situacao.situacao-criar');
    }

    public function storeSituacao(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_SITUACOES_CREATE);
        $validator = Validator::make(
            $request->only(['nome', 'descricao']),
            [
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string',
            ],
            [
                'nome.required' => 'É necessário inserir um nome!',
                'max' => 'Quantidade de caracteres ultrapassada, o nome deve ter menos que 254 caracteres!',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $situacao = new Situacao();

        $situacao->ativo = true;
        $situacao->nome = $request->nome;
        $situacao->descricao = $request->descricao;

        $situacao->save();

        return redirect()->route('get-situacao-list');
    }

    public function viewSituacao($id)
    {

        $this->authorize(Permission::GERENCIAR_SITUACOES_VIEW);
        $situacao = Situacao::find($id);
        return view('admin.config.situacao.situacao-visualizar', ['situacao' => $situacao]);
    }

    public function editSituacao(Situacao $Situacao)
    {
        $this->authorize(Permission::GERENCIAR_MOTIVACOES_EDIT);
        return view('admin.config.situacao.situacao-editar', compact('Situacao'));
    }
    
    public function updateSituacao(Request $request, Situacao $Situacao)
    {
        $this->authorize(Permission::GERENCIAR_MOTIVACOES_EDIT);
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $Situacao->nome = $request->nome;
        $Situacao->descricao = $request->descricao;
        $Situacao->save();

        return redirect()->route('get-situacao-list', $Situacao->id)->with('mensagem', 'Atualizado com sucesso!');
    }

    public function deleteSituacao(Situacao  $Situacao)
    {
        $this->authorize(Permission::GERENCIAR_MOTIVACOES_DELETE);
        $Situacao->delete();
        return redirect()->route('get-situacao-list')->with('mensagem', 'Deletado com sucesso!');
    }

    public function toggleSituacaoStatus($id)
    {
        $situacoes = Situacao::find($id);
        $situacoes->ativo = !$situacoes->ativo;
        $situacoes->save();

        return redirect()->route('get-situacao-list');
    }
}
