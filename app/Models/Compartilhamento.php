<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compartilhamento extends Model
{
    protected $table = 'compartilhamentos';

    protected $guarded = [];

    public function manifestacao(): BelongsTo
    {
        return $this->belongsTo(Manifestacoes::class);
    }

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class);
    }
}
