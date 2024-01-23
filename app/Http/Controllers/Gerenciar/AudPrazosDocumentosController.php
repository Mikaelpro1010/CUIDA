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
}
