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

Route::get('/qr', function () {
    QrCode::format('svg');  //Will return a svg image
    // return QrCode::size(500)->generate('http://localhost:9000', '../public/qrcodes/qrcode.svg');
    return QrCode::size(300)->generate('http://10.0.49.0:9000');
})->name('qr');


//Rotas Não Logadas
Route::view('/', 'public.home')->name('home');
Route::view('/politicas-de-privacidade', 'public.politicas')->name('politicas');
Route::view('/termos-de-uso', 'public.termos')->name('termos');


Route::get('/inicio', 'HomeController@index')->name('inicio');

Route::get('/manifestacoes', "ManifestsController@listagem")->name('manifestacoes');
Route::get('/manifestacoes/{id}', "ManifestsController@visualizarManifest")->name('visualizarManifests');

Route::get('/messages', "MessagesController@index")->name('mensagens');
Route::get('/messages/{id}', "MessagesController@visualizarMsg")->name('visualizarMsg');
Route::post('/messages/{id}/new-msg', "MessagesController@enviarMsg")->name('enviarMsg');
Route::post('/messages/encerrar/{id}', "MessagesController@encerrarCanal")->name('encerrarCanal');
Route::get('/download/{id}', 'AnexoMensagemController@downloadAnexo')->name('download-anexos');

Auth::routes();
