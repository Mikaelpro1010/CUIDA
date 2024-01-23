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
            
        return redirect()->route('listarAudStatusDocumentos')->with('success','Status de Prazo cadastrado com sucesso!');
    }

    public function visualizarAudStatusDocumento(AudStatusDocumentos $AudStatusDocumento){
        
        return view('admin/gerenciar/aud-status-documentos/visualizarAudStatusDocumentos', compact('AudStatusDocumento'));
    }
}
