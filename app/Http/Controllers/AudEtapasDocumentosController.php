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
}
