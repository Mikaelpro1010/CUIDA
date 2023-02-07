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

// use App\Constants\Permission as ConstantsPermission;
// use App\Models\Permission;
// use Illuminate\Http\Response;
// use App\Models\Avaliacao\Unidade;
// use App\Models\Chat\AnexoMensagem;
// use App\Models\Manifest\AnexoManifest;
// use App\Models\Manifest\Manifest;
// use App\Models\Permission;
// use App\Models\Role;
// use App\Models\Secretaria;
// use App\Models\User;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\DB;

use App\Models\Avaliacao\TipoAvaliacao;
use App\Models\Avaliacao\Unidade;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Storage;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// $this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
// $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// $this->post('password/reset', 'Auth\ResetPasswordController@reset');


// Route::get('/img', function () {
//     return "<div><img width='300px' src=" . route('teste') . " alt=''></div>";
// })->middleware('auth');

Route::get('/teste', function () {
    $tiposAvaliacao = TipoAvaliacao::where('secretaria_id', 7)
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->id => ['nota' => 0]];
        })
        ->toArray();

    dd($tiposAvaliacao);
    // $nome = ['name' => 'name'];
    // dd(auth()->user()->$nome['name']);
    //     $file = AnexoMensagem::first();
    //     // $path = storage_path('app/msgs_anexos/') . $file->caminho . $file->nome;
    //     $path = $file->caminho . $file->nome;
    //     if (Storage::disk('msgs_anexos')->exists($path)) {
    //         // return Storage::disk('msgs_anexos')->download($path, $file->nome);
    //         return Storage::disk('msgs_anexos')->response($path);
    //     } else {
    //         abort(404, 'File not found!');
    //     }
    //     return;
})->name('teste');

// Route::get('/anexos', function () {
//     $arquivos = AnexoMensagem::all();
//     $result = "";
//     foreach ($arquivos as $key => $arquivo) {
//         $result .= $key + 1 . " - <a href=" . env('APP_URL') . Storage::url($arquivo->caminho . $arquivo->nome) . " target='_blank'>$arquivo->nome_original</a> <br>";
//     }
//     return $result;
// })->name('storageUrl');


//Rotas Não Logadas
Route::view('/', 'public.home')->name('home');
Route::view('/politicas-de-privacidade', 'public.politicas')->name('politicas');
Route::view('/termos-de-uso', 'public.termos')->name('termos');

Route::get('pagina-manifestacao/visualizar', 'Publico\PaginaManifestController@visualizarPagina')->name("vis-pagina_manifestacao");
Route::post('manifestacao/cadastrar/novo', 'Publico\PaginaManifestController@cadastrar')->name("cadastrar-manifestacao");
Route::post('pagina-manifestacao/visualizar-manifestacao', 'Publico\PaginaManifestController@visualizarManifestacao')->name("vis-manifestacao");
Route::post('pagina-manifestacao/visualizar-manifestacao/recurso/criar', 'Publico\PaginaManifestController@criarRecurso')->name("criar-recurso");

// Route::get('/pagina-inicial', 'Publico\FaqController@paginaInicial')->name("pagina-inicial");
Route::get('/pagina-inicial', function () {
    return redirect()->route('home');
})->name("pagina-inicial");

Route::middleware(['auth:web'])->group(
    function () {
        //superAdm
        Route::prefix('super-adm')->group(
            function () {
                // Route::get('/migrar', 'SuperAdmController@migrarDados');
                Route::get('/down', function () {
                    if (auth()->user()->email == 'asd@mail.com') {
                        Artisan::call('down');
                    }
                    return redirect()->route('home');
                });
            }
        );

        //inicio
        Route::get('/inicio', 'HomeController@index')->name('inicio');

        //Perfil do Usuário
        Route::get('/perfil-usuario', 'PerfilUsuarioController@viewPerfil')->name('get-user-perfil');
        Route::get('/perfil-usuario/edit', 'PerfilUsuarioController@editPerfil')->name('get-edit-user-perfil-view');
        Route::patch('/perfil-usuario/update-user', 'PerfilUsuarioController@updatePerfil')->name('patch-update-user-perfil');
        Route::get('/perfil-usuario/edit-password', 'PerfilUsuarioController@viewEditPassword')->name('get-user-perfil-password');
        Route::patch('/perfil-usuario/update-password', 'PerfilUsuarioController@updatePassword')->name('patch-update-user-perfil-password');

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
                Route::get('tipo-manifestacao/{tipoManifestacao}/toggle', 'TipoManifestacaoController@toggleTipoManifestacaoStatus')->name("get-toggle-tipo-manifestacao-status");

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

                Route::get('tipo-avaliacao', 'TipoAvaliacaoController@listTipoAvaliacao')->name('get-tipo-avaliacao-list');
                Route::get('tipo-avaliacao/create', 'TipoAvaliacaoController@createTipoAvaliacao')->name('get-create-tipo-avaliacao');
                Route::post('tipo-avaliacao', 'TipoAvaliacaoController@storeTipoAvaliacao')->name('post-store-tipo-avaliacao');
                Route::get('tipo-avaliacao/{tipoAvaliacao}', 'TipoAvaliacaoController@viewTipoavaliacao')->name('get-tipo-avaliacao-view');
                Route::get('tipo-avaliacao/{tipoAvaliacao}/edit', 'TipoAvaliacaoController@editTipoavaliacao')->name('get-edit-tipo-avaliacao-view');
                Route::patch('tipo-avaliacao/{tipoAvaliacao}', 'TipoAvaliacaoController@updateTipoavaliacao')->name('patch-update-tipo-avaliacao');
                Route::delete('tipo-avaliacao/{tipoAvaliacao}', 'TipoAvaliacaoController@deleteTipoavaliacao')->name('delete-delete-tipo-avaliacao');
                Route::get('tipo-avaliacao/{tipoAvaliacao}/toggle', 'TipoAvaliacaoController@toggleTipoavaliacaoStatus')->name("get-toggle-tipo-avaliacao-status");
                Route::get('tipo-avaliacao/{secretariaId}/secretaria', 'TipoAvaliacaoController@getTiposAvaliacaoSecretaria')->name("get-tipo-avaliacao-secretaria");
            }
        );

        // manifestacoes
        Route::get('/manifestacoes', "ManifestsController@list")->name('get-list-manifestacoes');
        Route::get('/manifestacao/{id}', "ManifestsController@viewManifest")->name('get-view-manifestacao');

        //manifestacoes 2

        Route::get('/manifestacoes2', "Manifests2Controller@list")->name('get-list-manifestacoes2');
        Route::get('/manifestacao2/create', 'Manifests2Controller@create')->name("get-create-manifest2");
        Route::post('/manifestacao2', 'Manifests2Controller@storeManifest')->name("post-store-manifest2");
        Route::get('/manifestacoes2/{id}', "Manifests2Controller@viewManifest")->name('get-view-manifestacao2');
        Route::post('/manifestacao2/{manifestacao}/prorrogacao/create', 'ProrrogacaoController@create')->name("criar-prorrogacao");
        Route::post('/manifestacao2/{manifestacao}/prorrogacao/{prorrogacao}/response', 'ProrrogacaoController@responseProrrogacao')->name("criar-resposta");
        Route::post('/manifestacao2/compartilhamento/{manifestacao_id}', 'CompartilhamentoController@compartilharManifestacao')->name("compartilhar-manifestacao");
        Route::post('/manifestacao2/compartilhamento/{compartilhamento}/resposta', 'CompartilhamentoController@responderCompartilhamento')->name("responder-compartilhamento");
        Route::post('/manifestacao2/compartilhamento', 'Manifests2Controller@viewCompartilhamento')->name("view-compartilhamento");
        Route::post('/manifestacao2/{manifestacao}/{recurso}/responder', "Manifests2Controller@responderRecurso")->name('responder-recurso');


        //mensagens
        Route::get('/messages', "MessagesController@index")->name('mensagens');
        Route::get('/messages/{id}', "MessagesController@visualizarMsg")->name('visualizarMsg');
        Route::post('/messages/{id}/new-msg', "MessagesController@enviarMsg")->name('enviarMsg');
        Route::post('/messages/encerrar/{id}', "MessagesController@encerrarCanal")->name('encerrarCanal');
        // AnexosMensagem
        Route::get('/messages/download/{anexo}', 'AnexoMensagemController@downloadAnexo')->name('download-anexo');
        Route::get('/messages/view/{anexo}', 'AnexoMensagemController@viewAnexo')->name('view-anexo');


        //modulo Avaliacoes
        Route::prefix('avaliacoes')->namespace('Avaliacoes')->group(function () {
            //Relatorios
            Route::get('/relatorios', 'RelatoriosAvaliacoesController@resumo')->name('get-avaliacao-geral');

            Route::get('/relatorios/secretaria', 'RelatoriosAvaliacoesController@resumoSecretariasList')->name('get-list-resumo-avaliacoes-secretaria');
            Route::get('/relatorios/secretaria/{secretaria}', 'RelatoriosAvaliacoesController@resumoSecretaria')->name('get-resumo-avaliacoes-secretaria');

            Route::get('/relatorios/secretaria/{secretaria}/avaliacoes/mes', 'RelatoriosAvaliacoesController@avaliacoesPorMesSecretaria')
                ->middleware('throttle:60,60')
                ->name('get-resumo-avaliacoes-secretaria-avaliacoes-mes');

            Route::get('/relatorios/unidade', 'RelatoriosAvaliacoesController@resumoUnidadeSecrList')->name('get-list-resumo-avaliacoes-unidade');
            Route::get('/relatorios/secretaria/{secretaria}/unidade/{unidade}', 'RelatoriosAvaliacoesController@resumoUnidadeSecr')->name('get-resumo-avaliacoes-unidade');

            Route::get('/relatorios/unidade/{unidade}/notas-mes', 'RelatoriosAvaliacoesController@notasPorMesUnidade')
                ->middleware('throttle:60,60')
                ->name('get-resumo-avaliacoes-unidade-notas-mes');
            Route::get('/relatorios/unidade/{unidade}/avaliacoes-mes', 'RelatoriosAvaliacoesController@avaliacoesPorMesUnidade')
                ->middleware('throttle:60,60')
                ->name('get-resumo-avaliacoes-unidade-avaliacoes-mes');

            //Gerenciar Unidade
            Route::get('/unidade', 'UnidadeSecrController@listagem')->name('get-unidades-secr-list');
            Route::get('/unidade/create', 'UnidadeSecrController@createUnidade')->name('get-create-unidade');
            Route::post('/unidade', 'UnidadeSecrController@storeUnidade')->name('post-store-unidade');
            Route::get('/unidade/{unidade}', 'UnidadeSecrController@visualizar')->name('get-unidades-secr-view');
            Route::get('/unidade/{unidade}/edit', 'UnidadeSecrController@editUnidade')->name('get-edit-unidade-view');
            Route::patch('/unidade/{unidade}/update', 'UnidadeSecrController@updateUnidade')->name('patch-update-unidade-secr');
            Route::get('/unidade/{unidade}/ativar', 'UnidadeSecrController@ativarDesativar')->name('get-ativar-unidade-secr');
            Route::get('/unidade/{unidade}/qr-code', 'UnidadeSecrController@gerarQrcode')->name('get-qrcode-unidade-secr');

            Route::post('/setor/{unidade}', 'SetoresController@storeSetor')->name('post-store-setor');
            Route::patch('/setor/{setor}/update', 'SetoresController@updateSetor')->name('patch-update-setor');
            Route::delete('/setor/{setor}', 'SetoresController@deleteSetor')->name('delete-delete-setor');
            Route::get('/setor/{setor}/ativar', 'SetoresController@ativarDesativar')->name('get-toggle-setor-status');
            Route::get('/setor/{setor}/tipos-avaliacao', 'SetoresController@getTiposAvaliacaoSetor')->name('get-tipos-avaliacao-setor');
            Route::get('/setor/{setor}/qr-code', 'SetoresController@gerarQrcode')->name('get-qrcode-setor');
        });
    }
);

//não logado
Route::prefix('avaliacoes')->namespace('Publico')->group(function () {
    Route::get('/unidade/setores/{unidadeToken}', 'AvaliacoesController@listSetores')->name('get-avaliacao-setores');
    Route::get('/unidade/{setorToken}/avaliar', 'AvaliacoesController@viewAvaliacao')->name('get-view-avaliacao');
    Route::post('/unidade/{setorToken}/avaliar', 'AvaliacoesController@storeAvaliacao')->name('post-store-avaliacao');
    // ->middleware('throttle:1,1440');
    Route::view('/agradecer', 'public.avaliacoes.agradecimento')->name('agradecimento-avaliacao');
});

Route::any('{url}', function () {
    return response()->view('errors.404', ['error' => 'Not Found'], 404);
})->where('url', '.*');
