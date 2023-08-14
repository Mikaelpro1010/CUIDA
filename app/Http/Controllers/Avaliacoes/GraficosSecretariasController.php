<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Secretaria;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\JsonResponse;

class GraficosSecretariasController extends Controller
{

    public function mostrargraficoPorSecretaria(Request $request): JsonResponse
    {
        return Response::json();
    }


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
            'corGrafico'
        );

        return view('admin.avaliacoes.resumos.secretarias.resumo-secretaria', $dataToView);
    }
}
