<?php

namespace App\Models\Avaliacao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAvaliacao extends Model
{
    use SoftDeletes;

    protected $table = "tipos_avaliacao";
    
    protected $guarded = [];
}
