<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerfilUsuarioController extends Controller
{
    public function viewUser(User $user)
    {
        $this->authorize(Permission::GERENCIAR_PERFIL_USUARIO_VIEW);
        return view('admin.perfil-usuario', compact('user'));
    }

    public function updateUser(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_PERFIL_USUARIO_EDIT);
        $name = $request->name;
        $email = $request->email;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
        ]);

        Auth::user()->update(['name' => $name, 'email' => $email]);

        return redirect()->back()->with('success', 'O nome/email de usuário foi alterado com sucesso!');
    }

    public function viewPassword(User $user)
    {
        $this->authorize(Permission::GERENCIAR_PERFIL_SENHA_VIEW);
        return view('admin.editar-senha', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $this->authorize(Permission::GERENCIAR_PERFIL_SENHA_EDIT);
        $user = Auth::user();
        $userPassword = $user->password;
        $newPass = $request->password;

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|digits:5',
            'confirm_password' => 'required|same:password',
        ]);

        if(Hash::check($request->current_password, $userPassword))
        { 
            DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => bcrypt($newPass)]);
            Auth::loginUsingId($user->id);

            return redirect()->back()->with('success', 'A senha foi alterada com sucesso!');
        }else{
            return redirect()->back()->with(['error' => 'As senhas informadas não coinciden']);
        }
    }

}
