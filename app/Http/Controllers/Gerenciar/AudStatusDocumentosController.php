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
}
