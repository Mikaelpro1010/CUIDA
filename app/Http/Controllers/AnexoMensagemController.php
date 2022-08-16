<?php

namespace App\Http\Controllers;

use App\Models\Chat\AnexoMensagem;
use Illuminate\Http\Request;

class AnexoMensagemController extends Controller
{
    function downloadAnexo($id)
    {
        $anexo = AnexoMensagem::find($id);
        $pathToFile = storage_path('app\\' . str_replace('/', '\\', $anexo->caminho) . '\\' . $anexo->nome);
        return response()->download($pathToFile, $anexo->nome_original);
    }
}
