<?php

namespace App\Http\Controllers\Configs;

use App\Constants\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function listRoles()
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_USUARIOS_LIST);

        $roles = Role::query()
            ->when(
                request()->pesquisa,
                function ($query) {
                    return $query
                        ->where(
                            'name',
                            'like',
                            '%' . request()->pesquisa . '%'
                        );
                }
            )
            ->with('users')
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->appends([
                'pesquisa' => request()->pesquisa,
                'tipo_usuario' => request()->tipo_usuario,
            ]);

        return view('admin.config.tipos-usuario.tipo-usuarios-listar', compact('roles'));
    }

    public function viewRole(Role $role)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_USUARIOS_VIEW);

        $permissionGroups = Permission::PERMISSIONS;

        return view('admin.config.tipos-usuario.tipo-usuario-visualizar', compact('role', 'permissionGroups'));
    }

    public function createRole(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_USUARIOS_CREATE);
        $permissionGroups = Permission::PERMISSIONS;

        return view('admin.config.tipos-usuario.tipo-usuario-criar', compact('permissionGroups'));
    }

    public function storeRole(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_USUARIOS_CREATE);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        return redirect()->route('get-roles-list')->with(['success' => 'Tipo de Usuário Cadastrado com Sucesso!']);
    }

    public function editRole(Role $role)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_USUARIOS_EDIT);
        $permissionGroups = Permission::PERMISSIONS;

        return view('admin.config.tipos-usuario.tipo-usuario-editar', compact('role', 'permissionGroups'));
    }

    public function updateRole(Role $role, Request $request)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_USUARIOS_EDIT);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        $role->permissions()->sync($request->permissions);
        Cache::forget('role-' . $role->id . '-permissions');

        return redirect()->route('get-roles-list')->with(['success' => 'Tipos de Usuário editado com Sucesso!']);
    }

    public function deleteRole(Role $role)
    {
        $this->authorize(Permission::GERENCIAR_TIPOS_USUARIOS_DELETE);

        if ($role->users->isNotEmpty()) {
            return redirect()->route('get-roles-list')->withErrors(['Erro' => 'Não é possivel deletar um tipo de Usuario que contenha Usuários ativos!']);
        } else {
            $role->delete();
            return redirect()->route('get-roles-list')->with(['success' => 'Tipo de Usuário deletado com Sucesso!']);
        }
    }
}
