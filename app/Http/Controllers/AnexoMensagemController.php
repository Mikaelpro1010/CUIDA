<?php

namespace App\Http\Controllers;

use App\Models\Chat\AnexoMensagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnexoMensagemController extends Controller
{
    function viewAnexo(AnexoMensagem $anexo)
    {
        $path = $anexo->caminho . $anexo->nome;
        if (Storage::disk('msgs_anexos')->exists($path)) {
            return Storage::disk('msgs_anexos')->response($path);
        } else {
            abort(404, 'File not found!');
        }
    }

    function downloadAnexo(AnexoMensagem $anexo)
    {
        $path = $anexo->caminho . $anexo->nome;
        if (Storage::disk('msgs_anexos')->exists($path)) {
            return Storage::disk('msgs_anexos')->download($path, time() . $anexo->nome_original);
        } else {
            abort(404, 'File not found!');
        }
    }
}
