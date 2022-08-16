<?php

namespace App\Models\Manifest;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    protected $table = 'recursos';
    protected $guarded = [];

    public function manifestacao()
    {
        return $this->belongsTo(Manifest::class, 'id_manifestacao', 'id');
    }
}
