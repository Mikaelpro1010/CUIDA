<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\Unidade;
use Illuminate\Http\RedirectResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SetoresController extends Controller
{
    public function storeSetor(Request $request, Unidade $unidade)
    {
        $this->authorize(Permission::SETOR_CREATE);

        $request->validate([
            'nome' => 'required|string|max:255',
            'tipos_avaliacao' => 'required|array',
        ]);

        $tipos_avaliacao = $unidade->secretaria->tiposAvaliacao()->where('ativo', true)->pluck('id')->toArray();

        $tiposAvaliacao = [];
        foreach ($request->tipos_avaliacao as $tipo) {
            if (in_array($tipo, $tipos_avaliacao)) {
                $tiposAvaliacao[$tipo] = ['nota' => 0];
            }
        }
        if (count($tiposAvaliacao) > 0) {
            if ($unidade != null) {
                $setor = Setor::query()->create([
                    'unidade_id' => $unidade->id,
                    'nome' => $request->nome,
                    'token' => substr(bin2hex(random_bytes(50)), 1),
                    'ativo' => true,
                    'principal' => false,
                ]);
                $setor->tiposAvaliacao()->sync($tiposAvaliacao);
                return redirect()->route('get-unidades-secr-view', $unidade->id)->with(['success' => 'Setor Cadastrado com Sucesso!']);
            } else {
                return redirect()->route('get-unidades-secr-list')->withError(['unidade' => 'A unidade não existe!']);
            }
        } else {
            return redirect()->route('get-unidades-secr-view', $unidade->id)->withError(['tipos_avaliacao' => 'É preciso definir os tipos de avaliação!'])->withInput();
        }
    }

    public function updateSetor(Request $request, Setor $setor)
    {
        $this->authorize(Permission::SETOR_EDIT);

        $request->validate([
            'nome' => 'required|string|max:255',
            'tipos_avaliacao' => 'required|array',
        ]);

        if ($setor != null) {
            $tipos_avaliacao = $setor->unidade->secretaria->tiposAvaliacao()->where('ativo', true)->pluck('id')->toArray();

            $tiposAvaliacao = [];
            foreach ($request->tipos_avaliacao as $tipo) {
                if (in_array($tipo, $tipos_avaliacao)) {
                    $tiposAvaliacao[$tipo] = ['nota' => 0];
                }
            }

            $setor->nome = $request->nome;
            $setor->ativo = true;
            $setor->save();

            $setor->tiposAvaliacao()->sync($tiposAvaliacao);

            return redirect()
                ->route('get-unidades-secr-view', $setor->unidade_id)
                ->with(['success' => "Setor $setor->nome editado com Sucesso!"]);
        }
        return redirect()->back()->withError(['setor' => 'O setor não existe!']);
    }

    public function deleteSetor(Setor $setor)
    {
        $this->authorize(Permission::SETOR_DELETE);
        $setor->delete();
        return redirect()
            ->route('get-unidades-secr-view', $setor->unidade_id)
            ->with(['success' => 'Setor deletado com Sucesso!']);
    }

    public function ativarDesativar(Setor $setor): RedirectResponse
    {
        $this->authorize(Permission::SETOR_TOGGLE_ATIVO);
        if (!$setor->principal) {
            $setor->ativo = !$setor->ativo;
            $setor->save();
        } else {
            return redirect()->route('get-unidades-secr-view', $setor->unidade_id)->withErrors(['erro' => "Não é Possivel desativar o setor principal de uma Unidade!"]);
        }

        return redirect()->route('get-unidades-secr-view', $setor->unidade_id)->with(['success' => "Setor " . ($setor->ativo ? "Ativado" : "Desativado") . " com Sucesso!"]);
    }

    public function getTiposAvaliacaoSetor($setor): Setor
    {
        return Setor::with(['tiposAvaliacao' => function ($query) {
            $query->select('tipo_avaliacoes.id', 'tipo_avaliacoes.nome')
                ->where('ativo', true);
        }])
            ->select('id', 'nome')
            ->find($setor);
    }

    public function gerarQrcode(Setor $setor)
    {
        $qrcode = QrCode::size(500)->generate(route('get-view-avaliacao', $setor->token));
        // $qrcode = `<a href="` . route('get-view-avaliacao', $setor->token) . `">url</a>`;
        $unidade = $setor->unidade;
        return view('admin.avaliacoes.qrcode-view', compact('unidade', 'setor', 'qrcode'));
    }
}
