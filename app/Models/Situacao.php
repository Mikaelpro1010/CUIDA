<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Situacao extends Model
{
    use SoftDeletes;

    protected $table = 'situacoes';

    protected $guarded = [];

    public function situacao()
    {
        return $this->belongsTo('App\Situacao', 'situacao_id', 'id');
    }
}
