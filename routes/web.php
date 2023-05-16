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

use App\Models\Avaliacao\Avaliacao;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Route::get('/teste', function () {
// })->name('teste');

//Rotas Não Logadas
Route::view('/', 'public.home')->name('home');
Route::view('/politicas-de-privacidade', 'public.politicas')->name('politicas');
Route::view('/termos-de-uso', 'public.termos')->name('termos');

Route::prefix('avaliacoes')->namespace('Publico')->group(function () {
    Route::get('/unidade/setores/{unidadeToken}', 'AvaliacoesController@listSetores')->name('get-avaliacao-setores');
    Route::get('/unidade/{setorToken}/avaliar', 'AvaliacoesController@viewAvaliacao')->name('get-view-avaliacao')
        ->middleware('throttle:4,1');
    Route::post('/unidade/{setorToken}/avaliar', 'AvaliacoesController@storeAvaliacao')->name('post-store-avaliacao')
        ->middleware('throttle:8,1');
    Route::view('/agradecer', 'public.avaliacoes.agradecimento')->name('agradecimento-avaliacao')
        ->middleware('throttle:6,1');
});

Route::middleware(['auth:web'])->group(
    function () {
        //superAdm
        Route::prefix('super-adm')->group(
            function () {
                Route::get('/down', function () {
                    if (auth()->user()->email == 'asd@mail.com') {
                        Artisan::call('down');
                    }
                    return redirect()->route('home');
                });
                Route::get('/cache-flush', function () {
                    if (auth()->user()->email == 'asd@mail.com') {
                        Cache::flush();
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
            }
        );

        //modulo Avaliacoes
        Route::prefix('avaliacoes')->namespace('Avaliacoes')->group(function () {
            //Relatorios
            //Geral
            Route::get('/relatorios', 'RelatoriosAvaliacoesController@resumo')->name('get-avaliacao-geral');

            //Secretarias
            Route::get('/relatorios/secretaria', 'RelatoriosAvaliacoesController@resumoSecretariasList')->name('get-list-resumo-avaliacoes-secretaria');
            Route::get('/relatorios/secretaria/{secretaria}', 'RelatoriosAvaliacoesController@resumoSecretaria')->name('get-resumo-avaliacoes-secretaria');
            Route::get('/relatorios/secretaria/{secretaria}/avaliacoes/mes', 'RelatoriosAvaliacoesController@avaliacoesPorMesSecretaria')
                ->middleware('throttle:60,60')
                ->name('get-resumo-avaliacoes-secretaria-avaliacoes-mes');

            //Unidades
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
            Route::get('/unidade/{unidade}/relatorio', 'RelatoriosUnidadeController@relatorio')->name('get-unidades-relatorio');

            //Comentarios
            Route::get('/comentario', 'ComentariosAvaliacoesController@listComentarios')->name('get-comentarios-avaliacoes-list');
            Route::get('/comentario/export', 'ComentariosAvaliacoesController@exportcomments')->name('get-comentarios-avaliacoes-export');
            Route::get('/comentario/secretaria', 'ComentariosAvaliacoesController@getSecretariaInfo')->name('get-comentarios-scretaria-info');
            Route::get('/comentario/setor', 'ComentariosAvaliacoesController@getSetoresInfo')->name('get-comentarios-setores-info');

            // Setor
            Route::post('/setor/{unidade}', 'SetoresController@storeSetor')->name('post-store-setor');
            Route::patch('/setor/{setor}/update', 'SetoresController@updateSetor')->name('patch-update-setor');
            Route::delete('/setor/{setor}', 'SetoresController@deleteSetor')->name('delete-delete-setor');
            Route::get('/setor/{setor}/ativar', 'SetoresController@ativarDesativar')->name('get-toggle-setor-status');
            Route::get('/setor/{setor}/tipos-avaliacao', 'SetoresController@getTiposAvaliacaoSetor')->name('get-tipos-avaliacao-setor');
            Route::get('/setor/{setor}/qr-code', 'SetoresController@gerarQrcode')->name('get-qrcode-setor');

            //Tipo Avaliação
            Route::get('tipo-avaliacao', 'TipoAvaliacaoController@listTipoAvaliacao')->name('get-tipo-avaliacao-list');
            Route::get('tipo-avaliacao/create', 'TipoAvaliacaoController@createTipoAvaliacao')->name('get-create-tipo-avaliacao');
            Route::post('tipo-avaliacao', 'TipoAvaliacaoController@storeTipoAvaliacao')->name('post-store-tipo-avaliacao');
            Route::get('tipo-avaliacao/{tipoAvaliacao}', 'TipoAvaliacaoController@viewTipoavaliacao')->name('get-tipo-avaliacao-view');
            Route::get('tipo-avaliacao/{tipoAvaliacao}/edit', 'TipoAvaliacaoController@editTipoavaliacao')->name('get-edit-tipo-avaliacao-view');
            Route::patch('tipo-avaliacao/{tipoAvaliacao}', 'TipoAvaliacaoController@updateTipoavaliacao')->name('patch-update-tipo-avaliacao');
            Route::delete('tipo-avaliacao/{tipoAvaliacao}', 'TipoAvaliacaoController@deleteTipoavaliacao')->name('delete-delete-tipo-avaliacao');
            Route::get('tipo-avaliacao/{tipoAvaliacao}/toggle', 'TipoAvaliacaoController@toggleTipoavaliacaoStatus')->name("get-toggle-tipo-avaliacao-status");
            Route::get('tipo-avaliacao/{secretariaId}/secretaria', 'TipoAvaliacaoController@getTiposAvaliacaoSecretaria')->name("get-tipo-avaliacao-secretaria");
        });
    }
);

Route::any('{url}', function () {
    return response()->view('errors.404', ['error' => 'Not Found'], 404);
})->where('url', '.*');
