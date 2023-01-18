<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motivacao extends Model
{
    use SoftDeletes;

    protected $table = 'motivacoes';

    protected $guarded = [];

}
