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

Route::middleware('api')->namespace('Api')->group(
    function () {
        //Api Ouvidoria
        //registrar usuário//
        Route::post('register', 'UserController@cadastrar');
        //login//
        Route::post('login', 'UserController@login');
        //get user by email//
        Route::get('user', 'UserController@userByEmail');
        //alterarSenha
        Route::post('update-password', 'UserController@alterarSenha');

        //recuperarSenha
        Route::post('password/password-recover', 'UserController@verificarDados');
        //novaSenha
        Route::post('password/new-password', 'UserController@novaSenha');

        //get tipo manifestação
        Route::get('manifest/tipos', 'ManifestApiController@getTiposManifestacao');
        //get manifestacoes
        Route::get('manifest/all', 'ManifestApiController@getManifestacoes');
        //criar manifestacao
        Route::post('manifest/create', 'ManifestApiController@create');
        //Criar recurso
        Route::post('manifest/create-appel', 'ManifestApiController@criarRecurso');

        //Chat
        //Inicia as mensagens do Chat
        Route::post('manifest/mensagem/new', 'ManifestApiController@newMessage');
        //Mensagens do Chat
        Route::get('manifest/{protocolo}/chat/mensagem/', 'ManifestApiController@getChatPorProtocolo');

        //APP exclusive routes
        //Usuario anonimo
        Route::get('user-anonimo', 'UserController@getUserAnonimo');
        //marcações no mapa 
        Route::get('mapa', 'ManifestApiController@getMapaPins');
    }
);
