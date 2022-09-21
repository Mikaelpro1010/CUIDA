<?php

use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->group(
    function () {
        //Api Ouvidoria
        //registrar usuário//
        Route::post('register', 'Api\UserController@cadastrar');
        //login//
        Route::post('login', 'Api\UserController@login');
        //get user by email//
        Route::get('user', 'Api\UserController@userByEmail');
        //alterarSenha
        Route::post('update-password', 'Api\UserController@alterarSenha');

        //recuperarSenha
        Route::post('password/password-recover', 'Api\UserController@verificarDados');
        //novaSenha
        Route::post('password/new-password', 'Api\UserController@novaSenha');

        //get tipo manifestação
        Route::get('manifest/tipos', 'Api\ManifestApiController@getTiposManifestacao');
        //get manifestacoes
        Route::get('manifest/all', 'Api\ManifestApiController@getManifestacoes');
        //criar manifestacao
        Route::post('manifest/create', 'Api\ManifestApiController@create');
        //Criar recurso
        Route::post('manifest/create-appel', 'Api\ManifestApiController@criarRecurso');

        //Chat
        //Inicia as mensagens do Chat
        Route::post('manifest/mensagem/new', 'Api\ManifestApiController@newMessage');
        //Mensagens do Chat
        Route::get('manifest/{protocolo}/chat/mensagem/', 'Api\ManifestApiController@getChatPorProtocolo');

        //APP exclusive routes
        //Usuario anonimo
        Route::get('user-anonimo', 'Api\UserController@getUserAnonimo');
        //marcações no mapa 
        Route::get('mapa', 'Api\ManifestApiController@getMapaPins');
    }
);
