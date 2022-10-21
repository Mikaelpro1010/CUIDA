<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    use SoftDeletes;


    protected $guarded = [];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermission(string  $permission): void
    {
        if (!$this->hasPermission($permission)) {
            $p = Permission::getPermission($permission);
            $this->permissions()->attach($p);
        }
        Cache::forget('role-' . $this->id . '-permissions');
    }

    public function removePermission(string  $permission): void
    {
        $p = Permission::getPermission($permission);
        if (!is_null($p)) {
            $this->permissions()->detach($p->id);
        }
        Cache::forget('role-' . $this->id . '-permissions');
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = Cache::rememberForever('role-' . $this->id . '-permissions', function () {
            return $this->permissions()->get();
        });
        return $permissions->where('permission', $permission)->isNotEmpty();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
