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

use App\Models\Chat\AnexoMensagem;
use App\Models\Manifest\AnexoManifest;
use App\Models\Manifest\Manifest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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

Auth::routes();
Route::get('/inicio', 'HomeController@index')->name('inicio');


Route::get('/manifestacoes', "ManifestsController@listagem")->name('manifestacoes');
Route::get('/manifestacoes/{id}', "ManifestsController@visualizarManifest")->name('visualizarManifests');

Route::get('/messages', "MessagesController@index")->name('mensagens');
Route::get('/messages/{id}', "MessagesController@visualizarMsg")->name('visualizarMsg');
Route::post('/messages/{id}/new-msg', "MessagesController@enviarMsg")->name('enviarMsg');
Route::post('/messages/encerrar/{id}', "MessagesController@encerrarCanal")->name('encerrarCanal');
Route::get('/download/{id}', 'AnexoMensagemController@downloadAnexo')->name('download-anexos');


// Rotas para a funcionalidade do QRcode
Route::get('/qr', function () {
    // QrCode::format('svg');  //Will return a svg image
    // return QrCode::size(500)->generate('http://localhost:9000', '../public/qrcodes/qrcode.svg');
    return QrCode::size(300)->generate('http://10.0.49.0:9000');
})->name('qr');

Route::prefix('avaliacoes')->namespace('Avaliacoes')->group(function () {
    Route::get('/', 'AvaliacoesController@resumo')->name('resumo-avaliacoes');
    Route::get('/secretaria', 'AvaliacoesController@resumoSecretaria')->name('resumo-avaliacoes-secretaria');
    Route::get('/unidade', 'AvaliacoesController@resumoUnidadeSecr')->name('resumo-avaliacoes-unidade');

    Route::get('/unidade/lista', 'UnidadeSecrController@listagem')->name('unidades-secr-list');
    Route::post('/unidade/criar', 'UnidadeSecrController@novaUnidade')->name('nova-unidade');
    Route::get('/unidade/{unidade}', 'UnidadeSecrController@visualizar')->name('visualizar-unidade');
    Route::put('/unidade/{unidade}/atualizar', 'UnidadeSecrController@atualizarUnidade')->name('atualizar-unidade');
    Route::get('/unidade/{unidade}/ativar', 'UnidadeSecrController@ativarDesativar')->name('ativar-unidade');
    Route::get('/pdf/{unidade}', 'UnidadeSecrController@gerarQrcode')->name('gerar-qrcode-unidade');
});

//não logado
Route::namespace('Avaliacoes')->group(function () {
    Route::get('/avaliacoes/{token}/avaliar', 'UnidadeSecrController@paginaAvaliar')->name('get-avaliar-unidade');
    Route::post('/avaliacoes/{token}/avaliar', 'UnidadeSecrController@avaliar')->name('post-avaliar-unidade');
    Route::view('/avaliacoes/agradecer', 'public.unidade_secr.agradecimento')->name('agradecimento-avaliacao');
});
