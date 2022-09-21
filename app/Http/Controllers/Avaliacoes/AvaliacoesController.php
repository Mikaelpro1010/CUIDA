<?php

namespace App\Http\Controllers\Avaliacoes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class AvaliacoesController extends Controller
{
    public function resumo()
    {
        return view('admin.avaliacoes.resumo');
    }

    public function resumoSecretaria()
    {
        return view('admin.avaliacoes.resumo');
    }

    public function resumoUnidadeSecr()
    {
        return view('admin.avaliacoes.resumo');
    }
}
