<?php

namespace App\Http\Controllers\Gerenciar;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Secretaria;

class SecretariasController extends Controller
{
    public function listSecretarias()
    {
        $this->authorize(Permission::GERENCIAR_SECRETARIAS_LIST);
        $secretarias = Secretaria::query()
            ->when(request()->pesquisa, function ($query) {
                $query->where('nome', 'ilike', "%" . request()->pesquisa . "%")
                    ->orWhere('sigla', 'ilike', "%" . request()->pesquisa . "%");
            })
            ->orderBy('ativo', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->appends(
                ['pesquisa' => request()->pesquisa]
            );

        return view('admin.gerenciar.secretarias.secretarias-listar', compact('secretarias'));
    }

    public function viewSecretaria(Secretaria $secretaria)
    {
        $this->authorize(Permission::GERENCIAR_SECRETARIAS_VIEW);

        return view('admin.gerenciar.secretarias.secretaria-visualizar', compact('secretaria'));
    }

    public function createSecretaria()
    {
        $this->authorize(Permission::GERENCIAR_SECRETARIAS_CREATE);
        return view('admin.gerenciar.secretarias.secretaria-criar');
    }

    public function storeSecretaria(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_SECRETARIAS_CREATE);
        $request->validate([
            'sigla' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
        ]);

        Secretaria::create([
            'nome' => $request->nome,
            'sigla' => $request->sigla,
            'ativo' => true,
        ]);

        return redirect()->route('get-secretarias-list')->with(['success' => 'Secretaria cadastrada com Sucesso!']);
    }

    public function editSecretaria(Secretaria $secretaria)
    {
        $this->authorize(Permission::GERENCIAR_SECRETARIAS_EDIT);
        return view('admin.gerenciar.secretarias.secretaria-editar', compact('secretaria'));
    }

    public function updateSecretaria(Request $request, Secretaria $secretaria)
    {
        $this->authorize(Permission::GERENCIAR_SECRETARIAS_EDIT);
        $request->validate([
            'sigla' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
        ]);

        $secretaria->update([
            'sigla' => $request->sigla,
            'nome' => $request->nome,
        ]);

        return redirect()->route('get-secretarias-list')->with(['success' => 'Secretaria atualizada com sucesso!']);
    }

    public function toggleSecretariaStatus(Secretaria $secretaria)
    {
        $this->authorize(Permission::GERENCIAR_SECRETARIAS_ACTIVE_TOGGLE);
        $secretaria->ativo = !$secretaria->ativo;
        $secretaria->save();

        return redirect()->route('get-secretarias-list')->with(['success' => "Secretaria " . ($secretaria->ativo ? "Ativada" : "Desativada") . " com Sucesso!"]);
    }
}
