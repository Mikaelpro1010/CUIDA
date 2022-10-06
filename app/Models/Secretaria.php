<?php

namespace App\Models;

use App\Models\Avaliacao\Unidade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Secretaria extends Model
{
    protected $table = "secretarias";
    protected $guarded = [];

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidade::class, 'secretaria_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
