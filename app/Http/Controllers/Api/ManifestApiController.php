<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Integrations\Ouvidoria;
use App\Models\Chat\AnexoMensagem;
use App\Models\Chat\CanalMensagem;
use App\Models\Chat\Mensagem;
use App\Models\Manifest\Manifest;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManifestApiController extends Controller
{
    use ApiResponser;

    /**
     *  busca os tipos de manifestação disponiveis 
     */
    public function getTiposManifestacao(Request $request)
    {
        $token = $request->bearerToken();
        $requestResult = Ouvidoria::get('/tipoManifestacao', null, $token);

        if ($requestResult['statusCode'] != 200) {
            return $this->error('error', 404);
        }

        $response = [];
        foreach ($requestResult['body'] as $tipo) {
            $item['nome'] = $tipo->nome;
            $item['id_tipo'] = $tipo->id_tipo;

            if ($tipo->nome == 'DENÚNCIA FAKE NEWS') {
                $item['cor'] = Manifest::COR_MANIFESTACAO[Manifest::TIPO_DENUNCIA_FAKE_NEWS];
            } elseif ($tipo->nome == 'DENÚNCIA') {
                $item['cor'] = Manifest::COR_MANIFESTACAO[Manifest::TIPO_DENÚNCIA];
            } elseif ($tipo->nome == 'SOLICITAÇÃO DE INFORMAÇÃO') {
                $item['cor'] = Manifest::COR_MANIFESTACAO[Manifest::TIPO_SOLICITACAO_DE_INFORMACAO];
            } elseif ($tipo->nome == 'SOLICITAÇÃO') {
                $item['cor'] = Manifest::COR_MANIFESTACAO[Manifest::TIPO_SOLICITACAO];
            } elseif ($tipo->nome == 'RECLAMAÇÃO') {
                $item['cor'] = Manifest::COR_MANIFESTACAO[Manifest::TIPO_RECLAMACAO];
            } elseif ($tipo->nome == 'ELOGIO') {
                $item['cor'] = Manifest::COR_MANIFESTACAO[Manifest::TIPO_ELOGIO];
            } elseif ($tipo->nome == 'SUGESTÃO') {
                $item['cor'] = Manifest::COR_MANIFESTACAO[Manifest::TIPO_SUGESTAO];
            } else {
                $item['cor'] = '#c3c3c3'; //cinza
            }
            array_push($response, $item);
        }
        return $response;
    }

    /**
     *  busca todas as manifestações do usuário logado
     */
    public function getManifestacoes(Request $request)
    {
        $token = $request->bearerToken();

        $requestResult = Ouvidoria::get('/manifestacao/all', null, $token);
        if ($requestResult['statusCode'] == 200) {
            return json_encode($requestResult['body']);
        } else {
            return $this->error("Nenhuma manifestação cadastrada", 404);
        }
    }

    /**
     *  cria uma nova manifestação
     */
    public function create(Request $request)
    {
        $token = $request->bearerToken();

        $json = [
            "manifestacao" => $request["manifestacao"],
            "anonimo" => $request["anonimo"] == 'true' ? 1 : 0,
            "id_tipo_manifestacao" => $request["id_tipo_manifestacao"],
            "latitude" => $request['latitude'],
            "longitude" => $request['longitude'],
            "bairro" => $request['bairro'],
            "logradouro" => $request['logradouro'],
            "numero" => $request['numero'],
            "complemento" => $request['complemento'],
        ];

        $createManifest = Ouvidoria::post("/manifestacao/create", $json, $token);

        if (!$createManifest['statusCode'] == 201) {
            return $this->error($createManifest['body']->message, 422);
        }

        $response = [];
        $response[] = $createManifest['body'];

        $manifests = $this->getManifestacoes($request);

        if (count(json_decode($manifests)) == 0) {
            return $this->error('Algo deu errado, tente novamente!', 422);
        }

        if (count($request->file()) > 0) {
            $arquivos = [];
            foreach ($request->file() as $file) {
                $arquivos[] = [
                    [
                        'name'     => 'arquivo',
                        'contents' => fopen($file->path(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                    [
                        'name'     => 'id',
                        'contents' => json_decode($manifests)[0]->id_manifestacao,
                    ]
                ];
            }
            foreach ($arquivos as $arquivo) {
                $anexos = Ouvidoria::postMultiPart('/manifestacao/arquivo', $arquivo, $token);
                if ($anexos['statusCode'] == 201) {
                    $response[] = $anexos['body'];
                }
            }
        }
        return json_encode($response);
    }

    /**
     *  entrar com recurso em uma manifestação
     */
    public function criarRecurso(Request $request)
    {
        $token = $request->bearerToken();
        $json = [
            "id_manifestacao" => $request["id_manifestacao"],
            "recurso" => $request["recurso"],
        ];

        $requestResult = Ouvidoria::post('/recurso/create', $json, $token);
        if ($requestResult['statusCode'] == 201) {
            return json_encode($requestResult['body']);
        } else {
            return $this->error("Recurso não cadastrado, tente novamente!", 422);
        }
    }

    /**
     *  pega todas as manifestaçoes que tiverem localizaçao e retorna no array
     * organiza os dados para consumir no frontend
     */
    public function getMapaPins(Request $request)
    {
        $manifests = json_decode($this->getManifestacoes($request));
        $tiposManifests = $this->getTiposManifestacao($request);

        $tipos = [];
        foreach ($tiposManifests as $tipo) {
            $tipos[$tipo['id_tipo']] = $tipo;
        }

        $response = [];
        foreach ($manifests as $manifest) {
            if (count($manifest->endereço) > 0) {
                $item['id_manifestacao'] = $manifest->id_manifestacao;
                $item['manifestacao'] = $manifest->manifestacao;
                $item['cor'] = $tipos[$manifest->id_tipo_manifestacao]['cor'];
                $item['tipo'] = $tipos[$manifest->id_tipo_manifestacao]['nome'];
                $item['lat'] = $manifest->endereço[0]->cd_latitude;
                $item['long'] = $manifest->endereço[0]->cd_longitude;
                if ($item['lat'] != null && $item['long'] != null) {
                    array_push($response, $item);
                }
            }
        }
        return $response;
    }

    //Canal Chat
    public function newMessage(Request $request)
    {
        $manifestacao = Manifest::with('canalMensagem')->where('protocolo', $request->protocolo)->first();
        if (is_null($manifestacao)) {
            return $this->error("Manifestação não encontrada!", 404);
        }
        // dd(count($manifestacao->canalMensagem));
        if (count($manifestacao->canalMensagem)) {
            if ($manifestacao->canalMensagem->id_status == CanalMensagem::STATUS_AGUARDANDO_RESPOSTA) {
                return $this->error("Não é possivel enviar mensagens até que um administrador responda sua mensagem anterior!", 422);
            } elseif ($manifestacao->canalMensagem->id_status == CanalMensagem::STATUS_ENCERRADO) {
                return $this->error("Não é possivel enviar mensagens quando o Chat já está encerrado!", 422);
            } else {
                $canalManifestacao = $manifestacao->canalMensagem;
            }
        } else {
            $canalManifestacao = new CanalMensagem();
            $canalManifestacao->id_manifestacao = $manifestacao->id;
        }
        $canalManifestacao->id_status = CanalMensagem::STATUS_AGUARDANDO_RESPOSTA;
        $canalManifestacao->updated_at = Carbon::now();
        $canalManifestacao->save();

        $novaMensagem = new Mensagem();
        $novaMensagem->id_canal_mensagem = $canalManifestacao->id;
        $novaMensagem->msg_type = Mensagem::TIPO_APP_USER;
        $novaMensagem->id_app_user = $manifestacao->autor->id;

        $novaMensagem->mensagem = $request->mensagem;

        if ($request->mensagem == '') {
            if ($request->has('anexo')) {
                $novaMensagem->mensagem = 'Anexo(s)';
            } else {
                return $this->error('Mensagem vazia e nenhum anexo inserido!', 401);
            }
        } else {
            $novaMensagem->mensagem = $request->mensagem;
        }
        $novaMensagem->save();
        if ($request->has('anexo')) {
            foreach ($request->file('anexo') as $file) {
                $uploadedFile = $file;
                $nameArray = explode(".", $uploadedFile->getClientOriginalName());
                $extensão = array_pop($nameArray);
                $originName = implode('', $nameArray);
                $filename = time() . "-" . str_slug($originName) . "." . $extensão;
                // dd($uploadedFile->getClientOriginalName(), $nameArray, $originName, $extensão, $filename);
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

        return $this->success('Mensagem enviada com Sucesso!', 201);
    }

    public function getChatPorProtocolo($protocolo)
    {
        $manifestacao = Manifest::with('canalMensagem', 'canalMensagem.mensagens', 'canalMensagem.mensagens.anexos', 'autor')->where('protocolo', $protocolo)->first();
        if (is_null($manifestacao)) {
            return $this->error('Não foi possivel carregar as mensagens!', 404);
        }
        return $this->successWithData($manifestacao, 'Mensagens carregadas com sucesso!');
    }
}
