<?php

namespace App\Http\Controllers\Gerenciar;

use App\Http\Controllers\Controller;
use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Models\Alunos;

class AlunoController extends Controller
{
    public function listarAlunos(){
        $this->authorize(Permission::GERENCIAR_ALUNOS_LIST);

        $alunos = Alunos::query()
        ->paginate(10);
        return view('admin/gerenciar/CRUD_alunos/listarAlunos', compact('alunos'));
    }

    public function visualizarCadastroAluno(){
        $this->authorize(Permission::GERENCIAR_ALUNOS_CREATE);
        return view('admin/gerenciar/CRUD_alunos/cadastrarAlunos');
    }
    
    public function cadastrarAlunos(Request $request){
        $this->authorize(Permission::GERENCIAR_ALUNOS_CREATE);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nota' => 'required'
        ]);
        
        $aluno = new Alunos;
        
        $aluno->name = $request->name;
        $aluno->nota = $request->nota;
        
        if($aluno->nota > 10 or $aluno->nota < 0 ){
            return redirect()->route('visualizarCadastro')->with('error','Somente é permitido notas de 0 a 10!');
        } else{
            $aluno->save();
            
            return redirect()->route('listarAlunos')->with('success','Aluno cadastrado com sucesso!');
        }
        
    }
    
    public function visualizarAluno(Aluno $aluno){
        $this->authorize(Permission::GERENCIAR_ALUNOS_VIEW);
        
        return view('admin/gerenciar/CRUD_alunos/visualizarAluno', compact('aluno'));
    }
    
    public function editarAluno(Aluno $aluno){
        $this->authorize(Permission::GERENCIAR_ALUNOS_EDIT);
        
        return view('admin/gerenciar/CRUD_alunos/editarAluno', compact('aluno'));
    }
    
    public function atualizarAluno(Request $request, Aluno $aluno){
        $this->authorize(Permission::GERENCIAR_ALUNOS_EDIT);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nota' => 'required'
        ]);
        
        $aluno->name = $request->name;
        $aluno->nota = $request->nota;
        
        $aluno->save();
        
        return redirect()->route('listarAlunos')->with('success','Aluno editado com sucesso!');
    }
    
    public function deletarAluno(Request $request){
        $this->authorize(Permission::GERENCIAR_ALUNOS_DELETE);

        $aluno = Alunos::find($request->id);
        $aluno->delete();
        return redirect()->route('listarAlunos')->with('success','Aluno deletado com sucesso!');
    }
}

