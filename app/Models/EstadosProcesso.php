<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadosProcesso extends Model
{

    use SoftDeletes;

    protected $table = 'estados_processo';

    protected $guarded = [];

    public function manifestacao()
    {
        return $this->belongsTo(Manifestacao::class, 'estado_processo_id', 'id');
    }
}
