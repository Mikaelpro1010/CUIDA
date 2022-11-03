<?php

namespace App\Http\Controllers;

use App\Models\Chat\AnexoMensagem;
use App\Models\Chat\CanalMensagem;
use App\Models\Chat\Mensagem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MessagesController extends Controller
{
    public function index()
    {
        $canaisMensagem = CanalMensagem::with("manifestacao", "autor")
            ->when(is_numeric(request()->protocolo), function ($query) {
                $query->whereHas('manifestacao', function (Builder $query) {
                    $query->where('protocolo', request()->protocolo);
                });
            })
            ->when(request()->status, function ($query) {
                $query->where('id_status', request()->status);
            })
            ->when(request()->data_inicio, function ($query) {
                $query->where('created_at', '>=', request()->data_inicio);
            })
            ->when(request()->data_fim, function ($query) {
                $query->where('updated_at', '<=', Carbon::parse(request()->data_fim)->addDay());
            });

        $aguardandoResposta = clone $canaisMensagem;
        $aguardandoResposta = $aguardandoResposta->where('id_status', CanalMensagem::STATUS_AGUARDANDO_RESPOSTA)->count();

        $respondido = clone $canaisMensagem;
        $respondido = $respondido->where('id_status', CanalMensagem::STATUS_RESPONDIDO)->count();

        $encerrado = clone $canaisMensagem;
        $encerrado = $encerrado->where('id_status', CanalMensagem::STATUS_ENCERRADO)->count();

        $totalCanaisMsg = CanalMensagem::count();

        $canaisMensagem =
            $canaisMensagem
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends([
                "status" => request()->status,
                "protocolo" => request()->protocolo,
                "data_inicio" => request()->data_inicio,
                "data_fim" => request()->data_fim,
            ]);

        $resposta = [
            "canaisMensagem" => $canaisMensagem,
            "aguardandoResposta" => $aguardandoResposta,
            "respondido" => $respondido,
            "encerrado" => $encerrado,
            "totalCanaisMsg" => $totalCanaisMsg,
        ];

        return view('admin.messages.index', $resposta);
    }

    public function visualizarMsg($id)
    {
        $canalManifestacao = CanalMensagem::with('manifestacao', 'manifestacao.autor')->find($id);

        if (is_null($canalManifestacao)) {
            return redirect()->route('mensagens')->withInput()->with(['warning' => 'Canal não encontrado!']);
        }

        $mensagens = Mensagem::with('anexos', 'canalMensagens', 'canalMensagens.manifestacao', 'canalMensagens.manifestacao.autor')->where('id_canal_mensagem', $id)->get();

        $response = [
            'id' => $id,
            'mensagens' => $mensagens,
            'canalManifestacao' => $canalManifestacao,
        ];

        return view('admin.messages.visualizar', $response);
    }

    public function enviarMsg(Request $request, $id)
    {
        $canalManifestacao = CanalMensagem::find($id);
        if (is_null($canalManifestacao)) {
            return redirect()->route('visualizarMsg', ['id' => $id])->with(['warning' => 'Não foi possivel enviar a mensagem!']);
        }

        $novaMensagem = new Mensagem();
        $novaMensagem->id_canal_mensagem = $id;
        $novaMensagem->id_user = auth()->user()->id;
        $novaMensagem->msg_type = Mensagem::TIPO_OUVIDOR;
        if ($request->mensagem != '') {
            $novaMensagem->mensagem = $request->mensagem;
        } else {
            if ($request->has('anexo')) {
                $novaMensagem->mensagem = 'Anexo(s)';
            } else {
                return redirect()->back()->withInput()->with(['warning' => 'Mensagem vazia e nenhum anexo inserido!']);
            }
        }
        $novaMensagem->save();

        if ($request->has('anexo')) {
            foreach ($request->file('anexo') as $file) {
                $uploadedFile = $file;
                $filename = time() . $uploadedFile->getClientOriginalName();
                $caminho = "public/anexos-msgs/$canalManifestacao->id/$novaMensagem->id/";
                Storage::disk('local')->putFileAs(
                    $caminho,
                    $uploadedFile,
                    $filename
                );

                $upload = new AnexoMensagem();
                $upload->nome = $filename;
                $upload->caminho = $caminho;
                $upload->nome_original = $uploadedFile->getClientOriginalName();
                $upload->id_mensagem = $novaMensagem->id;

                $upload->save();
            }
        }

        if ($request->status == CanalMensagem::STATUS_ENCERRADO) {
            $canalManifestacao->id_status = CanalMensagem::STATUS_ENCERRADO;
        } else {
            $canalManifestacao->id_status = CanalMensagem::STATUS_RESPONDIDO;
        }
        $canalManifestacao->updated_at = Carbon::now();
        $canalManifestacao->save();

        Cache::forget('qtdNotifications' . auth()->user()->id);
        canaisAguardandoRespostaNotification();

        return redirect()->route('visualizarMsg', ['id' => $id])->with(['success' => 'Mensagem Enviada com sucesso!']);
    }

    public function encerrarCanal($id)
    {
        $canalManifestacao = CanalMensagem::find($id);
        if (!is_null($canalManifestacao)) {
            if ($canalManifestacao->id_status != CanalMensagem::STATUS_ENCERRADO) {
                $canalManifestacao->id_status = CanalMensagem::STATUS_ENCERRADO;
                $canalManifestacao->save();

                Cache::forget('qtdNotifications' . auth()->user()->id);
                canaisAguardandoRespostaNotification();

                return redirect()->route('visualizarMsg', ['id' => $id])->with(['success' => 'Chat encerrado!']);
            }
            return redirect()->route('visualizarMsg', ['id' => $id])->with(['warning' => 'Não foi possivel encerrar o chat!']);
        }
    }
}
