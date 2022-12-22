<?php

namespace App\Http\Controllers;

use App\Models\Manifest\Manifest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManifestsController extends Controller
{
    public function list(Request $request)
    {
        $manifestacoes = Manifest::with('autor')
            ->when(is_numeric(request()->protocolo), function ($query) {
                $query->where('protocolo', request()->protocolo);
            })
            ->when(request()->data_inicio, function ($query) {
                $query->where('created_at', '>=', request()->data_inicio);
            })
            ->when(request()->data_fim, function ($query) {
                $query->where('updated_at', '<=', Carbon::parse(request()->data_fim)->addDay());
            })
            ->when(request()->motivacao, function ($query) {
                $query->where('id_motivacao', request()->motivacao);
            })
            ->when(request()->tipo, function ($query) {
                $query->where('id_tipo_manifestacao', request()->tipo);
            })
            ->when(request()->situacao, function ($query) {
                $query->where('id_situacao', request()->situacao);
            })
            ->when(request()->estado_processo, function ($query) {
                $query->where('id_estado_processo', request()->estado_processo);
            });

        $manifestacoes =
            $manifestacoes
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends([
                "protocolo" => request()->protocolo,
                "data_inicio" => request()->data_inicio,
                "data_fim" => request()->data_fim,
                "motivacao" => request()->motivacao,
                "tipo" => request()->tipo,
                "situacao" => request()->situacao,
                "estado_processo" => request()->estado_processo,
            ]);

        $resposta = [
            "manifestacoes" => $manifestacoes,
            // "aguardandoResposta" => $aguardandoResposta,
            // "respondido" => $respondido,
            // "encerrado" => $encerrado,
            // "totalCanaisMsg" => $totalCanaisMsg,
        ];

        return view('admin.manifests.manifests-listar', $resposta);
    }

    public function viewManifest($id)
    {
        $manifestacao = Manifest::with('recursos', 'autor', 'location')->find($id);

        return view('admin.manifests.manifest-visualizar', ['manifestacao' => $manifestacao]);
    }
}
