<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\TipoAvaliacao;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UnidadeSecrController extends Controller
{
    public function listagem()
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_LIST);
        $ativo = ['ativo' => 1, 'inativo' => 0];

        $unidades = Unidade::query()->with('secretaria')
            ->when(request()->pesquisa, function ($query) {
                $query->where('nome', 'like',  "%" . request()->pesquisa . "%");
            })
            ->when(
                request()->secretaria_pesq,
                function ($query) {
                    if (
                        auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA) ||
                        in_array(request()->secretaria_pesq, auth()->user()->secretarias->pluck('id')->toArray())
                    ) {
                        $query->where('secretaria_id', request()->secretaria_pesq);
                    } else {
                        $query->whereIn('secretaria_id', auth()->user()->secretarias->pluck('id'));
                    }
                },
                function ($query) {
                    if (auth()->user()->cant(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
                        $query->whereIn('secretaria_id', auth()->user()->secretarias->pluck('id'));
                    }
                }
            )
            ->when(request()->situacao, function ($query) use ($ativo) {
                $query->where('ativo', $ativo[request()->situacao]);
            })
            ->orderBy('ativo', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends([
                'pesquisa' => request()->pesquisa,
                'secretaria' => request()->secretaria_pesq,
                'situacao' => request()->situacao,
            ]);

        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }

        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_CREATE_ANY)) {
            $secretariasCreateSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasCreateSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }
        return view('admin.avaliacoes.unidades-secr.unidades-listagem', compact('unidades', 'secretariasSearchSelect', 'secretariasCreateSelect'));
    }

    public function createUnidade()
    {
        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_CREATE_ANY)) {
            $secretarias = Secretaria::query();
        } else {
            $secretarias = auth()->user()->secretarias();
        };

        $secretarias = $secretarias->with('unidades')->orderBy('nome', 'asc')->get();

        $tipos_avaliacao = TipoAvaliacao::where('ativo', 1)->get();

        return view('admin.avaliacoes.unidades-secr.unidades-criar', compact('tipos_avaliacao', 'secretarias'));
    }

    public function storeUnidade(Request $request)
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_CREATE);

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'secretaria' => 'required|int',
            'tipos_avaliacao' => 'required|array',
        ]);


        if (
            (auth()->user()->cant(Permission::UNIDADE_SECRETARIA_CREATE_ANY) &&
                !in_array($request->secretaria, auth()->user()->secretarias->pluck('id')->toArray()))
            || is_null(Secretaria::find($request->secretaria))
        ) {
            return redirect()->back()->withErrors(['secretaria' => 'Não foi possível identificar a Secretaria!'])->withInput();
        }
        $tipos_avaliacao = TipoAvaliacao::where('ativo', true);

        $tiposAvaliacao = [];
        foreach ($request->tipos_avaliacao as $tipo) {
            if (in_array($tipo, $tipos_avaliacao->pluck('id')->toArray())) {
                $tiposAvaliacao[$tipo] = ['nota' => 0];
            }
        }
        if (count($tiposAvaliacao) > 0) {

            $unidade = Unidade::query()->create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'secretaria_id' => $request->secretaria,
                'nota' => 0,
                'ativo' => true,
                'token' => substr(bin2hex(random_bytes(50)), 1),
            ]);

            $unidade->tiposAvaliacao()->sync($tiposAvaliacao);

            return redirect()->route('get-unidades-secr-list')->with(['success' => 'Unidade Cadastrada com Sucesso!']);
        } else {
            return redirect()->back()->withError(['tipos_avaliacao' => 'É preciso definir os tipos de avaliação!'])->withInput();
        }
    }

    public function visualizar($unidade)
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_VIEW);
        $unidadeObj = Unidade::with('tiposAvaliacao')->find($unidade);
        // $qrcode = QrCode::size(200)->generate('http://10.0.49.0:9000/avaliacoes/' . $unidadeObj->token . '/avaliar');
        $qrcode = QrCode::size(200)->generate(route('get-view-avaliacao', $unidadeObj->token));
        // dd($unidade, $unidadeObj);
        return view('admin.avaliacoes.unidades-secr.unidades-visualizar', compact('unidadeObj', 'qrcode'));
    }

    public function editUnidade(Unidade $unidade)
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_EDIT);
        $tipos_avaliacao = TipoAvaliacao::where('ativo', 1)->get();

        return view('admin.avaliacoes.unidades-secr.unidades-editar', compact('unidade', 'tipos_avaliacao'));
    }

    public function updateUnidade(Unidade $unidade, Request $request)
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_UPDATE);

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipos_avaliacao' => 'required|array',
        ]);

        $tipos_avaliacao = TipoAvaliacao::where('ativo', true);

        $tiposAvaliacao = [];
        foreach ($request->tipos_avaliacao as $tipo) {
            if (in_array($tipo, $tipos_avaliacao->pluck('id')->toArray())) {
                $tiposAvaliacao[$tipo] = ['nota' => 0];
            }
        }
        if (count($tiposAvaliacao) > 0) {
            $unidade->nome = $request->nome;
            $unidade->descricao = $request->descricao;
            $unidade->ativo = true;
            $unidade->save();

            $unidade->tiposAvaliacao()->sync($tiposAvaliacao);

            return redirect()
                ->route('get-unidades-secr-view', compact('unidade'))
                ->with(['success' => 'Unidade editada com Sucesso!']);
        } else {
            return redirect()
                ->back()
                ->withError(['tipos_avaliacao' => 'É preciso definir os tipos de avaliação!'])
                ->withInput();
        }
    }

    public function ativarDesativar(Unidade $unidade)
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_TOGGLE_ATIVO);
        $unidade->ativo = !$unidade->ativo;
        $unidade->save();

        return redirect()->route('unidades-secr-list');
    }

    public function gerarQrcode(Unidade $unidade)
    {
        $baseUrl = env('APP_URL');
        $qrcode = QrCode::size(500)->generate($baseUrl . '/avaliacoes/' . $unidade->token . '/avaliar');

        return view('admin.avaliacoes.unidades-secr.qrcode-view', compact('unidade', 'qrcode'));
    }
}
