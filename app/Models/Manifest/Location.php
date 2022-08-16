<?php

namespace App\Models\Manifest;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    protected $guarded = [];

    public function manifest()
    {
        return $this->belongsTo(Manifest::class, 'id_manifestacao', 'id');
    }
}
