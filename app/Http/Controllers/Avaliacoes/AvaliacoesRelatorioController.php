<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class AvaliacoesRelatorioController extends Controller
{
    public function listAvaliacao(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_QUANTIDADE_AVALIACOES_LIST);
        $data = Avaliacao::query()
            ->selectRaw('
        unidades.nome,setores.nome as setores, secretarias.nome as secretaria,
        SUM(CASE WHEN avaliacoes.nota = 2 THEN 1 ELSE 0 END) AS muito_ruim,
        SUM(CASE WHEN avaliacoes.nota = 4 THEN 1 ELSE 0 END) AS ruim,
        SUM(CASE WHEN avaliacoes.nota = 6 THEN 1 ELSE 0 END) AS neutro,
        SUM(CASE WHEN avaliacoes.nota = 8 THEN 1 ELSE 0 END) AS bom,
        SUM(CASE WHEN avaliacoes.nota = 10 THEN 1 ELSE 0 END) AS muito_bom,
        SUM(CASE WHEN avaliacoes.nota IN (2, 4, 6, 8, 10) THEN 1 ELSE 0 END) AS total_av
    ')->from('avaliacoes')
            ->leftJoin('setores', 'setores.id', '=', 'avaliacoes.setor_id')
            ->leftJoin('unidades', 'unidades.id', '=', 'setores.unidade_id')
            ->leftJoin('secretarias', 'secretarias.id', '=', 'unidades.secretaria_id')
            ->groupBy('unidades.nome', 'setores.nome', 'secretarias.nome')
            ->when(
                request()->pesquisa_unidade_setor,  // quando o campo de pesquisa for preenchido por pesquisa unidade setor
                function ($query) {
                    $query->whereHas('setor', function ($query) {
                        $query->where('nome', 'ilike', "%" . request()->pesquisa_unidade_setor . "%")
                            ->orWhereHas('unidade', function ($query) {
                                $query->where('nome', 'ilike', "%" . request()->pesquisa_unidade_setor . "%");
                            });
                    });
                }
            )
            ->secretaria(request()->secretaria_pesq)    // quando o campo de pesquisa for preenchido por secretaria
            ->when(request()->tipo_avaliacao, function ($query) {
                $query->where('tipo_avaliacao_id', request()->tipo_avaliacao);
            })
            ->when(request()->unidade_pesq, function ($query) { // quando o campo de pesquisa for preenchido por unidade
                $query->whereHas('setor', function ($query) {
                    $query->where('unidade_id', request()->unidade_pesq);
                });
            })
            ->when(request()->setor_pesq, function ($query) {   // quando o campo de pesquisa for preenchido por setor
                $query->where('setor_id', request()->setor_pesq);
            })
            ->when(
                request()->data_inicial || request()->data_final,
                function ($query) {
                    $query->when(request()->data_inicial, function ($query) {
                        $query->whereDate('avaliacoes.created_at', '>=', request()->data_inicial);
                    })
                        ->when(request()->data_final, function ($query) {
                            $query->whereDate('avaliacoes.created_at', '<=', request()->data_final);
                        });
                },
                function ($query) {
                    $query->whereDate('avaliacoes.created_at', '>=', now()->subDays(30));
                }
            )
            ->paginate(15)->appends(request()->all());

        // dd($data);


        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }

        $secretaria = [];
        $unidades = [];
        $setores = [];
        $tiposAvaliacao = [];
        if (request()->secretaria_pesq) {
            $key = array_search(request()->secretaria_pesq, $secretariasSearchSelect->pluck('id')->toArray()); // retorna o indice do array que contem o valor passado
            if ($key !== false) {
                $secretaria = $secretariasSearchSelect[$key]->load(['unidades' => function ($query) { // retorna a secretaria com o id passado 
                    $query->ativo();
                }, 'unidades.setores' => function ($query) {  // retorna as unidades da secretaria
                    $query->when(request()->unidade_pesq, function ($query) {
                        $query->where('unidade_id', request()->unidade_pesq);  // unidade_pesq é o id da unidade
                    })->ativo();
                }, 'tiposAvaliacao']);

                $unidades = $secretaria->unidades;
                $keyUnidade = array_search(request()->unidade_pesq, $unidades->pluck('id')->toArray());  //retorna o indice do array que contem o valor passado e
                if ($keyUnidade !== false) {
                    $setores = $unidades[$keyUnidade]->setores; // retorna os setores da unidade
                }
                $tiposAvaliacao  = $secretaria->tiposAvaliacao;  // retorna os tipos de avaliação da secretaria 
            }
        }


        return view('admin.avaliacoes.quantidade-avaliacoes.listar-qtd-avaliacoes', compact(
            'data',
            'secretariasSearchSelect',
            'secretaria',
            'unidades',
            'setores',
            'tiposAvaliacao'
        ));
    }

    public function getSecretariaInfo(): JsonResponse
    {
        if (!request()->ajax()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->authorize(Permission::GERENCIAR_QUANTIDADE_AVALIACOES_LIST);
        if (
            array_search(request()->secretaria_id, auth()->user()->secretarias()->pluck('id')->toArray()) === false
            && !auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)  // se o usuario não tiver permissão para acessar qualquer secretaria
        ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $unidades = Unidade::query()
            ->where('secretaria_id', request()->secretaria_id)          // retorna as unidades da secretaria
            ->get(['id', 'nome']);


        return response()->json(compact('unidades'));
    }

    public function getSetoresInfo()
    {
        if (!request()->ajax()) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize(Permission::GERENCIAR_QUANTIDADE_AVALIACOES_LIST);  // se o usuario não tiver permissão para acessar qualquer secretaria

        $unidade = Unidade::findOrFail(request()->unidade_id);

        $unidade->userCanAccess();

        $unidade->load(['setores' => function ($query) {
            $query->ativo();
        }]);

        $setores = $unidade->setores->map(function ($setor) {
            return [
                'id' => $setor->id,
                'nome' => $setor->nome,
            ];
        });

        return response()->json(compact('setores'));
    }

    public function exportquantidadeAvaliacoes(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_QUANTIDADE_AVALIACOES_EXPORT);

        $qtdavaliacoes = Avaliacao::query()
            ->leftJoin('setores', 'setores.id', '=', 'avaliacoes.setor_id')
            ->leftJoin('tipo_avaliacoes', 'tipo_avaliacoes.id', '=', 'avaliacoes.tipo_avaliacao_id')
            ->leftJoin('unidades', 'unidades.id', '=', 'setores.unidade_id')
            ->leftJoin('secretarias', 'secretarias.id', '=', 'unidades.secretaria_id')
            ->when(
                request()->pesquisa_unidade_setor,
                function ($query) {
                    $query->whereHas('setor', function ($query) {
                        $query->where('nome', 'ilike', "%" . request()->pesquisa_unidade_setor . "%")
                            ->orWhereHas('unidade', function ($query) {
                                $query->where('nome', 'ilike', "%" . request()->pesquisa_unidade_setor . "%");
                            });
                    });
                }
            )
            ->secretaria(request()->secretaria_pesq)
            ->when(request()->tipo_avaliacao, function ($query) {
                $query->where('tipo_avaliacao_id', request()->tipo_avaliacao);
            })
            ->when(request()->unidade_pesq, function ($query) {
                $query->whereHas('setor', function ($query) {
                    $query->where('unidade_id', request()->unidade_pesq);
                });
            })
            ->when(request()->setor_pesq, function ($query) {
                $query->where('setor_id', request()->setor_pesq);
            })
            ->when(is_numeric(request()->pesq_nota), function ($query) {
                $query->where('nota', request()->pesq_nota);
            })
            ->when(
                request()->data_inicial || request()->data_final,
                function ($query) {
                    $query->when(request()->data_inicial, function ($query) {
                        $query->whereDate('avaliacoes.created_at', '>=', request()->data_inicial);
                    })
                        ->when(request()->data_final, function ($query) {
                            $query->whereDate('avaliacoes.created_at', '<=', request()->data_final);
                        });
                },
                function ($query) {
                    $query->whereDate('avaliacoes.created_at', '>=', now()->subDays(30));
                }
            )

            ->select([
                DB::raw("unidades.nome as nome,
                SUM(CASE WHEN avaliacoes.nota = 2 THEN 1 ELSE 0 END) AS muito_ruim,
                SUM(CASE WHEN avaliacoes.nota = 4 THEN 1 ELSE 0 END) AS ruim,
                SUM(CASE WHEN avaliacoes.nota = 6 THEN 1 ELSE 0 END) AS neutro,
                SUM(CASE WHEN avaliacoes.nota = 8 THEN 1 ELSE 0 END) AS bom,
                SUM(CASE WHEN avaliacoes.nota = 10 THEN 1 ELSE 0 END) AS muito_bom,
                COUNT(*) AS total_av,
                'Avaliacao' AS tipo
            ")
            ])
            ->groupBy('unidades.nome')
            ->unionAll(
                Avaliacao::query()
                    ->leftJoin('setores', 'setores.id', '=', 'avaliacoes.setor_id')
                    ->leftJoin('tipo_avaliacoes', 'tipo_avaliacoes.id', '=', 'avaliacoes.tipo_avaliacao_id')
                    ->leftJoin('unidades', 'unidades.id', '=', 'setores.unidade_id')
                    ->leftJoin('secretarias', 'secretarias.id', '=', 'unidades.secretaria_id')
                    ->select([
                        DB::raw("'Total' as nome,
                        SUM(CASE WHEN avaliacoes.nota = 2 THEN 1 ELSE 0 END) AS muito_ruim,
                        SUM(CASE WHEN avaliacoes.nota = 4 THEN 1 ELSE 0 END) AS ruim,
                        SUM(CASE WHEN avaliacoes.nota = 6 THEN 1 ELSE 0 END) AS neutro,
                        SUM(CASE WHEN avaliacoes.nota = 8 THEN 1 ELSE 0 END) AS bom,
                        SUM(CASE WHEN avaliacoes.nota = 10 THEN 1 ELSE 0 END) AS muito_bom,
                        COUNT(*) AS total_av,
                        'Total' AS tipo
                    ")
                    ])
            )
            ->get();

        ExportExcel::export($qtdavaliacoes);
    }
}
