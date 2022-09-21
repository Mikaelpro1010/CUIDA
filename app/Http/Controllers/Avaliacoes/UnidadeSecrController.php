<?php

namespace App\Http\Controllers\avaliacoes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UnidadeSecrController extends Controller
{
    public function listagem()
    {
        $ativo = ['ativo' => 1, 'inativo' => 0];

        $unidades = Unidade::query()->with('secretaria')
            ->when(request()->pesquisa, function ($query) {
                $query->where('nome', 'like',  "%" . request()->pesquisa . "%");
            })
            //insert permission
            ->when(true && request()->secretaria_pesq, function ($query) {
                $query->where('secretaria_id', request()->secretaria_pesq);
            })
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

        $secretarias = Secretaria::query()->orderBy('nome', 'asc')->get();

        return view('admin.avaliacoes.unidades-secr.unidades-listagem', compact('unidades', 'secretarias'));
    }

    public function novaUnidade(Request $request)
    {
        $rules = [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ];

        if (true) { //insert permission
            $rules['secretaria'] = 'required|int';
        }

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
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $unidade = new Unidade();
        $unidade->nome = $request->nome;
        $unidade->descricao = $request->descricao;
        if (true) { //insert permission
            $unidade->secretaria_id = $request->secretaria;
        } else {
            $unidade->secretaria_id = auth()->user()->secretaria_id;
        }
        $unidade->ativo = true;
        $unidade->save();

        return redirect()->route('unidades-secr-list')->with('success', 'Unidade Cadastrada com Sucesso!');
    }

    public function visualizar(Unidade $unidade)
    {
        $qrcode = QrCode::size(200)->generate('http://10.0.49.0:9000');
        return view('admin.avaliacoes.unidades-secr.unidades-visualizacao', compact('unidade', 'qrcode'));
    }

    public function atualizarUnidade(Unidade $unidade, Request $request)
    {
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
            ->with('success', 'Unidade Cadastrada com Sucesso!');
    }

    public function ativarDesativar(Unidade $unidade)
    {
        $unidade->ativo = !$unidade->ativo;
        $unidade->save();

        return redirect()->route('unidades-secr-list');
    }

    public function paginaAvaliar(Unidade $unidade)
    {
        return view('public.unidade_secr.avaliacao', compact('unidade'));
    }

    public function avaliar(Request $request)
    {
        dd($request->all());
    }
}
