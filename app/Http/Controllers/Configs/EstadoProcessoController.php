<?php

namespace App\Http\Controllers\Configs;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EstadosProcesso;
use Illuminate\Support\Facades\Validator;

class EstadoProcessoController extends Controller
{


    public function listEstadoProcesso()
    {
        $this->authorize(Permission::GERENCIAR_ESTADOS_PROCESSO_LIST);
        $estados_processo = EstadosProcesso::query()
            ->when(request()->pesquisa, function($query){
                $query->where('nome', 'like', "%". request()->pesquisa."%");
            })  
            ->orderBy('ativo', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->appends(
                ['pesquisa'=>request()->pesquisa]
            );

            return view('admin.config.estados-processo.estado-processo-listar', compact('estados_processo'));
    }

    public function createEstadoProcesso()
    {
        $this->authorize(Permission::GERENCIAR_ESTADOS_PROCESSO_CREATE);
        return view('admin.config.estados-processo.estado-processo-criar');
    }

    public function storeEstadoProcesso(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_ESTADOS_PROCESSO_CREATE);
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

        $estado_processo = new EstadosProcesso();

        $estado_processo->ativo = true;
        $estado_processo->nome = $request->nome;
        $estado_processo->descricao = $request->descricao;

        $estado_processo->save();

        return redirect()->route('get-estado-processo-list')->with('success', 'Estado do Processo cadastrado com sucesso!');
    }

    public function viewEstadoProcesso($id)
    {

        $this->authorize(Permission::GERENCIAR_ESTADOS_PROCESSO_VIEW);
        $estado_processo = EstadosProcesso::find($id);
        return view('admin.config.estados-processo.estado-processo-visualizar', ['estado_processo' => $estado_processo]);
    }

    public function editEstadoProcesso(EstadosProcesso $estadoProcesso)
    {
        $this->authorize(Permission::GERENCIAR_ESTADOS_PROCESSO_EDIT);
        return view('admin.config.estados-processo.estado-processo-editar', compact('estadoProcesso'));
    }
    
    public function updateEstadoProcesso(Request $request, EstadosProcesso $estadoProcesso)
    {
        $this->authorize(Permission::GERENCIAR_ESTADOS_PROCESSO_EDIT);
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $estadoProcesso->nome = $request->nome;
        $estadoProcesso->descricao = $request->descricao;
        $estadoProcesso->save();

        return redirect()->route('get-estado-processo-list', $estadoProcesso->id)->with('success', 'Atualizado com sucesso!');
    }

    public function deleteEstadoProcesso(EstadosProcesso  $estadoProcesso)
    {
        $this->authorize(Permission::GERENCIAR_ESTADOS_PROCESSO_DELETE);
        $estadoProcesso->delete();
        return redirect()->route('get-estado-processo-list')->with('success', 'Deletado com sucesso!');
    }

    public function toggleEstadoProcessoStatus($id)
    {
        $estado_processos = EstadosProcesso::find($id);
        $estado_processos->ativo = !$estado_processos->ativo;
        $estado_processos->save();

        if($estado_processos->ativo){
            return redirect()->route('get-estado-processo-list')->with('success', 'Estado do Processo ativado com sucesso!');
        } else{
            return redirect()->route('get-estado-processo-list')->with('success', 'Estado do Processo desativado com sucesso!');
        }
    }
}
