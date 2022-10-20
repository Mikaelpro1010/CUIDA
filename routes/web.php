<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Constants\Permission as ConstantsPermission;
use App\Models\Avaliacao\Unidade;
use App\Models\Chat\AnexoMensagem;
use App\Models\Manifest\AnexoManifest;
use App\Models\Manifest\Manifest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Auth::routes();

Route::get('/register', function () {
    return redirect()->route('login');
})->name('register');

Route::get('/teste', function () {
    dd(Secretaria::getResumoSecretariaAll());
    return;
})->name('teste');

Route::get('/anexos', function () {
    $arquivos = AnexoMensagem::all();
    $result = "";
    foreach ($arquivos as $key => $arquivo) {
        $result .= $key + 1 . " - <a href=" . env('APP_URL') . Storage::url($arquivo->caminho . $arquivo->nome) . " target='_blank'>$arquivo->nome_original</a> <br>";
    }
    return $result;
})->name('storageUrl');


//Rotas Não Logadas
Route::view('/', 'public.home')->name('home');
Route::view('/politicas-de-privacidade', 'public.politicas')->name('politicas');
Route::view('/termos-de-uso', 'public.termos')->name('termos');

Route::middleware(['auth'])->group(
    function () {
        Route::get('/inicio', 'HomeController@index')->name('inicio');

        Route::namespace('Gerenciar')->prefix('admin')->group(
            function () {
                Route::get('/users', 'UsersController@listUsers')->name('get-users-list');
                Route::get('/users/create', 'UsersController@createUser')->name('get-create-user');
                Route::post('/users', 'UsersController@storeUser')->name('post-store-user');
                Route::get('/users/{user}', 'UsersController@viewUser')->name('get-user-view');
                Route::get('/users/{user}/edit', 'UsersController@editUser')->name('get-edit-user-view');
                Route::patch('/users/{user}', 'UsersController@updateUser')->name('patch-update-user');
                Route::delete('/users/{user}', 'UsersController@deleteUser')->name('delete-delete-user');
            }
        );


        //manifestacoes
        Route::get('/manifestacoes', "ManifestsController@listagem")->name('manifestacoes');
        Route::get('/manifestacoes/{id}', "ManifestsController@visualizarManifest")->name('visualizarManifests');

        //mensagens
        Route::get('/messages', "MessagesController@index")->name('mensagens');
        Route::get('/messages/{id}', "MessagesController@visualizarMsg")->name('visualizarMsg');
        Route::post('/messages/{id}/new-msg', "MessagesController@enviarMsg")->name('enviarMsg');
        Route::post('/messages/encerrar/{id}', "MessagesController@encerrarCanal")->name('encerrarCanal');
        Route::get('/download/{id}', 'AnexoMensagemController@downloadAnexo')->name('download-anexos');

        //modulo Avaliacoes
        Route::prefix('avaliacoes')->namespace('Avaliacoes')->group(function () {
            Route::get('/', 'AvaliacoesController@resumo')->name('resumo-avaliacoes');

            Route::get('/secretaria', 'AvaliacoesController@resumoSecretariasList')->name('resumo-avaliacoes-secretaria-list');
            Route::get('/secretaria/{secretaria}', 'AvaliacoesController@resumoSecretaria')->name('resumo-avaliacoes-secretaria');
            Route::get('/secretaria/{secretaria}/avaliacoes/mes', 'AvaliacoesController@avaliacoesPorMesSecretaria')
                ->middleware('throttle:60,60')
                ->name('resumo-avaliacoes-secretaria-avaliacoes-mes');

            Route::get('/unidade', 'AvaliacoesController@resumoUnidadeSecrList')->name('resumo-avaliacoes-unidade-list');
            Route::get('/secretaria/{secretaria}/unidade/{unidade}', 'AvaliacoesController@resumoUnidadeSecr')->name('resumo-avaliacoes-unidade');
            Route::get('/unidade/{unidade}/notas-mes', 'AvaliacoesController@notasPorMesUnidade')
                ->middleware('throttle:60,60')
                ->name('resumo-avaliacoes-unidade-notas-mes');
            Route::get('/unidade/{unidade}/avaliacoes-mes', 'AvaliacoesController@avaliacoesPorMesUnidade')
                ->middleware('throttle:60,60')
                ->name('resumo-avaliacoes-unidade-avaliacoes-mes');


            Route::get('/unidade/lista', 'UnidadeSecrController@listagem')->name('unidades-secr-list');
            Route::post('/unidade/criar', 'UnidadeSecrController@novaUnidade')->name('nova-unidade');
            Route::get('/unidade/{unidade}', 'UnidadeSecrController@visualizar')->name('visualizar-unidade');
            Route::put('/unidade/{unidade}/atualizar', 'UnidadeSecrController@atualizarUnidade')->name('atualizar-unidade');
            Route::get('/unidade/{unidade}/ativar', 'UnidadeSecrController@ativarDesativar')->name('ativar-unidade');
            Route::get('/pdf/{unidade}', 'UnidadeSecrController@gerarQrcode')->name('gerar-qrcode-unidade');
        });
    }
);

//não logado
Route::namespace('Avaliacoes')->group(function () {
    Route::get('/avaliacoes/{token}/avaliar', 'UnidadeSecrController@paginaAvaliar')->name('get-avaliar-unidade');
    Route::post('/avaliacoes/{token}/avaliar', 'UnidadeSecrController@avaliar')->name('post-avaliar-unidade');
    // ->middleware('throttle:1,1440');
    Route::view('/avaliacoes/agradecer', 'public.unidade_secr.agradecimento')->name('agradecimento-avaliacao');
});
