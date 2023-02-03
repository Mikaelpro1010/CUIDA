<?php

namespace App\Models\Avaliacao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setor extends Model
{
    use SoftDeletes;

    protected $table = "setores";

    protected $guarded = [];

    public function tiposAvaliacao(): BelongsToMany
    {
        return $this->belongsToMany(TipoAvaliacao::class)->withTrashed();
    }

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }
}
