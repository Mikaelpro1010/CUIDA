<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Models\AudEtapasDocumentos;

class AudEtapasDocumentosController extends Controller
{
    public function listarAudEtapasDocumentos(){
        $AudEtapasDocumentos = AudEtapasDocumentos::query()
        ->paginate(10);
        return view('admin/gerenciar/aud-etapas-documentos/listarAudEtapasDocumentos', compact('AudEtapasDocumentos'));
    }

    public function visualizarCadastroAudEtapasDocumentos(){
        return view('admin/gerenciar/aud-etapas-documentos/cadastrarAudEtapasDocumentos');
    }

    public function cadastrarAudEtapasDocumentos(Request $request){
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'icone' => 'required|string|max:255',
            'lado_timeline' => 'required|string|max:255',
        ]);
        
        $AudEtapaDocumento = new AudEtapasDocumentos;
        
        $AudEtapaDocumento->nome = $request->nome;
        $AudEtapaDocumento->icone = $request->icone;
        $AudEtapaDocumento->lado_timeline = $request->lado_timeline;
        $AudEtapaDocumento->cadastrado_por = $request->cadastrado_por;
        
        $AudEtapaDocumento->save();
            
        return redirect()->route('listarAudEtapasDocumentos')->with('success','Etapa de Documento cadastrado com sucesso!');
    }

    public function visualizarAudEtapasDocumentos(AudEtapasDocumentos $AudEtapaDocumento){
        
        return view('admin/gerenciar/aud-etapas-documentos/visualizarAudEtapasDocumentos', compact('AudEtapaDocumento'));
    }
}
