<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Permission extends Model
{
    protected $guarded = [];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public static function getPermission(string $permission): Permission
    {
        $p = self::getAllFromCache()->where('permission', $permission)->first();
        if (is_null($p)) {
            $p = self::query()->create(['permission' => $permission]);
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
