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
        
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        
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
        
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        
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
