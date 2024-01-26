<?php

namespace App\Http\Controllers\Gerenciar;

use App\Http\Controllers\Controller;
use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Models\AudTiposDocumentos;

class AudTiposDocumentosController extends Controller
{
    public function listarAudTiposDocumentos(){
        $AudTiposDocumentos = AudTiposDocumentos::query()
        ->paginate(10);
        return view('admin/gerenciar/aud-tipos-documentos/listarAudTiposDocumentos', compact('AudTiposDocumentos'));
    }

    public function visualizarCadastroAudTiposDocumentos(){
        return view('admin/gerenciar/aud-tipos-documentos/cadastrarAudTiposDocumentos');
    }

    public function cadastrarAudTiposDocumentos(Request $request){
        
        $mensagens = [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'interno.required' => 'O campo interno é obrigatório.',
            'interno.integer' => 'O campo interno deve ser um número inteiro.',
            'interno.min' => 'O campo interno deve ser maior que zero.',
        ];
    
        $request->validate([
            'nome' => 'required|string|max:255',
            'interno' => 'required|integer|min:1',
        ], $mensagens);
        
        $AudTipoDocumento = new AudTiposDocumentos;
        
        $AudTipoDocumento->nome = $request->nome;
        $AudTipoDocumento->interno = $request->interno;
        
        $AudTipoDocumento->save();
            
        return redirect()->route('listarAudTiposDocumentos')->with('success','Tipos de Documentos cadastrado com sucesso!');
    }

    public function visualizarAudTipoDocumento(AudTiposDocumentos $AudTipoDocumento){
        
        return view('admin/gerenciar/aud-tipos-documentos/visualizarAudTiposDocumentos', compact('AudTipoDocumento'));
    }

    public function editarAudTipoDocumento(AudTiposDocumentos $AudTipoDocumento){
        
        return view('admin/gerenciar/aud-tipos-documentos/editarAudTiposDocumentos', compact('AudTipoDocumento'));
    }

    public function atualizarAudTipoDocumento(Request $request, AudTiposDocumentos $AudTipoDocumento){
        
        $mensagens = [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'interno.required' => 'O campo interno é obrigatório.',
            'interno.integer' => 'O campo interno deve ser um número inteiro.',
            'interno.min' => 'O campo interno deve ser maior que zero.',
        ];
    
        $request->validate([
            'nome' => 'required|string|max:255',
            'interno' => 'required|integer|min:1',
        ], $mensagens);
        
        $AudTipoDocumento->nome = $request->nome;
        $AudTipoDocumento->interno = $request->interno;
        
        $AudTipoDocumento->save();
        
        return redirect()->route('listarAudTiposDocumentos')->with('success','Tipos de Documento editado com sucesso!');
    }

    public function deletarAudTipoDocumento(Request $request){

        $AudTipoDocumento = AudTiposDocumentos::find($request->id);
        $AudTipoDocumento->delete();
        return redirect()->route('listarAudTiposDocumentos')->with('success','Tipos de Documento deletado com sucesso!');
    }
}
