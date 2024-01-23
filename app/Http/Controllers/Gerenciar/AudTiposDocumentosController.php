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
}
