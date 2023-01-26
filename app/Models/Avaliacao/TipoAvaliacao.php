<?php

namespace App\Models\Avaliacao;

use App\Models\Secretaria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAvaliacao extends Model
{
    use SoftDeletes;

    protected $table = "tipos_avaliacao";

    protected $guarded = [];

    public function unidade(): BelongsToMany
    {
        return $this->belongsToMany(Unidade::class);
    }

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class);
    }

}
