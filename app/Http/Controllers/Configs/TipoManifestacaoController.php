<?php

namespace App\Http\Controllers\Configs;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TiposManifestacao;
use Illuminate\Support\Facades\Validator;

class TipoManifestacaoController extends Controller
{
    public function listTipoManifestacao()
    {


        // $this->authorize(Permission::GERENCIAR_TIPOS_MANIFESTACAO_LIST);

        $tipo_manifestacoes = TiposManifestacao::query()
            ->when(request()->pesquisa, function ($query) {
                $query->where('nome', 'like', "%" . request()->pesquisa . "%")
                    ->orWhere('descricao', 'like', "%" . request()->pesquisa . "%");
            })
            ->orderBy('ativo', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->appends(
                ['pesquisa' => request()->pesquisa]
            );

        return view('admin.config.tipo-manifestacao.tipo-manifestacao-listar', compact('tipo_manifestacoes'));
    }

    public function createTipoManifestacao()
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_MANIFESTACAO_CREATE);
        return view('admin.config.tipo-manifestacao.tipo-manifestacao-criar');
    }

    public function storeTipoManifestacao(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_MANIFESTACAO_CREATE);
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $tipoManifestacao = new TiposManifestacao();

        $tipoManifestacao->ativo = true;
        $tipoManifestacao->nome = $request->nome;
        $tipoManifestacao->descricao = $request->descricao;

        $tipoManifestacao->save();

        return redirect()->route('get-tipo-manifestacao-list');
    }

    public function viewTipoManifestacao(TiposManifestacao $tipoManifestacao)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_MANIFESTACAO_VIEW);

        return view('admin.config.tipo-manifestacao.tipo-manifestacao-visualizar', compact('tipoManifestacao'));
    }

    public function editTipoManifestacao(TiposManifestacao $tipoManifestacao)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_MANIFESTACAO_EDIT);
        return view('admin.config.tipo-manifestacao.tipo-manifestacao-editar', compact('tipoManifestacao'));
    }

    public function updateTipoManifestacao(Request $request, TiposManifestacao $tipoManifestacao)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_MANIFESTACAO_EDIT);
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $tipoManifestacao->nome = $request->nome;
        $tipoManifestacao->descricao = $request->descricao;
        $tipoManifestacao->save();

        return redirect()->route('get-tipo-manifestacao-list', $tipoManifestacao->id)->with('mensagem', 'Atualizado com sucesso!');
    }

    public function deleteTipoManifestacao(TiposManifestacao $tipoManifestacao)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_MANIFESTACAO_DELETE);
        $tipoManifestacao->delete();
        return redirect()->route('get-tipo-manifestacao-list')->with('success', 'Deletado com sucesso!');
    }

    public function toggleTipoManifestacaoStatus($id)
    {
        $tipo_manifestacao = TiposManifestacao::find($id);
        $tipo_manifestacao->ativo = !$tipo_manifestacao->ativo;
        $tipo_manifestacao->save();

        return redirect()->route('get-tipo-manifestacao-list');
    }
}
