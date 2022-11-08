<?php

namespace App\Models;

use App\Models\Manifest\Manifest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TiposManifestacao extends Model
{
    use SoftDeletes;

    
    protected $table = 'tipos_manifestacao';

    protected $guarded = [];

    // public function manifestacoes()
    // {
    //     return $this->hasMany(Manifest::class, 'tipo_manifestacao_id', 'id');
    // }
}
