<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AnexoMensagem extends Model
{
    protected $table = 'anexos_mensagens';
    protected $guarded = [];

    protected $appends = ['viewUrl', 'downloadUrl'];

    public function getViewUrlAttribute()
    {
        return route('view-anexo', $this->id);
    }

    public function getDownloadUrlAttribute()
    {
        return route('download-anexo', $this->id);
    }
}
