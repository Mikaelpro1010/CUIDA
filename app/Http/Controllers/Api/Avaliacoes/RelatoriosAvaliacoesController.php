<?php

namespace App\Http\Controllers\Api\Avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;

class RelatoriosAvaliacoesController extends Controller
{

    //retorna json com os dados para o grafico de avaliaçoes por mes
    public function avaliacoesPorMesSecretaria(Secretaria $secretaria)
    {
        // $this->authorize(Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW);

        $unidades = $secretaria->unidades()->where('ativo', true)->get();

        $status = false;
        $resposta = null;
        if (preg_match("/^20[0-9]{2}$/", request()->ano)) {
            // Avaliacoes por mes (qtd)
            $resposta = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            $avaliacoesAno = $secretaria
                ->avaliacoes()
                ->where(
                    function ($query) use ($unidades) {
                        $query->whereIn('unidade_secr_id', $unidades->pluck('id'));
                    }
                )
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

        return json_encode($response);
    }

    //Rota de api que retorna o array com as quantidades de avaliaçoes por mes
    public function avaliacoesPorMesUnidade(Unidade $unidade)
    {
        // $this->authorize(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW);

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
        // $this->authorize(Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW);

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
