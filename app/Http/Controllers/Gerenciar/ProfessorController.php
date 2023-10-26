<?php

namespace App\Http\Controllers\Gerenciar;

use Illuminate\Http\Request;
use App\Constants\Permission;
use App\Models\Professores;
use App\Http\Controllers\Controller;


class ProfessorController extends Controller
{
    public function listarProfessores(){
        $this->authorize(Permission::GERENCIAR_PROFESSORES_LIST);

        $professores = Professores::query()
        ->paginate(10);
        return view('admin/gerenciar/CRUD_professores/listarProfessores', compact('professores'));
    }

    public function visualizarCadastroProfessor(){
        $this->authorize(Permission::GERENCIAR_PROFESSORES_CREATE);
        return view('admin/gerenciar/CRUD_professores/cadastrarProfessores');
    }
    
    public function cadastrarProfessores(Request $request){
        $this->authorize(Permission::GERENCIAR_PROFESSORES_CREATE);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nota' => 'required'
        ]);
        
        $professor = new Professores;
        
        $professor->name = $request->name;
        $professor->nota = $request->nota;
        
        if($professor->nota > 10 or $professor->nota < 0 ){
            return redirect()->route('visualizarCadastro')->with('error','Somente é permitido notas de 0 a 10!');
        } else{
            $professor->save();
            
            return redirect()->route('listarProfessores')->with('success','Professor cadastrado com sucesso!');
        }
        
    }
    
    public function visualizarProfessor(Professores $professor){
        $this->authorize(Permission::GERENCIAR_PROFESSORES_VIEW);
        
        return view('admin/gerenciar/CRUD_professores/visualizarProfessor', compact('professor'));
    }
    
    public function editarProfessor(Professor $Professor){
        $this->authorize(Permission::GERENCIAR_PROFESSORES_EDIT);
        
        return view('admin/gerenciar/CRUD_professores/editarProfessor', compact('professor'));
    }
    
    public function atualizarProfessor(Request $request, Professor $Professor){
        $this->authorize(Permission::GERENCIAR_PROFESSORES_EDIT);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nota' => 'required'
        ]);
        
        $professor->name = $request->name;
        $professor->nota = $request->nota;
        
        $professor->save();
        
        return redirect()->route('listarProfessores')->with('success','Professor editado com sucesso!');
    }
    
    public function deletarProfessor(Request $request){
        $this->authorize(Permission::GERENCIAR_PROFESSORES_DELETE);

        $professor = Professores::find($request->id);
        $professor->delete();
        return redirect()->route('listarProfessores')->with('success','Professor deletado com sucesso!');
    }
}
