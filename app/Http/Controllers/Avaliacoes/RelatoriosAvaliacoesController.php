<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class RelatoriosAvaliacoesController extends Controller
{
    //resumo Geral
    public function resumo(): View
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_GERAL_VIEW);

        $resumoSecretarias = Secretaria::getResumoSecretariaAll();
        $qtdAvaliacoes = $resumoSecretarias['qtd'];

        $notas = [
            2 => ['qtd' => $resumoSecretarias['notas1'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas1'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            4 => ['qtd' => $resumoSecretarias['notas2'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas2'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            6 => ['qtd' => $resumoSecretarias['notas3'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas3'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            8 => ['qtd' => $resumoSecretarias['notas4'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas4'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            10 => ['qtd' => $resumoSecretarias['notas5'], "percent" => $qtdAvaliacoes > 0 ? number_format($resumoSecretarias['notas5'] / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        ];

        $secretarias = Secretaria::query()->orderBy('nota', 'desc')->where('nota', '!=', 0)->where('ativo', true)->get();

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
                "id" => $secretaria->id,
                "nome" => $secretaria->nome . " - " . $secretaria->sigla,
                "nota" => is_null($secretaria->nota) ? floatval('0.00') : floatval(number_format($secretaria->nota, 2, '.', '')),
            ];
        }

        //top 5 melhores Unidades
        $top5BestUnidades = [];

        //melhores Unidades
        $bestUnidades = [];

        $unidades = Unidade::query()
            ->with('secretaria')
            ->where(function ($query) use ($secretarias) {
                $query->whereIn('secretaria_id', $secretarias->pluck('id'));
            })
            ->orderBy('nota', 'desc')
            ->limit(20)
            ->get();

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
                'id' => $unidade->id,
                'secretaria_id' => $unidade->secretaria_id,
                'nome' => $unidade->nome . " - " . $unidade->secretaria->sigla,
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
            'qtdAvaliacoes'
        );

        return view('admin.avaliacoes.resumos.resumo-geral', $dataToView);
    }

    // Listagem das Secretarias para resumo
    public function resumoSecretariasList()
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW);

        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretarias = Secretaria::query()
                ->when(request()->pesquisa, function ($query) {
                    $query->where('nome', 'ilike', "%" . request()->pesquisa . "%")
                        ->orWhere('sigla', 'ilike', "%" . request()->pesquisa . "%");
                });
        } else {
            $secretarias = auth()->user()->secretarias();
        };

        $secretarias = $secretarias
            ->orderBy('ativo', 'desc')
            ->withCount(['unidades' => function ($query) {
                $query->where('ativo', true);
            }, 'avaliacoes' => function ($query) {
                $query->whereHas('setor', function ($query) {
                    $query->whereHas('unidade', function ($query) {
                        $query->where('ativo', true);
                    });
                });
            }])
            ->when(
                request()->unidades,
                function ($query) {
                    $query->orderBy('unidades_count', request()->unidades);
                }
            )
            ->when(
                request()->avaliacoes,
                function ($query) {
                    $query->orderBy('avaliacoes_count', request()->avaliacoes);
                }
            )
            ->when(
                request()->notas,
                function ($query) {
                    $query->orderBy('nota', request()->notas);
                }
            )
            ->when(
                !(request()->unidades || request()->notas || request()->avaliacoes),
                function ($query) {
                    $query->orderBy('nome', 'asc');
                }
            )
            ->paginate(15)
            ->appends([
                'unidades' => request()->unidades,
                'notas' => request()->notas,
                'avaliacoes' => request()->avaliacoes,
            ]);

        if ($secretarias->count() == 1) {
            return redirect()->route('get-resumo-avaliacoes-secretaria', $secretarias[0]);
        }

        return view('admin.avaliacoes.resumos.secretarias.resumo-secretaria-list', compact('secretarias'));
    }

    //Resumo por secretaria
    public function resumoSecretaria(Secretaria $secretaria): View
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW);

        abort_unless(
            auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA) ||
                (auth()->user()->secretarias->contains($secretaria) && auth()->user()->can(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW)),
            HttpResponse::HTTP_FORBIDDEN
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

        $unidades = $secretaria
            ->unidades()
            ->ativo()
            ->where('nota', '>', 0)
            ->orderBy('nota', 'desc')
            ->limit(20)
            ->get();

        foreach ($unidades as $unidade) {
            $nota = $unidade->nota;
            $qtd = $unidade->getResumoFromCache()['qtd'];

            //melhores unidades
            $top5BestUnidades[] = [
                'id' => $unidade->id,
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
    public function avaliacoesPorMesSecretaria(Secretaria $secretaria): JsonResponse
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW);

        $status = false;
        $resposta = null;
        if (preg_match("/^20[0-9]{2}$/", request()->ano)) {
            $resposta = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            $avaliacoesAno = $secretaria
                ->avaliacoes()
                ->whereYear('avaliacoes.created_at', request()->ano)
                ->get();

            foreach ($avaliacoesAno as $avaliacao) {
                $resposta[formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
            }
            $status = true;
        }
        $response = [
            'status' => $status,
            'resposta' => $resposta,
        ];

        return Response::json($response);
    }

    //Lista de resumos por Unidade
    public function resumoUnidadeSecrList()
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW);

        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }

        $unidades = Unidade::query()
            ->with('secretaria')
            ->withCount('avaliacoes')
            ->orderBy('ativo', 'desc')
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
            ->when(
                request()->notas,
                function ($query) {
                    $query->orderBy('nota', request()->notas);
                }
            )
            ->when(
                request()->avaliacoes,
                function ($query) {
                    $query->orderBy('avaliacoes_count', request()->avaliacoes);
                }
            )
            ->when(
                !(request()->notas || request()->avaliacoes),
                function ($query) {
                    $query->orderBy('nome', 'asc');
                }
            )
            ->paginate(15)
            ->appends([
                'notas' => request()->notas,
                'avaliacoes' => request()->avaliacoes,
                'pesquisa' => request()->pesquisa,
                'secretaria' => request()->secretaria_pesq,
            ]);

        if ($unidades->count() == 1) {
            return redirect()->route('get-resumo-avaliacoes-unidade', [$unidades[0]->secretaria_id, $unidades[0]]);
        }

        $dataToView = compact(
            'secretariasSearchSelect',
            'unidades'
        );

        return view('admin.avaliacoes.resumos.unidades.resumo-unidade-list', $dataToView);
    }

    //pagina de resumos por Unidade
    public function resumoUnidadeSecr(Secretaria $secretaria, Unidade $unidade): View
    {
        abort_unless(
            auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA) ||
                (auth()->user()->secretarias->contains($secretaria) &&
                    $secretaria->unidades->contains($unidade) &&
                    auth()->user()->can(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW)),
            HttpResponse::HTTP_FORBIDDEN
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
    public function avaliacoesPorMesUnidade($unidade_id)
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW);

        $status = false;
        $resposta = null;
        if (preg_match("/^20[0-9]{2}$/", request()->ano)) {
            // Avaliacoes por mes (qtd)
            $resposta = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $unidade = Unidade::with('setores')->find($unidade_id);

            $postsCountByMonth = Avaliacao::selectRaw('extract(month from created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', request()->ano)
                ->groupBy('month')
                ->whereIn('setor_id', $unidade->setores->pluck('id')->toArray())
                ->get();

            foreach ($postsCountByMonth as $item) {
                $resposta[$item->month - 1] = $item->count;
            }

            $status = true;
        }

        $response = [
            'status' => $status,
            'resposta' => $resposta,
        ];

        return Response::json($response);
    }

    //Rota de Api que retorna os arrays com notas por mes
    public function notasPorMesUnidade($unidade_id): JsonResponse
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW);

        $status = false;
        $resposta = null;
        if (preg_match("/^20[0-9]{2}$/", request()->ano)) {

            $unidade = Unidade::withCount([
                'avaliacoes as notas_10_1' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 1)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_2' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 2)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_3' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 3)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_4' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 4)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_5' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 5)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_6' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 6)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_7' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 7)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_8' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 8)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_9' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 9)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_10' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 10)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_11' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 11)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_10_12' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 12)
                        ->where('avaliacoes.nota', 10);
                },
                'avaliacoes as notas_8_1' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 1)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_2' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 2)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_3' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 3)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_4' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 4)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_5' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 5)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_6' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 6)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_7' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 7)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_8' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 8)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_9' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 9)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_10' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 10)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_11' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 11)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_8_12' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 12)
                        ->where('avaliacoes.nota', 8);
                },
                'avaliacoes as notas_6_1' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 1)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_2' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 2)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_3' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 3)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_4' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 4)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_5' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 5)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_6' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 6)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_7' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 7)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_6' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 8)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_9' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 9)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_10' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 10)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_11' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 11)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_6_12' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 12)
                        ->where('avaliacoes.nota', 6);
                },
                'avaliacoes as notas_4_1' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 1)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_2' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 2)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_3' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 3)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_4' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 4)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_5' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 5)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_4' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 6)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_7' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 7)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_4' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 8)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_9' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 9)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_10' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 10)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_11' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 11)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_4_12' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 12)
                        ->where('avaliacoes.nota', 4);
                },
                'avaliacoes as notas_2_1' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 1)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_2' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 2)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_3' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 3)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_4' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 4)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_5' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 5)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_2' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 6)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_7' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 7)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_2' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 8)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_9' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 9)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_10' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 10)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_11' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 11)
                        ->where('avaliacoes.nota', 2);
                },
                'avaliacoes as notas_2_12' => function ($query) {
                    $query->whereYear('avaliacoes.created_at', request()->ano)
                        ->whereMonth('avaliacoes.created_at', 12)
                        ->where('avaliacoes.nota', 2);
                }
            ])
                ->find($unidade_id);
            $status = true;

            $resposta[1][0] = $unidade->notas_2_1 ?? 0;
            $resposta[1][1] = $unidade->notas_2_2 ?? 0;
            $resposta[1][2] = $unidade->notas_2_3 ?? 0;
            $resposta[1][3] = $unidade->notas_2_4 ?? 0;
            $resposta[1][4] = $unidade->notas_2_5 ?? 0;
            $resposta[1][5] = $unidade->notas_2_6 ?? 0;
            $resposta[1][6] = $unidade->notas_2_7 ?? 0;
            $resposta[1][7] = $unidade->notas_2_8 ?? 0;
            $resposta[1][8] = $unidade->notas_2_9 ?? 0;
            $resposta[1][9] = $unidade->notas_2_10 ?? 0;
            $resposta[1][10] = $unidade->notas_2_11 ?? 0;
            $resposta[1][11] = $unidade->notas_2_12 ?? 0;

            $resposta[3][0] = $unidade->notas_4_1 ?? 0;
            $resposta[3][1] = $unidade->notas_4_2 ?? 0;
            $resposta[3][2] = $unidade->notas_4_3 ?? 0;
            $resposta[3][3] = $unidade->notas_4_4 ?? 0;
            $resposta[3][4] = $unidade->notas_4_5 ?? 0;
            $resposta[3][5] = $unidade->notas_4_6 ?? 0;
            $resposta[3][6] = $unidade->notas_4_7 ?? 0;
            $resposta[3][7] = $unidade->notas_4_8 ?? 0;
            $resposta[3][8] = $unidade->notas_4_9 ?? 0;
            $resposta[3][9] = $unidade->notas_4_10 ?? 0;
            $resposta[3][10] = $unidade->notas_4_11 ?? 0;
            $resposta[3][11] = $unidade->notas_4_12 ?? 0;

            $resposta[5][0] = $unidade->notas_6_1 ?? 0;
            $resposta[5][1] = $unidade->notas_6_2 ?? 0;
            $resposta[5][2] = $unidade->notas_6_3 ?? 0;
            $resposta[5][3] = $unidade->notas_6_4 ?? 0;
            $resposta[5][4] = $unidade->notas_6_5 ?? 0;
            $resposta[5][5] = $unidade->notas_6_6 ?? 0;
            $resposta[5][6] = $unidade->notas_6_7 ?? 0;
            $resposta[5][7] = $unidade->notas_6_8 ?? 0;
            $resposta[5][8] = $unidade->notas_6_9 ?? 0;
            $resposta[5][9] = $unidade->notas_6_10 ?? 0;
            $resposta[5][10] = $unidade->notas_6_11 ?? 0;
            $resposta[5][11] = $unidade->notas_6_12 ?? 0;

            $resposta[7][0] = $unidade->notas_8_1 ?? 0;
            $resposta[7][1] = $unidade->notas_8_2 ?? 0;
            $resposta[7][2] = $unidade->notas_8_3 ?? 0;
            $resposta[7][3] = $unidade->notas_8_4 ?? 0;
            $resposta[7][4] = $unidade->notas_8_5 ?? 0;
            $resposta[7][5] = $unidade->notas_8_6 ?? 0;
            $resposta[7][6] = $unidade->notas_8_7 ?? 0;
            $resposta[7][7] = $unidade->notas_8_8 ?? 0;
            $resposta[7][8] = $unidade->notas_8_9 ?? 0;
            $resposta[7][9] = $unidade->notas_8_10 ?? 0;
            $resposta[7][10] = $unidade->notas_8_11 ?? 0;
            $resposta[7][11] = $unidade->notas_8_12 ?? 0;

            $resposta[9][0] = $unidade->notas_10_1 ?? 0;
            $resposta[9][1] = $unidade->notas_10_2 ?? 0;
            $resposta[9][2] = $unidade->notas_10_3 ?? 0;
            $resposta[9][3] = $unidade->notas_10_4 ?? 0;
            $resposta[9][4] = $unidade->notas_10_5 ?? 0;
            $resposta[9][5] = $unidade->notas_10_6 ?? 0;
            $resposta[9][6] = $unidade->notas_10_7 ?? 0;
            $resposta[9][7] = $unidade->notas_10_8 ?? 0;
            $resposta[9][8] = $unidade->notas_10_9 ?? 0;
            $resposta[9][9] = $unidade->notas_10_10 ?? 0;
            $resposta[9][10] = $unidade->notas_10_11 ?? 0;
            $resposta[9][11] = $unidade->notas_10_12 ?? 0;
        }

        $response = [
            'status' => $status,
            'resposta' => $resposta,
        ];

        return Response::json($response);
    }
}
