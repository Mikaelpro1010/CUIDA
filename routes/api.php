<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ManifestApiController;



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
        Route::get('manifest/tipos', 'Api\ManifestApiController@getTiposManifestacao');
        //get manifestacoes
        Route::get('manifest/all', 'Api\ManifestApiController@getManifestacoes');
        //criar manifestacao
        Route::post('manifest/create', 'Api\ManifestApiController@create');
        //Criar recurso
        Route::post('manifest/create-appel', 'Api\ManifestApiController@criarRecurso');

        //Inicia as mensagens do Chat
        Route::post('manifest/mensagem/new', 'Api\ManifestApiController@newMessage');

        //APP exclusive routes
        //Usuario anonimo
        Route::get('user-anonimo', 'UserController@getUserAnonimo');
        //marcações no mapa 
        Route::get('mapa', 'Api\ManifestApiController@getMapaPins');
    }
);
