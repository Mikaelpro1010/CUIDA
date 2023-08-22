<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\TipoAvaliacao;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use App\Traits\ExportExcel;
use Illuminate\Http\Request;

class RelatoriosAvaliacoesController extends Controller
{
    // resumo Geral
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
            $qtd = $unidade->getResumo()['qtd'];

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

    public function resumoSecretariasList()
    {
        // $this->authorize(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW);

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

    //
    //Resumo por secretaria
    public function filtrar(Secretaria $secretaria, Request $request): View
    {
        // $this->authorize(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW);

        abort_unless(
            auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA) ||
                (auth()->user()->secretarias->contains($secretaria) && auth()->user()->can(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW)),
            HttpResponse::HTTP_FORBIDDEN
        );

        //  

        $resumoSecretaria = Avaliacao::query()
            ->join('setores', 'avaliacoes.setor_id', '=', 'setores.id')
            ->join('unidades', 'setores.unidade_id', '=', 'unidades.id')
            ->join('secretarias', 'unidades.secretaria_id', '=', 'secretarias.id')
            ->where('secretarias.id', $secretaria->id)
            ->when(request()->tipoAvalicao, function ($query) {
                $query->where('avaliacoes.tipo_avaliacao_id', request()->tipoAvalicao);
            })
            ->when(
                request()->data_inicial || request()->data_final,
                function ($query) {
                    $query->when(request()->data_inicial, function ($query) {
                        $query->whereDate('avaliacoes.created_at', '>=', request()->data_inicial);  // quando o campo de pesquisa for preenchido por data inicial
                    })
                        ->when(request()->data_final, function ($query) {
                            $query->whereDate('avaliacoes.created_at', '<=', request()->data_final); // quando o campo de pesquisa for preenchido por data final
                        });
                },
            )
            ->selectRaw('avaliacoes.nota, avaliacoes.nota, COUNT(*) as qtd_notas')
            ->groupBy('avaliacoes.nota')
            ->get();


        $dataToView = compact(
            'resumoSecretaria',
        );

        return view(
            'admin.avaliacoes.resumos.secretarias.resumo-secretaria',
            $dataToView
        );
    }

    //Resumo por secretaria
    public function resumoSecretaria(Secretaria $secretaria, Request $request): View
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW);

        abort_unless(
            auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA) ||
                (auth()->user()->secretarias->contains($secretaria) && auth()->user()->can(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW)),
            HttpResponse::HTTP_FORBIDDEN
        );

        //  

        $resumoUnidade = Avaliacao::query()
            ->join('setores', 'avaliacoes.setor_id', '=', 'setores.id')
            ->join('unidades', 'setores.unidade_id', '=', 'unidades.id')
            ->join('secretarias', 'unidades.secretaria_id', '=', 'secretarias.id')
            ->where('secretarias.id', $secretaria->id)
            ->when(request()->tipoAvalicao, function ($query) {
                $query->where('avaliacoes.tipo_avaliacao_id', request()->tipoAvalicao);
            })
            ->when(
                request()->data_inicial || request()->data_final,
                function ($query) {
                    $query->when(request()->data_inicial, function ($query) {
                        $query->whereDate('avaliacoes.created_at', '>=', request()->data_inicial);  // quando o campo de pesquisa for preenchido por data inicial
                    })
                        ->when(request()->data_final, function ($query) {
                            $query->whereDate('avaliacoes.created_at', '<=', request()->data_final); // quando o campo de pesquisa for preenchido por data final
                        });
                },
            )
            ->selectRaw('avaliacoes.nota, avaliacoes.nota, COUNT(*) as qtd_notas')
            ->groupBy('avaliacoes.nota')
            ->get();

        $notas = [];
        foreach ($resumoUnidade as $item) {
            $notas[$item->nota] = [
                'qtd' => $item->qtd_notas
            ];
        }

        $tiposAvaliacao = TipoAvaliacao::query()
            ->where('secretaria_id', $secretaria->id)
            ->ativo()
            ->get(['id', 'nome']);


        //gerando cores aleatorias
        $r = random_int(1, 255);
        $g = random_int(1, 255);
        $b = random_int(1, 255);

        $corGrafico = "$r, $g, $b";
        //



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
            $qtd = $unidade->getResumo()['qtd'];

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
            'corGrafico',
            'resumoUnidade'
        );

        return view('admin.avaliacoes.resumos.secretarias.resumo-secretaria', $dataToView);
    }

    //retorna json com os dados para o grafico de avaliaçoes por mes
    public function avaliacoesPorMesSecretaria(Secretaria $secretaria): JsonResponse
    {
        // $this->authorize(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW);

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
        $this->authorize(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW);

        $unidade->userCanAccess();

        //media Geral
        $avaliacoesAverage = floatval(number_format($unidade->nota, 2, '.', ''));
        $percentAverage = $avaliacoesAverage * 10;

        $resumoUnidade = Avaliacao::query()
            ->join('setores', 'avaliacoes.setor_id', '=', 'setores.id')
            ->join('unidades', 'setores.unidade_id', '=', 'unidades.id')
            ->where('unidades.id', $unidade->id)
            ->when(request()->tipoAvalicao, function ($query) {
                $query->where('avaliacoes.tipo_avaliacao_id', request()->tipoAvalicao);
            })
            ->when(request()->ano, function ($query) {
                $query->whereYear('avaliacoes.created_at', request()->ano);
            }, function ($query) {
                $query->whereYear('avaliacoes.created_at', now()->year);
            })
            ->when(request()->mes, function ($query) {
                $query->whereMonth('avaliacoes.created_at', request()->mes);
            })
            ->selectRaw('avaliacoes.nota, avaliacoes.nota, COUNT(*) as qtd_notas')
            ->groupBy('avaliacoes.nota')
            ->get();

        $qtdAvaliacoes = $resumoUnidade->sum('qtd_notas');

        $notas = [];
        foreach ($resumoUnidade as $item) {
            $notas[$item->nota] = [
                'qtd' => $item->qtd_notas,
                'percent' => $qtdAvaliacoes > 0 ? number_format($item->qtd_notas / $qtdAvaliacoes * 100, 1, '.', '') : 0
            ];
        }

        $tiposAvaliacao = TipoAvaliacao::query()
            ->where('secretaria_id', $secretaria->id)
            ->ativo()
            ->get(['id', 'nome']);


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
            'corGrafico',
            'tiposAvaliacao'
        );

        return view('admin.avaliacoes.resumos.unidades.resumo-unidade', $dataToView);
    }

    //Rota de api que retorna o array com as quantidades de avaliaçoes por mes
    public function avaliacoesPorMesUnidade($unidade_id)
    {
        if (!request()->ajax()) {
            abort(HttpResponse::HTTP_NOT_FOUND);
        }

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
    public function notasPorMesSecretaria($secretaria_id): JsonResponse
    {

        if (!request()->ajax()) {
            abort(HttpResponse::HTTP_NOT_FOUND);
        }

        // $this->authorize(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW);

        $status = false;
        $resposta = null;
        if (preg_match("/^20[0-9]{2}$/", request()->ano)) {
            $aux = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            $resposta[1] = $aux;
            $resposta[3] = $aux;
            $resposta[5] = $aux;
            $resposta[7] = $aux;
            $resposta[9] = $aux;


            // $secretaria = Secretaria::find($secretaria_id);
            // $unidade = Unidade::find($secretaria_id);




            $avaliacoesBySecretaria = Avaliacao::selectRaw('extract(month from created_at) as month, avaliacoes.nota, COUNT(*) as count')
                ->whereYear('created_at', request()->ano)
                ->groupBy('month', 'nota')
                ->whereIn('setor_id', function ($query) use ($secretaria_id) {
                    $query->select('setores.id')
                        ->from('setores')
                        ->join('unidades', 'setores.unidade_id', '=', 'unidades.id')
                        ->where('unidades.secretaria_id', '=', $secretaria_id);
                })
                ->get();


            // dd($avaliacoesBySecretaria);
            foreach ($avaliacoesBySecretaria as $notaCount) {
                $resposta[$notaCount->nota - 1][$notaCount->month - 1] = $notaCount['count'];
            }
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
        if (!request()->ajax()) {
            abort(HttpResponse::HTTP_NOT_FOUND);
        }

        $this->authorize(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW);

        $status = false;
        $resposta = null;
        if (preg_match("/^20[0-9]{2}$/", request()->ano)) {
            $aux = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            $resposta[1] = $aux;
            $resposta[3] = $aux;
            $resposta[5] = $aux;
            $resposta[7] = $aux;
            $resposta[9] = $aux;

            $unidade = Unidade::find($unidade_id);

            $notasCountByMonth = Avaliacao::selectRaw('extract(month from created_at) as month, avaliacoes.nota, COUNT(*) as count')
                ->whereYear('created_at', request()->ano)
                ->groupBy('month', 'nota')
                ->whereIn('setor_id', $unidade->setores->pluck('id')->toArray())
                ->get();

            foreach ($notasCountByMonth as $notaCount) {
                $resposta[$notaCount->nota - 1][$notaCount->month - 1] = $notaCount['count'];
            }
        }

        $response = [
            'status' => $status,
            'resposta' => $resposta,
        ];

        return Response::json($response);
    }

    public function exportRelatorioUnidade()
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW);

        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }


        $unidades = Unidade::query()
            ->select([
                DB::raw("
            unidades.nome as Unidade, 
            secretarias.nome as Secretaria, 
            CAST(unidades.nota AS NUMERIC(10, 2)) AS nota, 
            COUNT(CASE WHEN avaliacoes.nota IN (2, 4, 6, 8, 10) THEN 1 ELSE 0 END) AS qtd_avaliacoes
        ")
            ])->from('unidades')
            ->join('setores', 'unidades.id', '=', 'setores.unidade_id')
            ->join('avaliacoes', 'setores.id', '=', 'avaliacoes.setor_id')
            ->leftJoin('secretarias', 'secretarias.id', '=', 'unidades.secretaria_id')
            ->groupBy('unidades.nome', 'secretarias.nome', 'unidades.nota')
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
                    $query->orderBy('qtd_avaliacoes', request()->avaliacoes);
                }
            )
            ->when(
                !(request()->notas || request()->avaliacoes),
                function ($query) {
                    $query->orderBy('unidades.nome', 'asc');
                }
            )
            ->get();

        ExportExcel::export($unidades);
    }

    public function resumoSecr(Secretaria $secretaria, Unidade $unidade): View
    {

        // $this->authorize(Permission::RELATORIO_AVALIACOES_SEC_VIEW);

        $secretaria->userCanAccess();

        //media Geral
        $avaliacoesAverage = floatval(number_format($secretaria->nota, 2, '.', ''));
        $percentAverage = $avaliacoesAverage * 10;

        $resumoUnidade = Avaliacao::query()
            ->join('setores', 'avaliacoes.setor_id', '=', 'setores.id')
            ->join('unidades', 'setores.unidade_id', '=', 'unidades.id')
            ->join('secretarias', 'unidades.secretaria_id ', '=', 'secretarias.id')
            ->where('secretarias.id', $secretaria->id)
            ->when(request()->tipoAvalicao, function ($query) {
                $query->where('avaliacoes.tipo_avaliacao_id', request()->tipoAvalicao);
            })
            ->when(request()->ano, function ($query) {
                $query->whereYear('avaliacoes.created_at', request()->ano);
            }, function ($query) {
                $query->whereYear('avaliacoes.created_at', now()->year);
            })
            ->when(request()->mes, function ($query) {
                $query->whereMonth('avaliacoes.created_at', request()->mes);
            })
            ->selectRaw('avaliacoes.nota, avaliacoes.nota, COUNT(*) as qtd_notas')
            ->groupBy('avaliacoes.nota')
            ->get();

        $notas = [];
        foreach ($resumoUnidade as $item) {
            $notas[$item->nota] = [
                'qtd' => $item->qtd_notas
            ];
        }

        $tiposAvaliacao = TipoAvaliacao::query()
            ->where('secretaria_id', $secretaria->id)
            ->ativo()
            ->get(['id', 'nome']);


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
            'corGrafico',
            'tiposAvaliacao'
        );



        return view('admin.avaliacoes.resumos.avaliacoes-secretaria.secretaria', $dataToView);
    }
}
