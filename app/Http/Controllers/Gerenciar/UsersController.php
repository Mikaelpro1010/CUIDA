<?php

namespace App\Http\Controllers\Gerenciar;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function listUsers()
    {
        $this->authorize(Permission::GERENCIAR_USUARIOS_LIST);

        $users = User::query()
            ->when(
                request()->pesquisa,
                function ($query) {
                    return $query
                        ->where(
                            'name',
                            'like',
                            '%' . request()->pesquisa . '%'
                        )
                        ->orWhere(
                            'email',
                            'like',
                            '%' . request()->pesquisa . '%'
                        );
                }
            )
            ->when(
                request()->tipo_usuario,
                function ($query) {
                    return $query
                        ->where('role_id', request()->tipo_usuario);
                }
            )
            ->with('role', 'secretarias')
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends([
                'pesquisa' => request()->pesquisa,
                'tipo_usuario' => request()->tipo_usuario,
            ]);

        $roles = Role::get();

        return view('admin.gerenciar.usuarios.usuarios-listar', compact('users', 'roles'));
    }

    public function viewUser($user_id)
    {
        $this->authorize(Permission::GERENCIAR_USUARIOS_VIEW);
        $user = User::with('secretarias', 'role')->find($user_id);
        return view('admin.gerenciar.usuarios.usuarios-visualizar', compact('user'));
    }

    public function createUser(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_USUARIOS_CREATE);
        $roles = Role::get();
        $secretarias = Secretaria::where('ativo', true)->orderBy('nome', 'asc')->get();
        return view('admin.gerenciar.usuarios.usuario-criar', compact('roles', 'secretarias'));
    }

    public function storeUser(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_USUARIOS_CREATE);
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'tipo' => 'required|integer',
                'secretaria' => 'required|array',
                'senha' => 'required|string|min:8|confirmed',
            ],
            [
                'required' => 'O campo :attribute não pode ser vazio!',
                'email.email' => 'O campo Email precisa ter um Email válido!',
                'name.max' => 'O campo Nome não pode conter mais de 255 caracteres!',
                'unique' => 'O campo :attribute não pode ser vazio!',
                'confirmed' => 'O campo Confirmar Senha deve ser igual a Senha!',
            ],
            [
                'name' => 'Nome',
                'email' => 'Email',
                'tipo' => 'Tipo de Usuário',
                'senha' => 'Senha',
                'secretaria' => 'Secretaria',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->tipo,
            'password' => bcrypt($request->senha),
        ]);

        $user->secretarias()->attach($request->secretaria);

        return redirect()->route('get-users-list')->with(['success' => 'Usuário Cadastrado com Sucesso!']);
    }

    public function editUser(User $user)
    {
        $this->authorize(Permission::GERENCIAR_USUARIOS_EDIT);
        $roles = Role::get();
        $secretarias = Secretaria::where('ativo', true)->orderBy('nome', 'asc')->get();

        return view('admin.gerenciar.usuarios.usuario-editar', compact('roles', 'user', 'secretarias'));
    }

    public function updateUser(User $user, Request $request)
    {
        $this->authorize(Permission::GERENCIAR_USUARIOS_EDIT);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'tipo' => 'required|integer',
                'secretaria' => 'required|array',
            ],
            [
                'name.required' => 'O campo Nome não pode ser vazio!',
                'email.required' => 'O campo Email não pode ser vazio!',
                'tipo.required' => 'O campo Tipo de Usuário não pode ser vazio!',
                'secretaria.required' => 'O campo Secretaria não pode ser vazio!',
                'email.email' => 'O campo Email precisa ter um Email válido!',
                'name.max' => 'O campo Nome não pode conter mais de 255 caracteres!',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user2 = User::query()->where('email', $request->email)->first();

        if (!$user->is($user2)) {
            return redirect()
                ->back()
                ->withErrors(['email' => 'Este email já está sendo utilizado por outro usuário!'])
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->tipo
        ]);

        $user->secretarias()->sync($request->secretaria);

        return redirect()->route('get-users-list')->with(['success' => 'Usuário editado com Sucesso!']);
    }

    public function editUserPassword(User $user)
    {
        $this->authorize(Permission::GERENCIAR_USUARIOS_EDIT_PASSWORD);
        return view('admin.gerenciar.usuarios.usuario-editar-senha', compact('user'));
    }

    public function updateUserPassword(User $user, Request $request)
    {
        $this->authorize(Permission::GERENCIAR_USUARIOS_EDIT_PASSWORD);

        $validator = Validator::make(
            $request->all(),
            [
                'senha' => 'required|string|min:8|confirmed',
            ],
            [
                'required' => 'O campo :attribute não pode ser vazio!',
                'confirmed' => 'O campo Confirmar Senha deve ser igual a Senha!',
            ],
            [
                'senha' => 'Senha',
                'senha_Confirmation' => 'Confirmar Senha',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'password' => $request->senha
        ]);

        return redirect()->route('get-users-list')->with(['success' => 'Senha atualizada com Sucesso!']);
    }

    public function deleteUser(User $user)
    {
        $this->authorize(Permission::GERENCIAR_USUARIOS_DELETE);
        if ($user->is(auth()->user())) {
            return redirect()->route('get-users-list')->withErrors(['erro' => 'Não é possivel deletar seu próprio Usuário!']);
        } else {
            $user->delete();
            return redirect()->route('get-users-list')->with(['success' => 'Usuário deletado com Sucesso!']);
        }
    }
}
