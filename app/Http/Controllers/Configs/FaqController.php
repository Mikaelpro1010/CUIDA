<?php

namespace App\Http\Controllers\Configs;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function listFAQ()
    {
        $this->authorize(Permission::GERENCIAR_FAQS_LIST);
        $faqs = FAQ::query()
            ->when(request()->pesquisa, function($query){
                $query->where('pergunta', 'ilike', "%". request()->pesquisa."%");
            })
            ->orderBy('ordem', 'asc')
            ->paginate(40)
            ->appends(
                ['pesquisa'=>request()->pesquisa]
            );

            return view('admin.config.faq.faq-listar', compact('faqs'));
    }

    public function createFAQ()
    {
        $this->authorize(Permission::GERENCIAR_FAQS_CREATE);
        return view('admin.config.faq.faq-criar');
    }

    public function storeFAQ(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_FAQS_CREATE);
        $request->validate([
            'pergunta' => 'required|string|max:255',
            'resposta' => 'required|string|max:255',
        ]);

        $faq = new FAQ();

        $faq->ativo = true;
        $faq->ordem = 0;
        $faq->pergunta = $request->pergunta;
        $faq->resposta = $request->resposta;
        
        $faq->save();
        
        return redirect()->route('get-faq-list')->with('success', 'FAQ cadastrado com sucesso com sucesso!');
    }
    
    public function viewFAQ($id)
    {
        
        $this->authorize(Permission::GERENCIAR_FAQS_VIEW);
        $faq = FAQ::find($id);
        return view('admin.config.faq.faq-visualizar', ['faq' => $faq]);
    }
    
    public function editFAQ(FAQ $FAQ)
    {
        $this->authorize(Permission::GERENCIAR_FAQS_EDIT);
        return view('admin.config.faq.faq-editar', compact('FAQ'));
    }
    
    public function updateFAQ(Request $request, FAQ $FAQ)
    {
        $this->authorize(Permission::GERENCIAR_FAQS_EDIT);
        $request->validate([
            'pergunta' => 'required|string|max:255',
            'resposta' => 'required|string|max:255',
        ]);
        
        $FAQ->pergunta = $request->pergunta;
        $FAQ->resposta = $request->resposta;
        $FAQ->save();
        
        return redirect()->route('get-faq-list', $FAQ->id)->with('success', 'Atualizado com sucesso!');
    }

    public function deleteFAQ(FAQ  $FAQ)
    {
        $this->authorize(Permission::GERENCIAR_FAQS_DELETE);
        $FAQ->delete();
        return redirect()->route('get-faq-list')->with('success', 'Deletado com sucesso!');
    }

    public function toggleFAQStatus($id)
    {
        $faqs = FAQ::find($id);
        $faqs->ativo = !$faqs->ativo;
        $faqs->save();

        if($faqs->ativo){
            return redirect()->route('get-faq-list')->with('success', 'FAQ ativado com sucesso!');
        } else{
            return redirect()->route('get-faq-list')->with('success', 'FAQ desativado com sucesso!');
        }
        return redirect()->route('get-faq-list');
    }

    public function orderFAQ(Request $request){
        foreach($request->ordem as $key=>$ordem){
            $faq = Faq::find($ordem);
            $faq->ordem = ++$key;
            $faq->save();
        }
        return json_encode(['success'=> 'true', 'success'=> 'Ordem alterada com sucesso!']);
    }
}
