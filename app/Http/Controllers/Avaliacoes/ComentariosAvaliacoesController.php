<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Setor;
use App\Models\Secretaria;

class ComentariosAvaliacoesController extends Controller
{
    public function listComentarios(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_LIST);

        $avaliacoes = Avaliacao::query()
            ->with('setor', 'setor.unidade', 'setor.unidade.secretaria')
            ->where('comentario', '!=', null)
            ->when(
                request()->secretaria_pesq,
                function ($query) {
                    if (
                        auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA) ||
                        in_array(request()->secretaria_pesq, auth()->user()->secretarias->pluck('id')->toArray())
                    ) {
                        $query->whereHas('setor', function ($query) {
                            $query->whereHas('unidade', function ($query) {
                                $query->where('secretaria_id', request()->secretaria_pesq);
                            });
                        });
                    } else {
                        $query->whereHas('setor', function ($query) {
                            $query->whereHas('unidade', function ($query) {
                                $query->whereIn('secretaria_id', auth()->user()->secretarias->pluck('id'));
                            });
                        });
                    }
                },
                function ($query) {
                    if (auth()->user()->cant(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
                        $query->whereHas('setor', function ($query) {
                            $query->whereHas('unidade', function ($query) {
                                $query->whereIn('secretaria_id', auth()->user()->secretarias->pluck('id'));
                            });
                        });
                    }
                }
            )
            ->when(
                request()->pesquisa_unidade_setor,
                function ($query) {
                    $query->whereHas('setor', function ($query) {
                        $query->whereHas('unidade', function ($query) {
                            $query->where('nome', 'ilike', "%".request()->pesquisa_unidade_setor."%");
                        })
                        ->orWhere('nome', 'ilike', "%".request()->pesquisa_unidade_setor."%");
                    });
                }
            )
            ->when(is_numeric(request()->pesq_nota), function ($query) {
                $query->where('nota', request()->pesq_nota);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends([
                'secretaria_pesq' => request()->secretaria_pesq,
                'pesquisa_unidade_setor' => request()->pesquisa_unidade_setor,
                'pesq_nota' => request()->pesq_nota,
            ]);
        if (auth()->user()->can(Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA)) {
            $secretariasSearchSelect = Secretaria::query()->orderBy('nome', 'asc')->get();
        } else {
            $secretariasSearchSelect = auth()->user()->secretarias()->orderBy('nome', 'asc')->get();
        }

        return view('admin.avaliacoes.comentarios-avaliacoes.comentarios-listar', compact('avaliacoes', 'secretariasSearchSelect'));
    }

    public function viewComentarios($id)
    {
        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_VIEW);
        $avaliacoes = Avaliacao::find($id);

        return view('admin.avaliacoes.comentarios-avaliacoes.comentarios-visualizar', compact('avaliacoes'));
    }
}
