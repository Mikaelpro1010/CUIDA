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

Route::get('/img', function () {
    return "<div><img width='300px' src=" . route('teste') . " alt=''></div>";
})->middleware('auth');

Route::get('/teste', function () {


    // // dd('web', auth('web')->user(), 'api', auth('api')->user());
    // $file = AnexoMensagem::first();
    // // $path = storage_path('app/msgs_anexos/') . $file->caminho . $file->nome;
    // $path = $file->caminho . $file->nome;
    // if (Storage::disk('msgs_anexos')->exists($path)) {
    //     // return Storage::disk('msgs_anexos')->download($path, $file->nome);
    //     return Storage::disk('msgs_anexos')->response($path);
    // } else {
    //     abort(404, 'File not found!');
    // }
    // return;
})->name('teste')->middleware('auth');

Route::get('/anexos', function () {
    $arquivos = AnexoMensagem::all();
    $result = "";
    foreach ($arquivos as $key => $arquivo) {
        $result .= $key + 1 . " - <a href=" . env('APP_URL') . Storage::url($arquivo->caminho . $arquivo->nome) . " target='_blank'>$arquivo->nome_original</a> <br>";
    }
    return $result;
})->name('storageUrl');


//Rotas Não Logadas
Route::view('/pagina-inicial', 'public.pagina-inicial')-> name("pagina-inicial");
Route::view('/', 'public.home')->name('home');
Route::view('/politicas-de-privacidade', 'public.politicas')->name('politicas');
Route::view('/termos-de-uso', 'public.termos')->name('termos');
Route::get('pagina-manifestacao/visualizar', 'Publico\PaginaManifestController@visualizarPagina')->name("vis-pagina_manifestacao");
Route::post('manifestacao/cadastrar/novo', 'Publico\PaginaManifestController@cadastrar')->name("cadastrar-manifestacao");
Route::get('/pagina-inicial', 'Publico\FaqController@paginaInicial')-> name("pagina-inicial");

Route::middleware(['auth:web'])->group(
    function () {
        Route::get('/inicio', 'HomeController@index')->name('inicio');

        //Gerenciamento
        Route::namespace('Gerenciar')->prefix('admin')->group(
            function () {
                // Usuarios
                Route::get('/users', 'UsersController@listUsers')->name('get-users-list');
                Route::get('/users/create', 'UsersController@createUser')->name('get-create-user');
                Route::post('/users', 'UsersController@storeUser')->name('post-store-user');
                Route::get('/users/{user_id}', 'UsersController@viewUser')->name('get-user-view');
                Route::get('/users/{user}/edit', 'UsersController@editUser')->name('get-edit-user-view');
                Route::patch('/users/{user}', 'UsersController@updateUser')->name('patch-update-user');
                Route::get('/users/{user}/password', 'UsersController@editUserPassword')->name('get-edit-user-password-view');
                Route::patch('/users/{user}/password', 'UsersController@updateUserPassword')->name('patch-update-user-password');
                Route::delete('/users/{user}', 'UsersController@deleteUser')->name('delete-delete-user');

                // Secretarias
                Route::get('secretarias', 'SecretariasController@listSecretarias')->name("get-secretarias-list");
                Route::get('secretarias/create', 'SecretariasController@createSecretaria')->name("get-create-secretaria");
                Route::post('secretarias', 'SecretariasController@storeSecretaria')->name("post-store-secretaria");
                Route::get('secretarias/{secretaria}', 'SecretariasController@viewSecretaria')->name("get-secretaria-view");
                Route::get('secretarias/{secretaria}/edit', 'SecretariasController@editSecretaria')->name("get-edit-secretaria");
                Route::patch('secretarias/{secretaria}', 'SecretariasController@updateSecretaria')->name("patch-update-secretaria");
                Route::get('secretarias/{secretaria}/toggle', 'SecretariasController@toggleSecretariaStatus')->name("get-toggle-secretaria-status");
            }
        );
        
        //Configuraçoes
        Route::namespace('Configs')->prefix('admin/config')->group(
            function () {
                Route::get('/users-type', 'RolesController@listRoles')->name('get-roles-list');
                Route::get('/users-type/create', 'RolesController@createRole')->name('get-create-role');
                Route::post('/users-type', 'RolesController@storeRole')->name('post-store-role');
                Route::get('/users-type/{role}', 'RolesController@viewRole')->name('get-role-view');
                Route::get('/users-type/{role}/edit', 'RolesController@editRole')->name('get-edit-role-view');
                Route::patch('/users-type/{role}', 'RolesController@updateRole')->name('patch-update-role');
                Route::delete('/users-type/{role}', 'RolesController@deleteRole')->name('delete-delete-role');
                
                Route::get('tipo-manifestacao', 'TipoManifestacaoController@listTipoManifestacao')->name('get-tipo-manifestacao-list');
                Route::get('tipo-manifestacao/create', 'TipoManifestacaoController@createTipoManifestacao')->name('get-create-tipo-manifestacao');
                Route::post('tipo-manifestacao', 'TipoManifestacaoController@storeTipoManifestacao')->name('post-store-tipo-manifestacao');
                Route::get('tipo-manifestacao/{tipoManifestacao}', 'TipoManifestacaoController@viewTipoManifestacao')->name('get-tipo-manifestacao-view');
                Route::get('tipo-manifestacao/{tipoManifestacao}/edit', 'TipoManifestacaoController@editTipoManifestacao')->name('get-edit-tipo-manifestacao-view');
                Route::patch('tipo-manifestacao/{tipoManifestacao}', 'TipoManifestacaoController@updateTipoManifestacao')->name('patch-update-tipo-manifestacao');
                Route::delete('tipo-manifestacao/{tipoManifestacao}', 'TipoManifestacaoController@deleteTipoManifestacao')->name('delete-delete-tipo-manifestacao');
                Route::get('tipo-mnifestacao/{tipoManifestacao}/toggle', 'TipoManifestacaoController@toggleTipoManifestacaoStatus')->name("get-toggle-tipo-manifestacao-status");
                
                Route::get('estado-processo', 'EstadoProcessoController@listEstadoProcesso')->name('get-estado-processo-list');
                Route::get('estado-processo/create', 'EstadoProcessoController@createEstadoProcesso')->name('get-create-estado-processo');
                Route::post('estado-processo', 'EstadoProcessoController@storeEstadoProcesso')->name('post-store-estado-processo');
                Route::get('estado-processo/{estadoProcesso}', 'EstadoProcessoController@viewEstadoProcesso')->name('get-estado-processo-view');
                Route::get('estado-processo/{estadoProcesso}/edit', 'EstadoProcessoController@editEstadoProcesso')->name('get-edit-estado-processo-view');
                Route::patch('estado-processo/{estadoProcesso}', 'EstadoProcessoController@updateEstadoProcesso')->name('patch-update-estado-processo');
                Route::delete('estado-processo/{estadoProcesso}', 'EstadoProcessoController@deleteEstadoProcesso')->name('delete-delete-estado-processo');
                Route::get('estado-processo/{estadoProcesso}/toggle', 'EstadoProcessoController@toggleEstadoProcessoStatus')->name("get-toggle-estado-processo-status");
                
                Route::get('motivacao', 'MotivacaoController@listMotivacao')->name('get-motivacao-list');
                Route::get('motivacao/create', 'MotivacaoController@createMotivacao')->name('get-create-motivacao');
                Route::post('motivacao', 'MotivacaoController@storeMotivacao')->name('post-store-motivacao');
                Route::get('motivacao/{Motivacao}', 'MotivacaoController@viewMotivacao')->name('get-motivacao-view');
                Route::get('motivacao/{Motivacao}/edit', 'MotivacaoController@editMotivacao')->name('get-edit-motivacao-view');
                Route::patch('motivacao/{Motivacao}', 'MotivacaoController@updateMotivacao')->name('patch-update-motivacao');
                Route::delete('motivacao/{Motivacao}', 'MotivacaoController@deleteMotivacao')->name('delete-delete-motivacao');
                Route::get('motivacao/{Motivacao}/toggle', 'MotivacaoController@toggleMotivacaoStatus')->name("get-toggle-motivacao-status");
                
                Route::get('situacao', 'SituacaoController@listSituacao')->name('get-situacao-list');
                Route::get('situacao/create', 'SituacaoController@createSituacao')->name('get-create-situacao');
                Route::post('situacao', 'SituacaoController@storeSituacao')->name('post-store-situacao');
                Route::get('situacao/{Situacao}', 'SituacaoController@viewSituacao')->name('get-situacao-view');
                Route::get('situacao/{Situacao}/edit', 'SituacaoController@editSituacao')->name('get-edit-situacao-view');
                Route::patch('situacao/{Situacao}', 'SituacaoController@updateSituacao')->name('patch-update-situacao');
                Route::delete('situacao/{Situacao}', 'SituacaoController@deleteSituacao')->name('delete-delete-situacao');
                Route::get('situacao/{Situacao}/toggle', 'SituacaoController@toggleSituacaoStatus')->name("get-toggle-situacao-status");
                
                Route::get('faq', 'FaqController@listFAQ')->name('get-faq-list');
                Route::get('faq/create', 'FaqController@createFAQ')->name('get-create-faq');
                Route::post('faq', 'FaqController@storeFAQ')->name('post-store-faq');
                Route::get('faq/{FAQ}', 'FaqController@viewFAQ')->name('get-faq-view');
                Route::get('faq/{FAQ}/edit', 'FaqController@editFAQ')->name('get-edit-faq-view');
                Route::patch('faq/{FAQ}', 'FaqController@updateFAQ')->name('patch-update-faq');
                Route::delete('faq/{FAQ}', 'FaqController@deleteFAQ')->name('delete-delete-faq');
                Route::get('faq/{FAQ}/toggle', 'FaqController@toggleFAQStatus')->name("get-toggle-faq-status");
                Route::post('faq/order', 'FaqController@orderFAQ')->name("order-faq");
            }
        );

        

        Route::get('/manifestacoes2', "Manifests2Controller@list")->name('manifestacoes2');
        Route::post('manifestacao2', 'Manifests2Controller@storeManifest')->name("post-store-manifest2");
        Route::get('manifestacao2/view', 'Manifests2Controller@create')->name("get-create-manifest2");
        //manifestacoes
        Route::get('/manifestacoes', "ManifestsController@list")->name('manifestacoes');
        Route::get('/manifestacoes/{id}', "ManifestsController@viewManifest")->name('visualizarManifests');

        //mensagens
        Route::get('/messages', "MessagesController@index")->name('mensagens');
        Route::get('/messages/{id}', "MessagesController@visualizarMsg")->name('visualizarMsg');
        Route::post('/messages/{id}/new-msg', "MessagesController@enviarMsg")->name('enviarMsg');
        Route::post('/messages/encerrar/{id}', "MessagesController@encerrarCanal")->name('encerrarCanal');

        Route::get('/messages/download/{anexo}', 'AnexoMensagemController@downloadAnexo')->name('download-anexo');
        Route::get('/messages/view/{anexo}', 'AnexoMensagemController@viewAnexo')->name('view-anexo');


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
Route::prefix('avaliacoes')->namespace('Avaliacoes')->group(function () {
    Route::get('/{token}/avaliar', 'UnidadeSecrController@paginaAvaliar')->name('get-avaliar-unidade');
    Route::post('/{token}/avaliar', 'UnidadeSecrController@avaliar')->name('post-avaliar-unidade');
    // ->middleware('throttle:1,1440');
    Route::view('/agradecer', 'public.unidade_secr.agradecimento')->name('agradecimento-avaliacao');
});
