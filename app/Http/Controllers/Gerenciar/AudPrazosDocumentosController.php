<?php

namespace App\Http\Controllers\Gerenciar;

use App\Http\Controllers\Controller;
use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Models\AudPrazosDocumentos;

class AudPrazosDocumentosController extends Controller
{
    public function listarAudPrazosDocumentos(){
        $AudPrazosDocumentos = AudPrazosDocumentos::query()
        ->paginate(10);
        return view('admin/gerenciar/aud-prazos-documentos/listarAudPrazosDocumentos', compact('AudPrazosDocumentos'));
    }

    public function visualizarCadastroAudPrazosDocumentos(){
        return view('admin/gerenciar/aud-prazos-documentos/cadastrarAudPrazosDocumentos');
    }

    public function cadastrarAudPrazosDocumentos(Request $request){
        
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        
        $AudPrazoDocumento = new AudPrazosDocumentos;
        
        $AudPrazoDocumento->nome = $request->nome;
        
        $AudPrazoDocumento->save();
            
        return redirect()->route('listarAudPrazosDocumentos')->with('success','Prazo de Prazo cadastrado com sucesso!');
    }

    public function visualizarAudPrazoDocumento(AudPrazosDocumentos $AudPrazoDocumento){
        
        return view('admin/gerenciar/aud-prazos-documentos/visualizarAudPrazosDocumentos', compact('AudPrazoDocumento'));
    }

    public function editarAudPrazoDocumento(AudPrazosDocumentos $AudPrazoDocumento){
        
        return view('admin/gerenciar/aud-prazos-documentos/editarAudPrazosDocumentos', compact('AudPrazoDocumento'));
    }
}
