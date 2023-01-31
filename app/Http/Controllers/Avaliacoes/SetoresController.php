<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\Unidade;

class SetoresController extends Controller
{
    public function storeSetor(Request $request, Unidade $unidade){
        $this->authorize(Permission::SETOR_CREATE);

        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        if($unidade != null)
        {
            Setor::query()->create([
                'unidade_id' => $request->unidade,
                'nome' => $request->nome,
                'ativo' => true,
            ]);
            
            return redirect()->route('get-unidades-secr-list', $unidade->id)->with(['success' => 'Setor Cadastrado com Sucesso!']);
        } else{
            return redirect()->route('get-unidades-secr-list')->withError(['unidade' => 'A unidade não existe!']);
        }
    }

    public function updateSetor(Request $request, Setor $setor){
        $this->authorize(Permission::SETOR_EDIT);
        
        $request->validate([ 
            'nome' => 'required|string|max:255',
        ]);

        if($setor != null)
        {
            $setor->nome = $request->nome;
            $setor->ativo = true;
            $setor->save();

            return redirect()
                    ->route('get-unidades-secr-view')
                    ->with(['success' => 'Setor editado com Sucesso!']);
        } else {
            return redirect()
                ->back()
                ->withError(['setor' => 'O setor não existe!']);
        }
    }

    public function deleteSetor(Setor $setor){
        $this->authorize(Permission::SETOR_DELETE);
        $setor->delete();
        return redirect()
                ->route('get-unidades-secr-view')
                ->with(['success' => 'Setor deletado com Sucesso!']);
    }
}
