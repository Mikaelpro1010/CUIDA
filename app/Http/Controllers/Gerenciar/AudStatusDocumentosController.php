<?php

namespace App\Http\Controllers\Gerenciar;

use App\Http\Controllers\Controller;
use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Models\AudStatusDocumentos;

class AudStatusDocumentosController extends Controller
{
    public function listarAudStatusDocumentos(){
        $AudStatusDocumentos = AudStatusDocumentos::query()
        ->paginate(10);
        return view('admin/gerenciar/aud-status-documentos/listarAudStatusDocumentos', compact('AudStatusDocumentos'));
    }

    public function visualizarCadastroAudStatusDocumentos(){
        return view('admin/gerenciar/aud-status-documentos/cadastrarAudStatusDocumentos');
    }

    public function cadastrarAudStatusDocumentos(Request $request){
        
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        
        $AudStatusDocumento = new AudStatusDocumentos;
        
        $AudStatusDocumento->nome = $request->nome;
        
        $AudStatusDocumento->save();
            
        return redirect()->route('listarAudStatusDocumentos')->with('success','Status de Status cadastrado com sucesso!');
    }

    public function visualizarAudStatusDocumento(AudStatusDocumentos $AudStatusDocumento){
        
        return view('admin/gerenciar/aud-status-documentos/visualizarAudStatusDocumentos', compact('AudStatusDocumento'));
    }

    public function editarAudStatusDocumento(AudStatusDocumentos $AudStatusDocumento){
        
        return view('admin/gerenciar/aud-status-documentos/editarAudStatusDocumentos', compact('AudStatusDocumento'));
    }

    public function atualizarAudStatusDocumento(Request $request, AudStatusDocumentos $AudStatusDocumento){
        
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        
        $AudStatusDocumento->nome = $request->nome;
        
        $AudStatusDocumento->save();
        
        return redirect()->route('listarAudStatusDocumentos')->with('success','Status de Documento editado com sucesso!');
    }

    public function deletarAudStatusDocumento(Request $request){

        $AudStatusDocumento = AudStatusDocumentos::find($request->id);
        $AudStatusDocumento->delete();
        return redirect()->route('listarAudStatusDocumentos')->with('success','Status de Documento deletado com sucesso!');
    }
}
