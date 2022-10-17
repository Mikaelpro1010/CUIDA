<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use Illuminate\Http\Response;

class AvaliacoesController extends Controller
{
    //resumo Geral
    public function resumo()
    {
        //Avaliacoes
        // $avaliacoes = Avaliacao::get();

        //notas qtd
        // $notas5 = $avaliacoes->where('nota', 5)->count();
        // $notas4 = $avaliacoes->where('nota', 4)->count();
        // $notas3 = $avaliacoes->where('nota', 3)->count();
        // $notas2 = $avaliacoes->where('nota', 2)->count();
        // $notas1 = $avaliacoes->where('nota', 1)->count();
        // $qtdAvaliacoes = $notas1 + $notas2 + $notas3 + $notas4 + $notas5;

        // $notas = [
        //     1 => ['qtd' => $notas1, "percent" => $qtdAvaliacoes > 0 ? number_format($notas1 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        //     2 => ['qtd' => $notas2, "percent" => $qtdAvaliacoes > 0 ? number_format($notas2 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        //     3 => ['qtd' => $notas3, "percent" => $qtdAvaliacoes > 0 ? number_format($notas3 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        //     4 => ['qtd' => $notas4, "percent" => $qtdAvaliacoes > 0 ? number_format($notas4 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        //     5 => ['qtd' => $notas5, "percent" => $qtdAvaliacoes > 0 ? number_format($notas5 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        // ];

        //media das avaliaçoes
        $mediaAvaliacoes = [];

        //Melhores secretarias
        $bestSecretarias = [];

        //top 5 melhores Unidades
        $top5BestUnidades = [];

        //melhores Unidades
        $bestUnidades = [];

        $secretarias =  Secretaria::query()->with('unidades', 'unidades.avaliacoes')->get();

        $unidades =  Unidade::query()->with('secretaria', 'avaliacoes')->get();

        $notas5 = 0;
        $notas4 = 0;
        $notas3 = 0;
        $notas2 = 0;
        $notas1 = 0;

        foreach ($secretarias as $secretaria) {
            $dataSet = [];
            $dataSetbestUnidades = [];
            $aux = [];

            //gerando cores aleatorias
            $r = random_int(1, 255);
            $g = random_int(1, 255);
            $b = random_int(1, 255);

            foreach ($secretaria->unidades as $unidade) {

                foreach ($unidade->avaliacoes as $avaliacao) {
                    switch ($avaliacao->nota) {
                        case 1:
                            $notas1++;;
                            break;
                        case 2:
                            $notas2++;;
                            break;
                        case 3:
                            $notas3++;;
                            break;
                        case 4:
                            $notas4++;;
                            break;
                        case 5:
                            $notas5++;;
                            break;
                    }
                }


                $x = $unidade->avaliacoes->avg('nota');
                $nota = is_null($x) ? floatval('0.00') : floatval(number_format($x, 2, '.', ''));
                $qtd = $unidade->avaliacoes->count('nota');
                $aux[] = $x;

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
                $dataSetbestUnidades = [];
            }

            $mediaUnidades = collect($aux)->avg();
            $secretaria->media = is_null($mediaUnidades) ? floatval('0.00') : floatval(number_format($mediaUnidades, 2, '.', ''));

            $bestSecretarias[] = [
                "nome" => $secretaria->nome . " - " . $secretaria->sigla,
                "nota" => $secretaria->media,
            ];

            //SecretariasAvg
            $dataSet['label'] = $secretaria->sigla;
            $dataSet['data'][] = $secretaria->media;

            $dataSet['backgroundColor'] = "rgba($r, $g, $b, 1)";
            $dataSet['fill'] = true;
            $dataSet['tension'] = 0.3;

            $mediaAvaliacoes[] = $dataSet;
        }

        $qtdAvaliacoes = $notas1 + $notas2 + $notas3 + $notas4 + $notas5;

        $notas = [
            1 => ['qtd' => $notas1, "percent" => $qtdAvaliacoes > 0 ? number_format($notas1 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            2 => ['qtd' => $notas2, "percent" => $qtdAvaliacoes > 0 ? number_format($notas2 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            3 => ['qtd' => $notas3, "percent" => $qtdAvaliacoes > 0 ? number_format($notas3 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            4 => ['qtd' => $notas4, "percent" => $qtdAvaliacoes > 0 ? number_format($notas4 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            5 => ['qtd' => $notas5, "percent" => $qtdAvaliacoes > 0 ? number_format($notas5 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        ];

        //media Geral
        $avaliacoesAverage = $secretarias->avg('media');
        $percentAverage = $avaliacoesAverage / 5 * 100;
        $avaliacoesAverage = floatval(number_format($avaliacoesAverage, 2, '.', ''));

        usort($mediaAvaliacoes, function ($a, $b) {
            return $a['data'] < $b['data'];
        });

        usort($bestSecretarias, function ($a, $b) {
            return $a['nota'] < $b['nota'];
        });

        usort($top5BestUnidades, function ($a, $b) {
            return $a['nota'] < $b['nota'];
        });

        usort($bestUnidades, function ($a, $b) {
            return $a['data'] < $b['data'];
        });

        $bestUnidades = array_slice($bestUnidades, 0, 30);
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
            $secretarias = Secretaria::query()->with('avaliacoes', 'unidades')->orderBy('nome', 'asc')->get();
        } else {
            $secretarias = auth()->user()->secretarias()->with('avaliacoes', 'unidades')->orderBy('nome', 'asc')->get();
        };

        if ($secretarias->count() == 1) {
            return redirect()->route('resumo-avaliacoes-secretaria', $secretarias[0]);
        }

        foreach ($secretarias as $secretaria) {
            $avaliacoesPorUnidades = $secretaria->avaliacoes->groupBy('unidade_secr_id');
            $mediaUnidades = 0;
            foreach ($avaliacoesPorUnidades as $key => $unidade) {
                $mediaUnidades += $unidade->avg('nota');
            }
            $secretaria->media =  $avaliacoesPorUnidades->count() == 0 ? '-' : number_format($mediaUnidades / $avaliacoesPorUnidades->count(), 2, ',', '');
        }

        return view('admin.avaliacoes.resumos.secretarias.resumo-secretaria-list', compact('secretarias'));
    }

    //Resumo por secretaria
    public function resumoSecretaria(Secretaria $secretaria)
    {
        abort_unless(
            auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA) ||
                (auth()->user()->secretarias->contains($secretaria) && auth()->user()->can(Permission::RESUMO_AVALIACOES_SECRETARIA_VIEW)),
            Response::HTTP_FORBIDDEN
        );

        //Avaliaçoes da secretaria
        $avaliacoes = $secretaria->with('avaliacoes', 'avaliacoes.unidade')->find($secretaria->id)->avaliacoes;

        //media Geral
        $avaliacoesAverage = floatval(number_format($avaliacoes->avg('nota'), 2, '.', ''));
        $percentAverage = $avaliacoesAverage / 5 * 100;

        //top 5 melhores Unidades
        $top5BestUnidades = [];

        //notas qtd
        $qtdAvaliacoes = $avaliacoes->count();
        $notas1 = $avaliacoes->where('nota', 1)->count();
        $notas2 = $avaliacoes->where('nota', 2)->count();
        $notas3 = $avaliacoes->where('nota', 3)->count();
        $notas4 = $avaliacoes->where('nota', 4)->count();
        $notas5 = $avaliacoes->where('nota', 5)->count();

        $notas = [
            1 => ['qtd' => $notas1, "percent" => $qtdAvaliacoes > 0 ? number_format($notas1 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            2 => ['qtd' => $notas2, "percent" => $qtdAvaliacoes > 0 ? number_format($notas2 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            3 => ['qtd' => $notas3, "percent" => $qtdAvaliacoes > 0 ? number_format($notas3 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            4 => ['qtd' => $notas4, "percent" => $qtdAvaliacoes > 0 ? number_format($notas4 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            5 => ['qtd' => $notas5, "percent" => $qtdAvaliacoes > 0 ? number_format($notas5 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        ];

        //melhores Unidades
        $bestUnidades = [];

        foreach ($avaliacoes->groupBy('unidade_secr_id') as $avaliacoesUnidade) {
            $average = $avaliacoesUnidade->avg('nota');
            $nota = floatval(number_format($average, 2, '.', ''));
            $qtd = $avaliacoesUnidade->count('nota');

            //melhores unidades
            $top5BestUnidades[] = [
                'nome' => $avaliacoesUnidade[0]->unidade->nome,
                'nota' => $nota,
                'qtd' => $qtd,
            ];

            //gerando cores aleatorias
            $r = random_int(1, 255);
            $g = random_int(1, 255);
            $b = random_int(1, 255);

            //melhores unidades
            $dataSetbestUnidades['label'] = $avaliacoesUnidade[0]->unidade->nome . " (" . $qtd . ")";
            $dataSetbestUnidades['data'][] = $nota;
            $dataSetbestUnidades['backgroundColor'] = "rgba($r, $g, $b, 1)";

            $bestUnidades[] = $dataSetbestUnidades;
            $dataSetbestUnidades = [];
        }

        usort($top5BestUnidades, function ($a, $b) {
            return $a['nota'] < $b['nota'];
        });

        usort($bestUnidades, function ($a, $b) {
            return $a['data'] < $b['data'];
        });

        $bestUnidades = array_slice($bestUnidades, 0, 30);
        $qtdBestUnidades = count($bestUnidades);

        // Avaliacoes por mes (qtd)
        $avaliacoesMes = [];
        $aux = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,];

        foreach ($avaliacoes as $avaliacao) {
            $ano = formatarDataHora($avaliacao->created_at, 'Y');
            if ($ano == formatarDataHora(null, "Y")) {
                $aux[formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
            }
        }

        //gerando cores aleatorias
        $r = random_int(1, 255);
        $g = random_int(1, 255);
        $b = random_int(1, 255);

        $avaliacoesMes = [];
        $avaliacoesMes['label'] = 'Quantidade de Avaliações';
        $avaliacoesMes['data'] = $aux;

        $avaliacoesMes['borderColor'] = "rgba($r, $g, $b, 1)";
        $avaliacoesMes['backgroundColor'] = "rgba($r, $g, $b, 0.3)";
        $avaliacoesMes['fill'] = true;
        $avaliacoesMes['tension'] = 0.3;

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
            'avaliacoesMes'
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
            $resposta = [
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0,
                11 => 0,
            ];

            foreach ($secretaria->avaliacoes as $avaliacao) {
                $anoAvaliacao = formatarDataHora($avaliacao->created_at, 'Y');
                if ($anoAvaliacao == request()->ano) {
                    $resposta[formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
                }
            }
            $status = true;
        } else {
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
                $query->where('nome', request()->pesquisa);
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
            ->with('avaliacoes', 'secretaria')
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
                    auth()->user()->can(Permission::RESUMO_AVALIACOES_UNIDADE_VIEW)),
            Response::HTTP_FORBIDDEN
        );

        //Avaliaçoes da secretaria
        $avaliacoes = $unidade->avaliacoes;

        //media Geral
        $avaliacoesAverage = floatval(number_format($avaliacoes->avg('nota'), 2, '.', ''));
        $percentAverage = $avaliacoesAverage / 5 * 100;

        //notas qtd
        $qtdAvaliacoes = $avaliacoes->count();
        $notas1 = $avaliacoes->where('nota', 1)->count();
        $notas2 = $avaliacoes->where('nota', 2)->count();
        $notas3 = $avaliacoes->where('nota', 3)->count();
        $notas4 = $avaliacoes->where('nota', 4)->count();
        $notas5 = $avaliacoes->where('nota', 5)->count();

        $notas = [
            1 => ['qtd' => $notas1, "percent" => $qtdAvaliacoes > 0 ? number_format($notas1 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            2 => ['qtd' => $notas2, "percent" => $qtdAvaliacoes > 0 ? number_format($notas2 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            3 => ['qtd' => $notas3, "percent" => $qtdAvaliacoes > 0 ? number_format($notas3 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            4 => ['qtd' => $notas4, "percent" => $qtdAvaliacoes > 0 ? number_format($notas4 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
            5 => ['qtd' => $notas5, "percent" => $qtdAvaliacoes > 0 ? number_format($notas5 / $qtdAvaliacoes * 100, 1, '.', '') : 0],
        ];


        $primary = 'rgba(12,110,253,1)';
        $transparentPrimary = 'rgba(12,110,253,0.3)';
        $danger = 'rgba(220,53,69,1)';
        $transparentDanger = 'rgba(220,53,69,0.3)';
        $warning = 'rgba(255,193,6,1)';
        $transparentWarning = 'rgba(255,193,6,0.3)';
        $info = 'rgba(14,202,240,1)';
        $transparentInfo = 'rgba(14,202,240,0.3)';
        $success = 'rgba(26,135,84,1)';
        $transparentSuccess = 'rgba(26,135,84,0.3)';

        $aux = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        $notasMes = [
            '0' => ['data' => $aux],
            '1' => ['data' => $aux],
            '2' => ['data' => $aux],
            '3' => ['data' => $aux],
            '4' => ['data' => $aux],
        ];
        foreach ($avaliacoes as $avaliacao) {
            $ano = formatarDataHora($avaliacao->created_at, 'Y');
            if ($ano == formatarDataHora(null, "Y")) {
                $notasMes[$avaliacao->nota - 1]['data'][formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
            }
        }


        for ($i = 0; $i < 5; $i++) {
            $notasMes[$i]['fill'] = true;
            $notasMes[$i]['tension'] = 0.3;
        }

        $notasMes[0]['label'] = 'Muito Ruim';
        $notasMes[0]['borderColor'] = $danger;
        $notasMes[0]['backgroundColor'] = $transparentDanger;

        $notasMes[1]['label'] = 'Ruim';
        $notasMes[1]['borderColor'] = $warning;
        $notasMes[1]['backgroundColor'] = $transparentWarning;

        $notasMes[2]['label'] = 'Neutro';
        $notasMes[2]['borderColor'] = $info;
        $notasMes[2]['backgroundColor'] = $transparentInfo;

        $notasMes[3]['label'] = 'Bom';
        $notasMes[3]['borderColor'] = $primary;
        $notasMes[3]['backgroundColor'] = $transparentPrimary;

        $notasMes[4]['label'] = 'Muito Bom';
        $notasMes[4]['borderColor'] = $success;
        $notasMes[4]['backgroundColor'] = $transparentSuccess;


        // Avaliacoes por mes (qtd)
        $avaliacoesMes = [];

        foreach ($avaliacoes as $avaliacao) {
            $ano = formatarDataHora($avaliacao->created_at, 'Y');
            if ($ano == formatarDataHora(null, "Y")) {
                $aux[formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
            }
        }

        //gerando cores aleatorias
        $r = random_int(1, 255);
        $g = random_int(1, 255);
        $b = random_int(1, 255);

        $avaliacoesMes = [];
        $avaliacoesMes['label'] = 'Avaliações por mês';
        $avaliacoesMes['data'] = $aux;

        $avaliacoesMes['borderColor'] = "rgba($r, $g, $b, 1)";
        $avaliacoesMes['backgroundColor'] = "rgba($r, $g, $b, 0.3)";
        $avaliacoesMes['fill'] = true;
        $avaliacoesMes['tension'] = 0.3;


        $dataToView = compact(
            'secretaria',
            'unidade',
            'qtdAvaliacoes',
            'notas',
            'avaliacoesAverage',
            'percentAverage',
            'notasMes',
            'avaliacoesMes',
            'todasAvaliacoes',
            'keysTodasAvaliacoes'
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

            foreach ($unidade->avaliacoes as $avaliacao) {
                $anoAvaliacao = formatarDataHora($avaliacao->created_at, 'Y');
                if ($anoAvaliacao == request()->ano) {
                    $resposta[formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
                }
            }
            $status = true;
        } else {
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

            $resposta[0] = $aux;
            $resposta[1] = $aux;
            $resposta[2] = $aux;
            $resposta[3] = $aux;
            $resposta[4] = $aux;

            foreach ($unidade->avaliacoes as $avaliacao) {
                $anoAvaliacao = formatarDataHora($avaliacao->created_at, 'Y');
                if ($anoAvaliacao == request()->ano) {
                    $resposta[$avaliacao->nota - 1][formatarDataHora($avaliacao->created_at, 'n') - 1] += 1;
                }
            }
            $status = true;
        } else {
        }
        $response = [
            'status' => $status,
            'resposta' => $resposta,
        ];

        return json_encode($response);
    }
}
