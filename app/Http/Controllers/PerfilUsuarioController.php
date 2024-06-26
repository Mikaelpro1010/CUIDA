<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PerfilUsuarioController extends Controller
{
    public function viewPerfil()
    {
        $this->authorize(Permission::PERFIL_USUARIO_VIEW);
        return view('admin.perfil-usuario.perfil-usuario-visualizar');
    }

    public function editPerfil()
    {
        $this->authorize(Permission::PERFIL_USUARIO_EDIT);
        return view('admin.perfil-usuario.perfil-usuario-editar');
    }

    public function updatePerfil(Request $request)
    {
        $this->authorize(Permission::PERFIL_USUARIO_EDIT);
        $name = $request->name;
        $email = $request->email;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                Rule::unique('users')->ignore(auth()->user()->id, 'id')
            ]
        ]);

        auth()->user()->update([
            'name' => $name,
            'email' => $email
        ]);

        return redirect()->route('get-user-perfil')->with('success', 'O nome/email de usuário foi alterado com sucesso!');
    }

    public function viewEditPassword()
    {
        $this->authorize(Permission::PERFIL_USUARIO_EDIT_PASSWORD);
        return view('admin.perfil-usuario.perfil-usuario-editar-senha');
    }

    public function updatePassword(Request $request)
    {
        $this->authorize(Permission::PERFIL_USUARIO_EDIT_PASSWORD);
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (Hash::check($request->current_password, auth()->user()->password)) {

            $user = User::where('id', auth()->user()->id)->first();
            $user->update(['password' => $request->new_password]);

            Auth::loginUsingId($user->id);

            return redirect()->back()->with('success', 'A senha foi alterada com sucesso!');
        } else {
            return redirect()->route('get-user-perfil-password')->withErrors(['error' => 'As senhas informadas não coinciden']);
        }
    }
}
