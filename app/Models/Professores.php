<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professores extends Model
{
    protected $fillable = [
        'name', 'disciplina',
    ];
}
