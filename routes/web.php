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

use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\SetorTipoAvaliacao;
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

// Route::get('/teste', function () {
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
// })->name('teste');

// Route::get('/anexos', function () {
//     $arquivos = AnexoMensagem::all();
//     $result = "";
//     foreach ($arquivos as $key => $arquivo) {
//         $result .= $key + 1 . " - <a href=" . env('APP_URL') . Storage::url($arquivo->caminho . $arquivo->nome) . " target='_blank'>$arquivo->nome_original</a> <br>";
//     }
//     return $result;
// })->name('storageUrl');


Route::get('/teste', function () {

    $ano = [2021, 2022, 2023];
    $mes = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    $nota = [2, 4, 6, 8, 10];

    for ($i = 0; $i < 5000; $i++) {
        // Avaliacao::create([
        //     'setor_id' => 1,
        //     'tipo_avaliacao_id' => 13,
        //     'nota' =>  $nota[rand() % 5],
        //     'created_at' => $ano[rand() % 3] . "-" . $mes[rand() % 12] . '-01 00:00:00'
        // ]);
    }
})->name('teste');

//Rotas Não Logadas
Route::view('/', 'public.home')->name('home');
Route::view('/politicas-de-privacidade', 'public.politicas')->name('politicas');
Route::view('/termos-de-uso', 'public.termos')->name('termos');

// Route::get('pagina-manifestacao/visualizar', 'Publico\PaginaManifestController@visualizarPagina')->name("vis-pagina_manifestacao");
// Route::post('manifestacao/cadastrar/novo', 'Publico\PaginaManifestController@cadastrar')->name("cadastrar-manifestacao");
// Route::post('pagina-manifestacao/visualizar-manifestacao', 'Publico\PaginaManifestController@visualizarManifestacao')->name("vis-manifestacao");
// Route::post('pagina-manifestacao/visualizar-manifestacao/recurso/criar', 'Publico\PaginaManifestController@criarRecurso')->name("criar-recurso");

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
            }
        );

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
            Route::get('/unidade/{unidade}/relatorio', 'RelatoriosUnidadeController@relatorio')->name('get-unidades-relatorio');

            //Comentarios
            Route::get('/comentario', 'ComentariosAvaliacoesController@listComentarios')->name('get-comentarios-avaliacoes-list');
            Route::get('/comentario/{id}', 'ComentariosAvaliacoesController@viewComentarios')->name('get-comentarios-avaliacoes-view');


            // Setor
            Route::post('/setor/{unidade}', 'SetoresController@storeSetor')->name('post-store-setor');
            Route::patch('/setor/{setor}/update', 'SetoresController@updateSetor')->name('patch-update-setor');
            Route::delete('/setor/{setor}', 'SetoresController@deleteSetor')->name('delete-delete-setor');
            Route::get('/setor/{setor}/ativar', 'SetoresController@ativarDesativar')->name('get-toggle-setor-status');
            Route::get('/setor/{setor}/tipos-avaliacao', 'SetoresController@getTiposAvaliacaoSetor')->name('get-tipos-avaliacao-setor');
            Route::get('/setor/{setor}/qr-code', 'SetoresController@gerarQrcode')->name('get-qrcode-setor');

            // Comentarios
            Route::get('/comentario', 'ComentariosAvaliacoesController@listComentarios')->name('get-comentarios-avaliacoes-list');
            Route::get('/comentario/{id}', 'ComentariosAvaliacoesController@viewComentarios')->name('get-comentarios-avaliacoes-view');

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
