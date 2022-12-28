<?php

namespace App\Models\Manifest;

use App\Models\Manifestacoes;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    protected $table = 'recursos';
    protected $guarded = [];

    public function manifestacao()
    {
        return $this->belongsTo(Manifestacoes::class);
    }
    
    public function autorResposta()
    {
        return $this->belongsTo(User::class);
    }
}
