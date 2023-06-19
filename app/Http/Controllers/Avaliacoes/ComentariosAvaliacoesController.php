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
        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_LIST);  // se o usuario não tiver permissão para acessar qualquer secretaria
        // Esse filtro pode ser por unidade setor ou secretaria
        $avaliacoes = Avaliacao::query()
            ->with('setor', 'setor.unidade', 'setor.unidade.secretaria') // with é um metodo do eloquent que retorna as relações  avaliacao->setor->unidade->secretaria 
            ->where('comentario', '!=', null)
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
            ->when(is_numeric(request()->pesq_nota), function ($query) {   // quando o campo de pesquisa for preenchido por nota
                $query->where('nota', request()->pesq_nota);
            })
            ->when(
                request()->data_inicial || request()->data_final,
                function ($query) {
                    $query->when(request()->data_inicial, function ($query) {
                        $query->whereDate('created_at', '>=', request()->data_inicial);  // quando o campo de pesquisa for preenchido por data inicial
                    })
                        ->when(request()->data_final, function ($query) {
                            $query->whereDate('created_at', '<=', request()->data_final); // quando o campo de pesquisa for preenchido por data final
                        });
                },
                function ($query) {
                    $query->whereDate('created_at', '>=', now()->subDays(30)); // quando o campo de pesquisa não for preenchido
                }
            )
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->all());

        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get(); // retorna todas as secretarias
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get(); // retorna as secretarias do usuario
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

        return view('admin.avaliacoes.comentarios-avaliacoes.comentarios-listar', compact(
            'avaliacoes',
            'secretariasSearchSelect',
            'secretaria',
            'unidades',
            'setores',  // compact retorna um array com os valores passados
            'tiposAvaliacao'
        ));
    }

    public function getSecretariaInfo(): JsonResponse
    {
        if (!request()->ajax()) {  //  se a requisição não for ajax
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_LIST);

        if (
            array_search(request()->secretaria_id, auth()->user()->secretarias()->pluck('id')->toArray()) === false
            && !auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)  // se o usuario não tiver permissão para acessar qualquer secretaria
        ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $unidades = Unidade::query()
            ->where('secretaria_id', request()->secretaria_id)          // retorna as unidades da secretaria
            ->get(['id', 'nome']);
        $tipo_avaliacao = TipoAvaliacao::query()
            ->where('secretaria_id', request()->secretaria_id)
            ->get(['id', 'nome']);

        return response()->json(compact('unidades', 'tipo_avaliacao'));
    }

    public function getSetoresInfo()
    {
        if (!request()->ajax()) {
            abort(Response::HTTP_NOT_FOUND);          // se a requisição não for ajax
        }

        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_LIST);  // se o usuario não tiver permissão para acessar qualquer secretaria

        $unidade = Unidade::findOrFail(request()->unidade_id); // retorna a unidade com o id passado

        $unidade->userCanAccess();  // se o usuario não tiver permissão para acessar qualquer secretaria

        $unidade->load(['setores' => function ($query) {  // carrega os setores da unidade load 
            $query->ativo();
        }]);

        $setores = $unidade->setores->map(function ($setor) {    // retorna os setores da unidade map está percorrendo o array setores e retornando um array com os campos id e nome   
            return [
                'id' => $setor->id,
                'nome' => $setor->nome,
            ];
        });

        return response()->json(compact('setores'));
    }

    public function exportComments(Request $request) // exporta os comentarios e avaliações para o excel usando o metodo export do trait ExportExcel e o metodo get do eloquent a consulta é feita usando o metodo query e o metodo when para fazer os filtros
    {
        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_EXPORT);  // se o usuario não tiver permissão para acessar qualquer secretaria

        $comentarios = Avaliacao::query()   // retorna os comentarios
            ->where('comentario', '!=', null)
            ->leftJoin('setores', 'setores.id', '=', 'avaliacoes.setor_id')
            ->leftJoin('tipo_avaliacoes', 'tipo_avaliacoes.id', '=', 'avaliacoes.tipo_avaliacao_id') // junta as tabelas
            ->leftJoin('unidades', 'unidades.id', '=', 'setores.unidade_id')
            ->leftJoin('secretarias', 'secretarias.id', '=', 'unidades.secretaria_id')
            ->when(   // quando o campo de pesquisa for preenchido por pesquisa unidade setor
                request()->pesquisa_unidade_setor,      // quando o campo de pesquisa for preenchido por pesquisa unidade setor
                function ($query) {
                    $query->whereHas('setor', function ($query) {
                        $query->where('nome', 'ilike', "%" . request()->pesquisa_unidade_setor . "%")
                            ->orWhereHas('unidade', function ($query) {
                                $query->where('nome', 'ilike', "%" . request()->pesquisa_unidade_setor . "%");
                            });
                    });
                }
            )
            ->secretaria(request()->secretaria_pesq)  // quando o campo de pesquisa for preenchido por secretaria
            ->when(request()->tipo_avaliacao, function ($query) {
                $query->where('tipo_avaliacao_id', request()->tipo_avaliacao);  // quando o campo de pesquisa for preenchido por tipo de avaliação
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
                $query->where('nota', request()->pesq_nota);               // quando o campo de pesquisa for preenchido por nota
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
                    $query->whereDate('avaliacoes.created_at', '>=', now()->subDays(30));  // quando o campo de pesquisa não for preenchido
                }
            )
            ->select([  // seleciona os campos que serão retornados e renomeia os campos e
                DB::raw("concat(secretarias.sigla, ' - ', secretarias.nome) as Secretaria"),  // 
                'tipo_avaliacoes.nome as Tipo de Avaliacao',           // retorna os campos   // retorna os campos e renomeia os campos 
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
                'avaliacoes.created_at as Data de Avaliacao', // ava
                'avaliacoes.comentario as Comentario'
            ])
            ->orderBy('avaliacoes.created_at', 'desc') // ordena por data de avaliação
            ->get(); // retorna os dados que foram selecionados acima  usando o metodo get DB::raw é usado para fazer uma consulta sql no laravel sem usar o eloquent 

        ExportExcel::export($comentarios); // exporta os dados para o excel
    }
}
