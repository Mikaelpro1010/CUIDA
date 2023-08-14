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
    public function resumoSecretaria(Secretaria $secretaria, Unidade $unidade): 
    {
        $this->authorize(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW);

        $unidade->userCanAccess();

  
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
}
