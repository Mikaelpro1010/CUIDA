<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use Illuminate\Http\Response;

class AvaliacoesController extends Controller
{
    //resumo Geral
    public function resumo()
    {
        $resumoSecretarias = Secretaria::getResumoSecretariaAll();

        $qtdAvaliacoes = $resumoSecretarias['qtd'];

        $notas = [
            2 => ['qtd' => $resumoSecretarias['notas1'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas1'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            4 => ['qtd' => $resumoSecretarias['notas2'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas2'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            6 => ['qtd' => $resumoSecretarias['notas3'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas3'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            8 => ['qtd' => $resumoSecretarias['notas4'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas4'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            10 => ['qtd' => $resumoSecretarias['notas5'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas5'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        ];

        $secretarias = Secretaria::query()->orderBy('nota', 'desc')->get();

        //media Geral
        $avaliacoesAverage = $secretarias->avg('nota');
        $percentAverage = $avaliacoesAverage * 10;
        $avaliacoesAverage = floatval(number_format($avaliacoesAverage, 2, '.', ''));

        //media das Secretarias
        $mediaAvaliacoes = [];

        //Melhores secretarias
        $bestSecretarias = [];

        foreach ($secretarias as $secretaria) {
            $dataSet = [];
            //gerando cores aleatorias
            $r = random_int(1, 255);
            $g = random_int(1, 255);
            $b = random_int(1, 255);

            //SecretariasAvg
            $dataSet['label'] = $secretaria->sigla;
            $dataSet['data'][] = is_null($secretaria->nota) ? floatval('0.00') : floatval(number_format($secretaria->nota, 2, '.', ''));

            $dataSet['backgroundColor'] = "rgba($r, $g, $b, 1)";
            $dataSet['fill'] = true;
            $dataSet['tension'] = 0.3;

            $mediaAvaliacoes[] = $dataSet;

            $bestSecretarias[] = [
                "nome" => $secretaria->nome . " - " . $secretaria->sigla,
                "nota" => is_null($secretaria->nota) ? floatval('0.00') : floatval(number_format($secretaria->nota, 2, '.', '')),
            ];
        }

        //top 5 melhores Unidades
        $top5BestUnidades = [];

        //melhores Unidades
        $bestUnidades = [];

        $unidades = Unidade::query()->orderBy('nota', 'desc')->limit(20)->get();

        foreach ($unidades as $unidade) {
            $dataSetbestUnidades = [];
            //gerando cores aleatorias
            $r = random_int(1, 255);
            $g = random_int(1, 255);
            $b = random_int(1, 255);

            $nota = is_null($unidade->nota) ? floatval('0.00') : floatval(number_format($unidade->nota, 2, '.', ''));
            $qtd = $unidade->getResumoFromCache()['qtd'];

            //melhores unidades
            $top5BestUnidades[] = [
                'nome' => $unidade->nome . " - " . $secretaria->sigla,
                'nota' => $nota,
                'qtd' => $qtd,
            ];

            $dataSetbestUnidades['label'] = $unidade->nome . "(" . $qtd . ") - " . $secretaria->sigla;
            $dataSetbestUnidades['data'][] = $nota;

            $dataSetbestUnidades['backgroundColor'] = "rgba($r, $g, $b, 1)";
            $dataSetbestUnidades['fill'] = true;
            $dataSetbestUnidades['tension'] = 0.3;

            $bestUnidades[] = $dataSetbestUnidades;
        }

        $qtdBestUnidades = count($bestUnidades);

        $dataToView = compact(
            'avaliacoesAverage',
            'percentAverage',
            'mediaAvaliacoes',
            'bestSecretarias',
            'top5BestUnidades',
            'bestUnidades',
            'qtdBestUnidades',
            'notas',
            'qtdAvaliacoes',
            'todasAvaliacoes',
            'keysTodasAvaliacoes'
        );

        return view('admin.avaliacoes.resumos.resumo-geral', $dataToView);
    }

    // Listagem das Secretarias para resumo
    public function resumoSecretariasList()
    {
        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretarias = Secretaria::query()->with('unidades')->orderBy('nome', 'asc')->paginate(15);
        } else {
            $secretarias = auth()->user()->secretarias()->with('unidades')->orderBy('nome', 'asc')->paginate(15);
        };

        if ($secretarias->count() == 1) {
            return redirect()->route('resumo-avaliacoes-secretaria', $secretarias[0]);
        }

        return view('admin.avaliacoes.resumos.secretarias.resumo-secretaria-list', compact('secretarias'));
    }

    //Resumo por secretaria
    public function resumoSecretaria(Secretaria $secretaria)
    {
        abort_unless(
            auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA) ||
                (auth()->user()->secretarias->contains($secretaria) && auth()->user()->can(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW)),
            Response::HTTP_FORBIDDEN
        );

        //notas qtd
        $resumoSecretaria = $secretaria->getResumo();

        $qtdAvaliacoes = $resumoSecretaria['qtd'];

        $notas = [
            2 => ['qtd' => $resumoSecretaria['notas1'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretaria['notas1'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            4 => ['qtd' => $resumoSecretaria['notas2'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretaria['notas2'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            6 => ['qtd' => $resumoSecretaria['notas3'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretaria['notas3'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            8 => ['qtd' => $resumoSecretaria['notas4'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretaria['notas4'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            10 => ['qtd' => $resumoSecretaria['notas5'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretaria['notas5'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        ];

        //media Geral
        $avaliacoesAverage = floatval(number_format($secretaria->nota, 2, '.', ''));
        $percentAverage = $avaliacoesAverage * 10;

        //top 5 melhores Unidades
        $top5BestUnidades = [];

        //melhores Unidades
        $bestUnidades = [];

        $unidades = $secretaria->unidades()->orderBy('nota', 'desc')->limit(20)->get();

        foreach ($unidades as $unidade) {
            $nota = $unidade->nota;
            $qtd = $unidade->getResumoFromCache()['qtd'];

            //melhores unidades
            $top5BestUnidades[] = [
                'nome' => $unidade->nome,
                'nota' => is_null($nota) ? '0,00' : number_format($nota, 2, ',', ''),
                'qtd' => $qtd,
            ];

            //gerando cores aleatorias
            $r = random_int(1, 255);
            $g = random_int(1, 255);
            $b = random_int(1, 255);

            //melhores unidades
            $dataSetbestUnidades['label'] = $unidade->nome . " (" . $qtd . ")";
            $dataSetbestUnidades['data'][] = is_null($nota) ? floatval('0.00') : floatval(number_format($nota, 2, '.', ''));
            $dataSetbestUnidades['backgroundColor'] = "rgba($r, $g, $b, 1)";

            $bestUnidades[] = $dataSetbestUnidades;
            $dataSetbestUnidades = [];
        }

        $qtdBestUnidades = count($bestUnidades);

        //gerando cores aleatorias
        $r = random_int(1, 255);
        $g = random_int(1, 255);
        $b = random_int(1, 255);

        $corGrafico = "$r, $g, $b";

        $dataToView = compact(
            'secretariasSearchSelect',
            'secretaria',
            'avaliacoesAverage',
            'percentAverage',
            'top5BestUnidades',
            'qtdAvaliacoes',
            'notas',
            'qtdBestUnidades',
            'bestUnidades',
            'corGrafico'
        );

        return view('admin.avaliacoes.resumos.secretarias.resumo-secretaria', $dataToView);
    }

    //retorna json com os dados para o grafico de avaliaçoes por mes
    public function avaliacoesPorMesSecretaria(Secretaria $secretaria)
    {
        $status = false;
        $resposta = null;
        if (preg_match("/^20[0-9]{2}$/", request()->ano)) {
            // Avaliacoes por mes (qtd)
            $resposta = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            $avaliacoesAno = $secretaria->avaliacoes()->whereYear('avaliacoes.created_at', request()->ano)->get();

            foreach ($avaliacoesAno as $avaliacao) {
                $resposta[formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
            }
            $status = true;
        }
        $response = [
            'status' => $status,
            'resposta' => $resposta,
        ];

        return json_encode($response);
    }

    //Lista de resumos por Unidade
    public function resumoUnidadeSecrList()
    {
        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }

        $unidades = Unidade::query()
            ->when(request()->pesquisa, function ($query) {
                $query->where('nome', 'like', '%' . request()->pesquisa . '%');
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
            ->with('secretaria')
            ->orderBy('nota', 'desc')
            ->paginate(15)
            ->appends([
                'pesquisa' => request()->pesquisa,
                'secretaria' => request()->secretaria_pesq,
            ]);

        $dataToView = compact(
            'secretariasSearchSelect',
            'unidades'
        );

        return view('admin.avaliacoes.resumos.unidades.resumo-unidade-list', $dataToView);
    }

    //pagina de resumos por Unidade
    public function resumoUnidadeSecr(Secretaria $secretaria, Unidade $unidade)
    {
        abort_unless(
            auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA) ||
                (auth()->user()->secretarias->contains($secretaria) &&
                    $secretaria->unidades->contains($unidade) &&
                    auth()->user()->can(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW)),
            Response::HTTP_FORBIDDEN
        );

        //media Geral
        $avaliacoesAverage = floatval(number_format($unidade->nota, 2, '.', ''));
        $percentAverage = $avaliacoesAverage * 10;

        //notas qtd
        $resumoUnidade = $unidade->getResumoFromCache();

        $qtdAvaliacoes = $resumoUnidade['qtd'];

        $notas = [
            2 => ['qtd' => $resumoUnidade['notas1'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoUnidade['notas1'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            4 => ['qtd' => $resumoUnidade['notas2'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoUnidade['notas2'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            6 => ['qtd' => $resumoUnidade['notas3'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoUnidade['notas3'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            8 => ['qtd' => $resumoUnidade['notas4'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoUnidade['notas4'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            10 => ['qtd' => $resumoUnidade['notas5'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoUnidade['notas5'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        ];

        //gerando cores aleatorias
        $r = random_int(1, 255);
        $g = random_int(1, 255);
        $b = random_int(1, 255);

        $corGrafico = "$r, $g, $b";

        $dataToView = compact(
            'secretaria',
            'unidade',
            'qtdAvaliacoes',
            'notas',
            'avaliacoesAverage',
            'percentAverage',
            'corGrafico'
        );

        return view('admin.avaliacoes.resumos.unidades.resumo-unidade', $dataToView);
    }

    //Rota de api que retorna o array com as quantidades de avaliaçoes por mes
    public function avaliacoesPorMesUnidade(Unidade $unidade)
    {
        $status = false;
        $resposta = null;
        if (preg_match("/^20[0-9]{2}$/", request()->ano)) {
            // Avaliacoes por mes (qtd)
            $resposta = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            $avaliacoesAno = $unidade->avaliacoes()->whereYear('avaliacoes.created_at', request()->ano)->get();

            foreach ($avaliacoesAno as $avaliacao) {
                $resposta[formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
            }
            $status = true;
        }

        $response = [
            'status' => $status,
            'resposta' => $resposta,
        ];

        return json_encode($response);
    }

    //Rota de Api que retorna os arrays com notas por mes
    public function notasPorMesUnidade(Unidade $unidade)
    {
        $status = false;
        $resposta = null;
        if (preg_match("/^20[0-9]{2}$/", request()->ano)) {
            // Avaliacoes por mes (qtd)
            $aux = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            $resposta[1] = $aux;
            $resposta[3] = $aux;
            $resposta[5] = $aux;
            $resposta[7] = $aux;
            $resposta[9] = $aux;

            $avaliacoesAno = $unidade->avaliacoes()->whereYear('avaliacoes.created_at', request()->ano)->get();

            foreach ($avaliacoesAno as $avaliacao) {
                $resposta[$avaliacao->nota - 1][formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
            }
            $status = true;
        }
        $response = [
            'status' => $status,
            'resposta' => $resposta,
        ];

        return json_encode($response);
    }
}
