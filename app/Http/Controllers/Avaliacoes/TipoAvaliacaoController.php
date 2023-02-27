<?php

namespace App\Http\Controllers\Avaliacoes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Constants\Permission;
use App\Models\Avaliacao\TipoAvaliacao;
use App\Models\Secretaria;

class TipoAvaliacaoController extends Controller
{
    public function listTipoAvaliacao()
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_AVALIACAO_LIST);
        if (auth()->user()->can(Permission::GERENCIAR_TIPO_AVALIACAO_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }

        $tipo_avaliacoes = TipoAvaliacao::query()
            ->when(request()->pesquisa, function ($query) {
                $query->where('nome', 'ilike', '%' . request()->pesquisa . '%');
            })
            ->when(
                in_array(request()->secretaria_pesq, $secretariasSearchSelect->pluck('id')->toArray()),
                function ($query) {
                    $query->where('secretaria_id', request()->secretaria_pesq);
                },
                function ($query) use ($secretariasSearchSelect) {
                    $query->whereIn('secretaria_id', $secretariasSearchSelect->pluck('id'));
                }
            )
            ->with('secretaria');

        $tipo_avaliacoes = $tipo_avaliacoes->orderBy('ativo', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->appends(
                ['pesquisa' => request()->pesquisa]
            );


        return view('admin.config.tipos-avaliacao.tipo-avaliacoes-listar', compact('tipo_avaliacoes', 'secretariasSearchSelect'));
    }

    public function createTipoAvaliacao()
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_AVALIACAO_CREATE);
        if (auth()->user()->can(Permission::GERENCIAR_TIPOS_AVALIACAO_CREATE_ANY_SECRETARIA)) {
            $secretarias = Secretaria::query();
        } else {
            $secretarias = auth()->user()->secretarias();
        };

        $secretarias = $secretarias
            ->where('ativo', true)
            ->orderBy('nome', 'asc')
            ->get();

        return view('admin.config.tipos-avaliacao.tipo-avaliacao-criar', compact('secretarias'));
    }

    public function storeTipoAvaliacao(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_AVALIACAO_CREATE);
        $request->validate([
            'nome' => 'required|string|max:255',
            'pergunta' => 'required|string',
            'inserir_automaticamente' => 'required|boolean',
            'secretaria' => 'required|int',
        ]);

        TipoAvaliacao::create([
            'ativo' => true,
            'nome' => $request->nome,
            'pergunta' => $request->pergunta,
            'default' => $request->inserir_automaticamente,
            'secretaria_id' => $request->secretaria,
        ]);

        return redirect()->route('get-tipo-avaliacao-list')->with('success', 'Tipo de Avaliação cadastrado com sucesso!');
    }

    public function viewTipoAvaliacao($id)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_AVALIACAO_VIEW);
        $tipo_avaliacao = TipoAvaliacao::find($id);
        return view('admin.config.tipos-avaliacao.tipo-avaliacao-visualizar', ['tipo_avaliacao' => $tipo_avaliacao]);
    }

    public function editTipoAvaliacao(TipoAvaliacao $tipoAvaliacao)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_AVALIACAO_EDIT);
        return view('admin.config.tipos-avaliacao.tipo-avaliacao-editar', compact('tipoAvaliacao'));
    }

    public function updateTipoAvaliacao(Request $request, TipoAvaliacao $tipoAvaliacao)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_AVALIACAO_EDIT);
        $request->validate([
            'nome' => 'required|string|max:255',
            'pergunta' => 'required|string',
            'inserir_automaticamente' => 'required|boolean',
        ]);

        $tipoAvaliacao->nome = $request->nome;
        $tipoAvaliacao->pergunta = $request->pergunta;
        $tipoAvaliacao->default =  $request->inserir_automaticamente;
        $tipoAvaliacao->ativo = true;
        $tipoAvaliacao->save();

        return redirect()->route('get-tipo-avaliacao-view', $tipoAvaliacao)->with('success', 'Atualizado com sucesso!');
    }

    public function deleteTipoAvaliacao(TipoAvaliacao  $tipoAvaliacao)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_AVALIACAO_DELETE);
        $tipoAvaliacao->delete();
        return redirect()->route('get-tipo-avaliacao-list')->with('success', 'Deletado com sucesso!');
    }

    public function toggleTipoAvaliacaoStatus($id)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_AVALIACAO_ACTIVE_TOGGLE);
        $tipo_avaliacao = TipoAvaliacao::find($id);
        $tipo_avaliacao->ativo = !$tipo_avaliacao->ativo;
        $tipo_avaliacao->save();

        if ($tipo_avaliacao->ativo) {
            return redirect()->route('get-tipo-avaliacao-list')->with('success', 'Tipo de avaliação ativada com sucesso!');
        } else {
            return redirect()->route('get-tipo-avaliacao-list')->with('success', 'Tipo de avaliação desativada com sucesso!');
        }
    }

    public function getTiposAvaliacaoSecretaria($secretariaId)
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_CREATE);
        $secretaria = Secretaria::find($secretariaId);

        if (!is_null($secretaria)) {
            $tiposAvaliacao = $secretaria->tiposAvaliacao()
                ->select(['id', 'nome', 'default'])
                ->where('ativo', true)
                ->get();

            return response()->json(
                [
                    'status' => true,
                    'data' => $tiposAvaliacao,
                    'message' => 'Sucesso!'
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'não foi possível encontrar a secretaria!'
                ]
            );
        }
    }
}
