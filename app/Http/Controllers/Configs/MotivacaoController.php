<?php

namespace App\Http\Controllers\Configs;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Motivacao;
use Illuminate\Support\Facades\Validator;

class MotivacaoController extends Controller
{
    public function listMotivacao()
    {
        $this->authorize(Permission::GERENCIAR_MOTIVACOES_LIST);
        $motivacoes = Motivacao::query()
            ->when(request()->pesquisa, function($query){
                $query->where('nome', 'like', "%". request()->pesquisa."%");
            })  
            ->orderBy('ativo', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->appends(
                ['pesquisa'=>request()->pesquisa]
            );

            return view('admin.config.motivacao.motivacao-listar', compact('motivacoes'));
    }

    public function createMotivacao()
    {
        $this->authorize(Permission::GERENCIAR_MOTIVACOES_CREATE);
        return view('admin.config.motivacao.motivacao-criar');
    }

    public function storeMotivacao(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_MOTIVACOES_CREATE);
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

        $motivacao = new Motivacao();

        $motivacao->ativo = true;
        $motivacao->nome = $request->nome;
        $motivacao->descricao = $request->descricao;

        $motivacao->save();

        return redirect()->route('get-motivacao-list');
    }

    public function viewMotivacao($id)
    {

        $this->authorize(Permission::GERENCIAR_MOTIVACOES_VIEW);
        $motivacao = Motivacao::find($id);
        return view('admin.config.motivacao.motivacao-visualizar', ['motivacao' => $motivacao]);
    }

    public function editMotivacao(Motivacao $Motivacao)
    {
        $this->authorize(Permission::GERENCIAR_MOTIVACOES_EDIT);
        return view('admin.config.motivacao.motivacao-editar', compact('Motivacao'));
    }
    
    public function updateMotivacao(Request $request, Motivacao $Motivacao)
    {
        $this->authorize(Permission::GERENCIAR_MOTIVACOES_EDIT);
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $Motivacao->nome = $request->nome;
        $Motivacao->descricao = $request->descricao;
        $Motivacao->save();

        return redirect()->route('get-motivacao-list', $Motivacao->id)->with('mensagem', 'Atualizado com sucesso!');
    }

    public function deleteMotivacao(Motivacao  $Motivacao)
    {
        $this->authorize(Permission::GERENCIAR_MOTIVACOES_DELETE);
        $Motivacao->delete();
        return redirect()->route('get-motivacao-list')->with('mensagem', 'Deletado com sucesso!');
    }

    public function toggleMotivacaoStatus($id)
    {
        $motivacoes = Motivacao::find($id);
        $motivacoes->ativo = !$motivacoes->ativo;
        $motivacoes->save();

        return redirect()->route('get-motivacao-list');
    }
}
