<?php

namespace App\Http\Controllers\avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use Illuminate\Support\Facades\Validator;
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

    public function novaUnidade(Request $request)
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_CREATE);

        $rules = [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'secretaria' => 'required|int'
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'required' => 'É necessário informar um :attribute!',
                'max' => 'Quantidade de caracteres ultrapassada, o campo :attribute precisa ter menos que 254 caracteres!',
                'int' => 'O campo :attribute precisa ser informado !',
            ],
            [
                'nome' => "Nome",
                'descricao' => 'Descrição',
                'secretaria' => 'Secretaria'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        if (
            (auth()->user()->cant(Permission::UNIDADE_SECRETARIA_CREATE_ANY) &&
                !in_array($request->secretaria, auth()->user()->secretarias->pluck('id')->toArray()))
            || is_null(Secretaria::find($request->secretaria))
        ) {
            return redirect()->back()->withErrors(['secretaria' => 'Não foi possível identificar a Secretaria!'])->withInput();
        }

        Unidade::query()->create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'secretaria_id' => $request->secretaria,
            'ativo' => true,
            'token' => substr(bin2hex(random_bytes(50)), 1),
        ]);

        return redirect()->route('unidades-secr-list')->with('success', 'Unidade Cadastrada com Sucesso!');
    }

    public function visualizar(Unidade $unidade)
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_VIEW);
        // $qrcode = QrCode::size(200)->generate('http://10.0.49.0:9000/avaliacoes/' . $unidade->token . '/avaliar');
        $qrcode = QrCode::size(200)->generate(route('get-avaliar-unidade', $unidade->token));
        // dd(route('get-avaliar-unidade', $unidade->token));
        return view('admin.avaliacoes.unidades-secr.unidades-visualizacao', compact('unidade', 'qrcode'));
    }

    public function atualizarUnidade(Unidade $unidade, Request $request)
    {
        $this->authorize(Permission::UNIDADE_SECRETARIA_UPDATE);
        $rules = [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'required' => 'É necessário informar um :attribute!',
                'max' => 'Quantidade de caracteres ultrapassada, o campo :attribute precisa ter menos que 254 caracteres!',
                'int' => 'O campo :attribute precisa ser informado !',
            ],
            [
                'nome' => "Nome",
                'descricao' => 'Descrição',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $unidade->nome = $request->nome;
        $unidade->descricao = $request->descricao;
        $unidade->ativo = true;
        $unidade->save();

        return redirect()
            ->route('visualizar-unidade', compact('unidade'))
            ->with('success', 'Unidade editada com Sucesso!');
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


    public function paginaAvaliar($token)
    {
        $unidade = Unidade::where('token', $token)->first();

        if (is_null($unidade)) {
            return redirect()->route('home');
        }

        return view('public.unidade_secr.avaliacao', compact('unidade'));
    }

    public function avaliar($token, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'avaliacao' => 'required|integer|max:5|min:1',
                'comentario' => 'nullable|string',
            ],
            [
                'required' => 'É necessário fazer uma :attribute!',
                'max' => 'Algo deu errao com a sua :atribute!',
                'min' => 'Algo deu errao com a sua :atribute!',
            ],
            [
                'avaliacao' => 'Avaliação',
                'comentario' => 'Comentário',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $unidade = Unidade::where('token', $token)->first();

        if (is_null($unidade)) {
            return redirect()->back()->withErrors(['unidade' => 'Unidade não encontrada!']);
        }

        $avaliacao = new Avaliacao();

        $avaliacao->nota  = $request->avaliacao;
        $avaliacao->comentario  = $request->comentario;
        $avaliacao->unidade_secr_id  = $unidade->id;
        $avaliacao->save();

        return redirect()->route('agradecimento-avaliacao')->with(["success" => 'Avaliação cadastrada com sucesso!']);
    }
}
