<?php

namespace App\Models\Avaliacao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setor extends Model
{
    use SoftDeletes;

    protected $table = "setores";

    protected $guarded = [];
}
