<?php

namespace App\Http\Controllers\Gerenciar;

use App\Http\Controllers\Controller;
use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Models\AudEtapasDocumentos;
use App\Models\User;

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
        
        $user = auth()->user();

        $mensagens = [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'nome.unique' => 'O nome já está em uso, escolha outro.',
            'icone.required' => 'O campo icone é obrigatório.',
            'icone.string' => 'O campo icone deve ser uma string.',
            'icone.max' => 'O campo icone não pode ter mais de 255 caracteres.',
            'lado_timeline.required' => 'O campo lado_timeline é obrigatório.',
            'lado_timeline.string' => 'O campo lado_timeline deve ser uma string.',
        ];

        $request->validate([
            'nome' => 'required|string|max:255|unique:aud_etapas_documentos',
            'icone' => 'required|string|max:255',
            'lado_timeline' => 'required',
        ], $mensagens);
        
        $AudEtapaDocumento = new AudEtapasDocumentos;
        
        $AudEtapaDocumento->nome = $request->nome;
        $AudEtapaDocumento->icone = $request->icone;
        $AudEtapaDocumento->lado_timeline = $request->lado_timeline;
        $AudEtapaDocumento->usuario_id = $user->id;
        
        $AudEtapaDocumento->save();
            
        return redirect()->route('listarAudEtapasDocumentos')->with('success','Etapa de Documento cadastrado com sucesso!');
    }

    public function visualizarAudEtapaDocumento(AudEtapasDocumentos $AudEtapaDocumento){
        
        return view('admin/gerenciar/aud-etapas-documentos/visualizarAudEtapasDocumentos', compact('AudEtapaDocumento'));
    }

    public function editarAudEtapaDocumento(AudEtapasDocumentos $AudEtapaDocumento){
        
        return view('admin/gerenciar/aud-etapas-documentos/editarAudEtapasDocumentos', compact('AudEtapaDocumento'));
    }

    public function atualizarAudEtapaDocumento(Request $request, AudEtapasDocumentos $AudEtapaDocumento){
        
        $mensagens = [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'nome.unique' => 'O nome já está em uso, escolha outro.',
            'icone.required' => 'O campo icone é obrigatório.',
            'icone.string' => 'O campo icone deve ser uma string.',
            'icone.max' => 'O campo icone não pode ter mais de 255 caracteres.',
            'lado_timeline.required' => 'O campo lado_timeline é obrigatório.',
            'lado_timeline.string' => 'O campo lado_timeline deve ser uma string.',
        ];

        $request->validate([
            'nome' => 'required|string|max:255|unique:aud_etapas_documentos',
            'icone' => 'required|string|max:255',
            'lado_timeline' => 'required',
        ], $mensagens);
        
        $AudEtapaDocumento->nome = $request->nome;
        $AudEtapaDocumento->icone = $request->icone;
        $AudEtapaDocumento->lado_timeline = $request->lado_timeline;
        
        $AudEtapaDocumento->save();
        
        return redirect()->route('listarAudEtapasDocumentos')->with('success','Etapa de Documento editado com sucesso!');
    }

    public function deletarAudEtapaDocumento(Request $request){

        $AudEtapaDocumento = AudEtapasDocumentos::find($request->id);
        $AudEtapaDocumento->delete();
        return redirect()->route('listarAudEtapasDocumentos')->with('success','Etapa de Documento deletado com sucesso!');
    }
}

