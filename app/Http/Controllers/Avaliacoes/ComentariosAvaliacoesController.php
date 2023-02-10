<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Avaliacao;

class ComentariosAvaliacoesController extends Controller
{
    public function listComentarios(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_LIST);

        $avaliacoes = Avaliacao::query()
            ->with('setor', 'setor.unidade', 'setor.unidade.secretaria')
            ->where('comentario', '!=' , null)
            ->when(request()->pesquisa_secretaria, function($query){
                $query->whereHas('setor', function($query){
                    $query->whereHas('unidade', function($query){
                        $query->whereHas('secretaria', function($query){
                            $query->where('sigla', 'ilike', request()->pesquisa_secretaria)
                                ->orWhere('nome', 'ilike', request()->pesquisa_secretaria);
                        });
                    });
                });
            })
            ->when(request()->pesquisa_unidade, function($query){
                $query->whereHas('setor', function($query){
                    $query->whereHas('unidade', function($query){
                        $query->where('nome', 'ilike', request()->pesquisa_unidade);
                    });
                });
            })
            ->when(request()->pesquisa_setor, function($query){
                $query->whereHas('setor', function($query){
                    $query->where('nome', 'ilike', request()->pesquisa_setor);
                });
            })
            ->when(is_numeric(request()->pesq_nota), function($query){
                $query->where('nota', request()->pesq_nota);
            })
            ->orderBy('created_at','desc')
            ->paginate(15)
            ->appends([
                'pesquisa_secretaria' => request()->pesquisa_secretaria,
                'pesquisa_unidade' => request()->pesquisa_unidade,
                'pesquisa_setor' => request()->pesquisa_setor,
                'pesq_nota' => request()->pesq_nota,
            ]);
        
        return view('admin.avaliacoes.comentarios-avaliacoes.comentarios-listar', compact('avaliacoes'));
    }

    public function viewComentarios($id)
    {
        $this->authorize(Permission::GERENCIAR_COMENTARIOS_AVALIACOES_VIEW);
        $avaliacoes = Avaliacao::find($id);

        return view('admin.avaliacoes.comentarios-avaliacoes.comentarios-visualizar', compact('avaliacoes'));
    }
}
