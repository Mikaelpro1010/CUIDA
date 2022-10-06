<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Permission extends Model
{
    protected $guarded = [];

    //Unidade Secretaria
    public const PERMISSION_UNIDADE_SECRETARIA_LIST = 'listar unidade secretaria';
    public const PERMISSION_UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA = 'unidade secretaria acessar qualquer secretaria';
    public const PERMISSION_UNIDADE_SECRETARIA_CREATE = 'criar unidade secretaria';
    public const PERMISSION_UNIDADE_SECRETARIA_CREATE_ANY = 'criar unidade de qualquer secretaria';
    public const PERMISSION_UNIDADE_SECRETARIA_VIEW = 'visualizar unidade secretaria';
    public const PERMISSION_UNIDADE_SECRETARIA_UPDATE = 'atualizar unidade secretaria';
    public const PERMISSION_UNIDADE_SECRETARIA_TOGGLE_ATIVO = 'atualizar unidade secretaria';
    public const PERMISSION_UNIDADE_SECRETARIA_DELETE = 'deletar unidade secretaria';

    public const PERMISSIONS = [

        //Unidade Secretaria
        self::PERMISSION_UNIDADE_SECRETARIA_LIST,
        self::PERMISSION_UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA,
        self::PERMISSION_UNIDADE_SECRETARIA_CREATE,
        self::PERMISSION_UNIDADE_SECRETARIA_CREATE_ANY,
        self::PERMISSION_UNIDADE_SECRETARIA_VIEW,
        self::PERMISSION_UNIDADE_SECRETARIA_UPDATE,
        self::PERMISSION_UNIDADE_SECRETARIA_TOGGLE_ATIVO,
        self::PERMISSION_UNIDADE_SECRETARIA_DELETE,
    ];

    public static function getPermission(string $permission): Permission
    {
        $p = self::getAllFromCache()->where('permission', $permission)->first();
        if (is_null($p)) {
            $p = Permission::query()->create(['permission' => $permission]);
        }
        return $p;
    }

    public static function getAllFromCache(): Collection
    {
        $permissions = Cache::rememberForever('permissions', function () {
            return self::all();
        });
        return $permissions;
    }

    public static function existsOnCache(string $permission): bool
    {
        return self::getAllFromCache()->where('permission', $permission)->isNotEmpty();
    }
}
