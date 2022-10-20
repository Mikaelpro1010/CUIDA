<?php

namespace App\Http\Controllers\Gerenciar;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function listUsers()
    {
        $this->authorize(Permission::ADMIN_LIST_USERS);

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
            ->with('role')
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends([
                'pesquisa' => request()->pesquisa,
                'tipo_usuario' => request()->tipo_usuario,
            ]);

        $roles = Role::get();

        return view('admin.gerenciar.usuarios.list-user', compact('users', 'roles'));
    }

    public function viewUser(User $user)
    {
        $this->authorize(Permission::ADMIN_SEE_USER);
        return view('admin.gerenciar.usuarios.see-user', compact('user'));
    }

    public function createUser(Request $request)
    {
        $this->authorize(Permission::ADMIN_CREATE_USERS);
        $roles = Role::get();
        return view('admin.gerenciar.usuarios.create-user', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $this->authorize(Permission::ADMIN_CREATE_USERS);
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'tipo' => 'required|integer',
                'senha' => 'required|string|min:8|confirmed',
            ],
            [
                'required' => 'O campo :atribute não pode ser vazio!',
                'email.email' => 'O campo Email precisa ter um Email válido!',
                'name.max' => 'O campo Nome não pode conter mais de 255 caracteres!',
            ],
            [
                'name' => 'Nome',
                'email' => 'Email',
                'tipo' => 'Tipo de Usuário',
                'senha' => 'Senha',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->tipo,
            'password' => bcrypt($request->senha),
        ]);

        return redirect()->route('get-users-list')->with(['success' => 'Usuário Cadastrado com Sucesso!']);
    }

    public function editUser(User $user)
    {
        $this->authorize(Permission::ADMIN_EDIT_USERS);
        $roles = Role::get();

        return view('admin.gerenciar.usuarios.edit-user', compact('roles', 'user'));
    }

    public function updateUser(User $user, Request $request)
    {
        $this->authorize(Permission::ADMIN_EDIT_USERS);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'tipo' => 'required|integer'
            ],
            [
                'name.required' => 'O campo Nome não pode ser vazio!',
                'email.required' => 'O campo Email não pode ser vazio!',
                'tipo.required' => 'O campo Tipo de Usuário não pode ser vazio!',
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

        return redirect()->route('get-users-list')->with(['success' => 'Usuário editado com Sucesso!']);
    }

    public function deleteUser(User $user)
    {
        $this->authorize(Permission::ADMIN_DELETE_USERS);
        if ($user->is(auth()->user())) {
            return redirect()->route('get-users-list')->withErrors(['erro' => 'Não é possivel deletar seu próprio Usuário!']);
        } else {
            $user->delete();
            return redirect()->route('get-users-list')->with(['success' => 'Usuário deletado com Sucesso!']);
        }
    }
}
