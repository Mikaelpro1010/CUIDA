<?php

namespace App\Http\Controllers\Gerenciar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentosController extends Controller
{
    public function visualizarCadastroOficios(){
        return view('admin/gerenciar/documentos/cadastrarOficios');
    }
}
