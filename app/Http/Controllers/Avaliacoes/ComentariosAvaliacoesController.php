<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\TipoAvaliacao;
use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Traits\ExportExcel;
use Illuminate\Support\Facades\DB;

class ComentariosAvaliacoesController extends Controller
{

    public function listComentarios(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_LIST);

        $avaliacoes = Avaliacao::query()
            ->with('setor', 'setor.unidade', 'setor.unidade.secretaria')
            ->where('comentario', '!=', null)
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
                        $query->whereDate('created_at', '>=', request()->data_inicial);
                    })
                        ->when(request()->data_final, function ($query) {
                            $query->whereDate('created_at', '<=', request()->data_final);
                        });
                },
                function ($query) {
                    $query->whereDate('created_at', '>=', now()->subDays(30));
                }
            )
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->all());

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
            $key = array_search(request()->secretaria_pesq, $secretariasSearchSelect->pluck('id')->toArray());
            if ($key !== false) {
                $secretaria = $secretariasSearchSelect[$key]->load(['unidades' => function ($query) {
                    $query->ativo();
                }, 'unidades.setores' => function ($query) {
                    $query->when(request()->unidade_pesq, function ($query) {
                        $query->where('unidade_id', request()->unidade_pesq);
                    })->ativo();
                }, 'tiposAvaliacao']);

                $unidades = $secretaria->unidades;
                $keyUnidade = array_search(request()->unidade_pesq, $unidades->pluck('id')->toArray());
                if ($keyUnidade !== false) {
                    $setores = $unidades[$keyUnidade]->setores;
                }
                $tiposAvaliacao  = $secretaria->tiposAvaliacao;
            }
        }

        return view('admin.avaliacoes.comentarios-avaliacoes.comentarios-listar', compact('avaliacoes', 'secretariasSearchSelect', 'secretaria', 'unidades', 'setores', 'tiposAvaliacao'));
    }

    public function getSecretariaInfo(): JsonResponse
    {
        if (!request()->ajax()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_LIST);

        if (
            array_search(request()->secretaria_id, auth()->user()->secretarias()->pluck('id')->toArray()) === false
            && !auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)
        ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $unidades = Unidade::query()
            ->where('secretaria_id', request()->secretaria_id)
            ->get(['id', 'nome']);
        $tipo_avaliacao = TipoAvaliacao::query()
            ->where('secretaria_id', request()->secretaria_id)
            ->get(['id', 'nome']);

        return response()->json(compact('unidades', 'tipo_avaliacao'));
    }

    public function getSetoresInfo()
    {
        if (!request()->ajax()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_LIST);

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

    public function exportComments(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_EXPORT);

        $comentarios = Avaliacao::query()
            ->where('comentario', '!=', null)
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
                DB::raw("concat(secretarias.nome, ' - ', secretarias.sigla) as Secretaria"),
                'tipo_avaliacoes.nome as Tipo de Avaliacao',
                'unidades.nome as Unidade',
                'setores.nome as Setor',
                DB::raw("(case avaliacoes.nota
                    when 2 then 'Muito Ruim'
                    when 4 then 'Ruim'
                    when 6 then 'Neutro'
                    when 8 then 'Bom'
                    when 10 then 'Muito Bom'
                    end)
                    as Avaliacao"),
                'avaliacoes.created_at as data de Avaliacao',
                'avaliacoes.comentario as Comentario'
            ])
            ->orderBy('avaliacoes.created_at', 'desc')
            ->get();

        ExportExcel::export($comentarios);
    }
}
