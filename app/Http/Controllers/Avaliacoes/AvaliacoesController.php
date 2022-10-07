<?php

namespace App\Http\Controllers\Avaliacoes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Unidade;
use App\Models\Permission;
use App\Models\Secretaria;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;

use function GuzzleHttp\json_decode;

class AvaliacoesController extends Controller
{
    //resumo Geral
    public function resumo()
    {
        //media Geral
        $generalAverage = floatval(number_format(Avaliacao::query()->avg('nota'), 2, '.', ''));
        $percentAverage = $generalAverage / 5 * 100;

        //media das avaliaçoes
        $secretariaAvg = [];

        //Melhores secretarias
        $bestSecretarias = [];

        //top 5 melhores Unidades
        $top5BestUnidades = [];

        //melhores Unidades
        $bestUnidades = [];

        //notas qtd
        $notas = Avaliacao::get();
        $totalNotas = $notas->count();
        $notas5 = $notas->where('nota', 5)->count();
        $notas4 = $notas->where('nota', 4)->count();
        $notas3 = $notas->where('nota', 3)->count();
        $notas2 = $notas->where('nota', 2)->count();
        $notas1 = $notas->where('nota', 1)->count();

        $notas = [
            1 => ['qtd' => $notas1, "percent" => number_format($notas1 / $totalNotas * 100, 1, '.', '')],
            2 => ['qtd' => $notas2, "percent" => number_format($notas2 / $totalNotas * 100, 1, '.', '')],
            3 => ['qtd' => $notas3, "percent" => number_format($notas3 / $totalNotas * 100, 1, '.', '')],
            4 => ['qtd' => $notas4, "percent" => number_format($notas4 / $totalNotas * 100, 1, '.', '')],
            5 => ['qtd' => $notas5, "percent" => number_format($notas5 / $totalNotas * 100, 1, '.', '')],
        ];

        $secretarias = Secretaria::query()
            ->with('unidades', 'unidades.avaliacoes')
            ->get();

        foreach ($secretarias as $key => $secretaria) {
            $dataSet = [];
            $dataSetbestUnidades = [];
            $aux = [];

            //gerando cores aleatorias
            $r = random_int(1, 255);
            $g = random_int(1, 255);
            $b = random_int(1, 255);

            foreach ($secretaria->unidades as $unidade) {
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
            $mediaUnidades = is_null($mediaUnidades) ? floatval('0.00') : floatval(number_format($mediaUnidades, 2, '.', ''));

            $bestSecretarias[] = [
                "nome" => $secretaria->nome . " - " . $secretaria->sigla,
                "nota" => $mediaUnidades
            ];

            //SscretariasAvg
            $dataSet['label'] = $secretaria->sigla;
            $dataSet['data'][] = $mediaUnidades;

            $dataSet['backgroundColor'] = "rgba($r, $g, $b, 1)";
            $dataSet['fill'] = true;
            $dataSet['tension'] = 0.3;

            $secretariaAvg[] = $dataSet;
        }

        usort($secretariaAvg, function ($a, $b) {
            return $a['data'] < $b['data'];
        });
        $secretariaAvg = json_encode($secretariaAvg);

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
        $bestUnidades = json_encode($bestUnidades);

        $dataToView = compact(
            'generalAverage',
            'percentAverage',
            'secretariaAvg',
            'bestSecretarias',
            'top5BestUnidades',
            'bestUnidades',
            'qtdBestUnidades',
            'notas',
            'totalNotas'
        );

        return view('admin.avaliacoes.resumos.resumo-geral', $dataToView);
    }

    public function resumoSecretariasList()
    {
        if (auth()->user()->can(Permission::PERMISSION_UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretarias = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretarias = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }

        // dd($secretarias->count());

        return view('admin.avaliacoes.resumos.secretarias.resumo-secretaria-list', compact('secretarias'));
    }

    //Resumo por secretaria
    public function resumoSecretaria($secretaria)
    {
        if (auth()->user()->can(Permission::PERMISSION_UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }

        //secretaria
        $secretariaObj = $secretariasSearchSelect->find($secretaria_id);

        //Avaliaçoes da secretaria
        $avaliacoes = Avaliacao::query()
            ->with('unidade', 'unidade.secretaria')
            ->whereHas('unidade.secretaria', function ($query) {
                $query->where('secretaria_id', request()->secretaria);
            })
            ->get();

        //media Geral
        $avaliacoesSecretariaAverage = floatval(number_format($avaliacoes->avg('nota'), 2, '.', ''));
        $percentAverage = $avaliacoesSecretariaAverage / 5 * 100;

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
            $dataSetbestUnidades['fill'] = true;
            $dataSetbestUnidades['tension'] = 0.3;

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
        $bestUnidades = json_encode($bestUnidades);

        // Avaliacoes por mes (qtd)
        $avaliacoesMes = [];
        $aux = [
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

        foreach ($avaliacoes as $avaliacao) {
            $ano = formatarDataHora($avaliacao->created_at, 'Y');
            if ($ano == request()->ano) {
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

        $avaliacoesMes = json_encode($avaliacoesMes);


        $dataToView = compact(
            'secretariasSearchSelect',
            'secretariaObj',
            'avaliacoesSecretariaAverage',
            'percentAverage',
            'top5BestUnidades',
            'qtdAvaliacoes',
            'notas',
            'qtdBestUnidades',
            'bestUnidades',
            'avaliacoesMes'
        );

        return view('admin.avaliacoes.resumos.resumo-secretaria', $dataToView);
    }

    public function resumoUnidadeSecr()
    {
        if (auth()->user()->can(Permission::PERMISSION_UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }

        //secretaria
        $secretariaObj = $secretariasSearchSelect->find(request()->secretaria);

        $unidadesSelect = [];
        if (!is_null($secretariaObj)) {
            $unidadesSelect = $secretariaObj->unidades()->orderBy('nome', 'asc')->get();
        }

        //Avaliaçoes da secretaria
        $avaliacoes = Avaliacao::query()
            ->with('unidade', 'unidade.secretaria')
            ->whereHas('unidade.secretaria', function ($query) {
                $query->where('secretaria_id', request()->secretaria);
            })
            ->get();

        //media Geral
        $avaliacoesSecretariaAverage = floatval(number_format($avaliacoes->avg('nota'), 2, '.', ''));
        $percentAverage = $avaliacoesSecretariaAverage / 5 * 100;

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
            $dataSetbestUnidades['fill'] = true;
            $dataSetbestUnidades['tension'] = 0.3;

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
        $bestUnidades = json_encode($bestUnidades);

        // Avaliacoes por mes (qtd)
        $avaliacoesMes = [];
        $aux = [
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

        foreach ($avaliacoes as $avaliacao) {
            $ano = formatarDataHora($avaliacao->created_at, 'Y');
            if ($ano == request()->ano) {
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

        $avaliacoesMes = json_encode($avaliacoesMes);


        $dataToView = compact(
            'secretariasSearchSelect',
            'secretariaObj',
            'unidadesSelect',
            'avaliacoesSecretariaAverage',
            'percentAverage',
            'top5BestUnidades',
            'qtdAvaliacoes',
            'notas',
            'qtdBestUnidades',
            'bestUnidades',
            'avaliacoesMes'
        );


        // $view = view('admin.avaliacoes.resumos.resumo-unidade', $dataToView)->render();
        // dd($view);
        return view('admin.avaliacoes.resumos.unidades.resumo-unidade', $dataToView)->render();
    }

    public function unidadeSecr($secretaria_id)
    {
        $status = false;
        $html = '';

        if (is_int($secretaria_id)) {
            $unidadesSelect = Unidade::query()->where('secretaria_id', $secretaria_id)->orderBy('nome', 'asc')->get();
            $html .= '
            <label class="col-md-2 col-form-label" for="secretaria">Unidade:</label>
            <div class="col-md-10">
                <select id="unidade" class="form-select" name="unidade">
                    <option value="" selected>Selecione</option>
            ';

            foreach ($unidadesSelect as $unidade) {
                $html .= '<option value="' . $unidade->id . '">' . $unidade->nome . '</option>';
            }

            $html .= '</select></div>';

            $status = true;
        }
        $response = [
            'status' => $status,
            'html' => $html,
        ];

        return json_encode($response);
    }

    public function unidadeSecrContent($secretaria_id, $unidade_id)
    {



        //Avaliaçoes da secretaria
        $avaliacoes = Avaliacao::query()
            ->with('unidade', 'unidade.secretaria')
            ->whereHas('unidade.secretaria', function ($query) use ($secretaria_id) {
                $query->where('secretaria_id', $secretaria_id);
            })
            ->get();

        //media Geral
        $avaliacoesSecretariaAverage = floatval(number_format($avaliacoes->avg('nota'), 2, '.', ''));
        $percentAverage = $avaliacoesSecretariaAverage / 5 * 100;

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
            $dataSetbestUnidades['fill'] = true;
            $dataSetbestUnidades['tension'] = 0.3;

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
        $bestUnidades = json_encode($bestUnidades);

        // Avaliacoes por mes (qtd)
        $avaliacoesMes = [];
        $aux = [
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

        foreach ($avaliacoes as $avaliacao) {
            $ano = formatarDataHora($avaliacao->created_at, 'Y');
            if ($ano == request()->ano) {
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

        $avaliacoesMes = json_encode($avaliacoesMes);


        $dataToView = compact(
            'unidadesSelect',
            'avaliacoesSecretariaAverage',
            'percentAverage',
            'top5BestUnidades',
            'qtdAvaliacoes',
            'notas',
            'qtdBestUnidades',
            'bestUnidades',
            'avaliacoesMes'
        );


        $view = view('admin.avaliacoes.resumos.unidades.resumo-unidade-content', $dataToView)->render();
        return json_encode($view);
    }
}
