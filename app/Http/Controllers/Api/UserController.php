<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPassordRequest;
use App\Http\Requests\UserRequest;
use App\Integrations\Ouvidoria;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use Faker\Generator;
use Illuminate\Container\Container;

class UserController extends Controller
{
    use ApiResponser;

    /**
     *  Cadastrar um novo usuário
     */
    public function cadastrar(UserRequest $request)
    {
        $json = [
            'nome' => $request['nome'],
            'cpf' => $request['cpf'],
            'email' => $request['email'],
            'senha' => $request['senha'],
        ];
        $requestResult = Ouvidoria::post('/usuarioApi/create', $json);
        if ($requestResult['statusCode'] == 201) {
            return json_encode($requestResult['body']);
        } else {
            return $this->error($requestResult['body']->message, 422);
        }
    }

    /**
     *  Login no sistema
     */
    public function login(LoginRequest $request)
    {
        $json = [
            'email' => strtolower($request['email']),
            'senha' => $request['senha'],
        ];

        $requestResult = Ouvidoria::post('/loginApi', $json);
        if ($requestResult['statusCode'] == 200) {
            return json_encode($requestResult['body']);
        } else {
            return $this->error($requestResult['body']->message, 422);
        }
    }

    /**
     *  pegar informações do usuário atravez do email
     */
    public function userByEmail(Request $request)
    {
        $json = [
            'email' => $request['email']
        ];

        $requestResult = Ouvidoria::post('/usuarioApi/getByEmail', $json);
        if ($requestResult['statusCode'] == 200) {
            return $this->successWithData($requestResult['body'], 'success');
        } else {
            return $this->error("Não encontrado", 404);
        }
    }

    /**
     * Alterar senha do usuario usando o id
     */
    public function alterarSenha(UserRequest $request)
    {
        $response = json_decode($this->userByEmail($request)->getContent());
        $data = $response->data;
        $status = $response->status;

        if ($status) {
            $cpf = str_replace(".", "", $request['cpf']);
            $cpf = str_replace("-", '', $cpf);

            if ($request['nome'] != $data->nome || $request['email'] != $data->email || $cpf != $data->cpf) {
                return $this->error('Não foi possivel editar esse usuário', 422);
            }

            $json = [
                'id' => $data->id,
                'senha' => $request['senha'],
            ];

            $requestResult = Ouvidoria::post('/usuarioApi/alterarSenha', $json);
            if ($requestResult['statusCode'] == 201) {
                return json_encode($requestResult['body']);
            } else {
                return $this->error('Não foi possivel editar esse usuário', 422);
            }
        } else {
            return $this->error("Algo deu errado", 422);
        }
    }

    /**
     *  Gera um Usuário anonimo para cadastrar 
     */
    public function getUserAnonimo()
    {
        $faker = Container::getInstance()->make(Generator::class);

        try {
            do {
                $numero = substr(uniqid(rand()), 0, 6);
                $nomeAnonimo = "Anonimo-" . $numero;
                $email = $numero . '@anonimo.com';

                $json = [
                    'email' => $email
                ];
                $body = Ouvidoria::post('/usuarioApi/getByEmail', $json);
            } while ($body['statusCode'] != 404);

            $cpf = $faker->cpf();

            return $this->successWithData(['nome' => $nomeAnonimo, 'email' => $email, 'cpf' => $cpf]);
        } catch (Exception $e) {
            report($e);
            return $this->error($e->getMessage(), 106);
        }
    }

    /**
     *  Funçao para verificar os dados informados pelo usuario antes de recuperar a senha
     */
    public function verificarDados(Request $request)
    {
        $retorno = $this->userByEmail($request);
        $response = json_decode($retorno->getContent());

        $user = $response->data;

        $cpf = $request['cpf'];
        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);

        if (!$response->status || $user->cpf != $cpf || $user->email != $request['email']) {
            return $this->error('Os dados não Conferem, tente novamente ou peça ajuda da administração!', 422);
        } else {
            return $this->successWithData(['email' => $request['email'], 'cpf' => $request['cpf']], 'success');
        }
    }

    /**
     * Função para gerar uma nova senha para o usuário
     */
    public function novaSenha(ResetPassordRequest $request)
    {
        $verify = $this->verificarDados($request);
        $requisicao = json_decode($verify->getContent());

        if (!$requisicao->status) {
            return $verify;
        }

        $userByEmail = $this->userByEmail($request);
        $content = json_decode($userByEmail->getContent());

        $user = $content->data;

        if (!$content->status) {
            return $user;
        }

        $json = [
            'id' => $user->id,
            'senha' => $request['senha'],
        ];

        $requestResult = Ouvidoria::post('/usuarioApi/alterarSenha', $json);
        if ($requestResult['statusCode'] == 201) {
            return json_encode($requestResult['body']);
        } else {
            return $this->error("Não encontrado", 422);
        }
    }
}
