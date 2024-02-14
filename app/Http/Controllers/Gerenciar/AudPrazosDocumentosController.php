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
        
        $user = auth()->user();

        $mensagens = [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'nome.unique' => 'O nome já está em uso, escolha outro.',
        ];

        $request->validate([
            'nome' => 'required|string|max:255|unique:aud_prazos_documentos',
        ], $mensagens);
        
        $AudPrazoDocumento = new AudPrazosDocumentos;
        
        $AudPrazoDocumento->nome = $request->nome;
        $AudPrazoDocumento->usuario_id = $user->id;
        
        $AudPrazoDocumento->save();
            
        return redirect()->route('listarAudPrazosDocumentos')->with('success','Prazo de Documento cadastrado com sucesso!');
    }

    public function visualizarAudPrazoDocumento(AudPrazosDocumentos $AudPrazoDocumento){
        
        return view('admin/gerenciar/aud-prazos-documentos/visualizarAudPrazosDocumentos', compact('AudPrazoDocumento'));
    }

    public function editarAudPrazoDocumento(AudPrazosDocumentos $AudPrazoDocumento){
        
        return view('admin/gerenciar/aud-prazos-documentos/editarAudPrazosDocumentos', compact('AudPrazoDocumento'));
    }

    public function atualizarAudPrazoDocumento(Request $request, AudPrazosDocumentos $AudPrazoDocumento){
        
        $mensagens = [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não pode ter mais de 255 caracteres.',
        ];

        $request->validate([
            'nome' => 'required|string|max:255',
        ], $mensagens);
        

        if($request->nome !== $AudPrazoDocumento->nome){
            $request->validate([
                'nome' => 'unique:aud_prazos_documentos',
            ], $mensagens);
        }

        $AudPrazoDocumento->nome = $request->nome;
        
        $AudPrazoDocumento->save();
        
        return redirect()->route('listarAudPrazosDocumentos')->with('success','Prazo de Documento editado com sucesso!');
    }

    public function deletarAudPrazoDocumento(Request $request){

        $AudPrazoDocumento = AudPrazosDocumentos::find($request->id);
        $AudPrazoDocumento->delete();
        return redirect()->route('listarAudPrazosDocumentos')->with('success','Prazo de Documento deletado com sucesso!');
    }

}
