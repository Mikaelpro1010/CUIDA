<?php

namespace App\Http\Controllers\Publico;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FAQ;

class FaqController extends Controller
{
    public function paginaInicial()
    {
        $faqs = FAQ::where('ativo', true)->get();

        return view('public.pagina-inicial', ['faqs' => $faqs]);  
    }
}
