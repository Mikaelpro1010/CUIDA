<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AnexoMensagem extends Model
{
    protected $table = 'anexos_mensagens';
    protected $guarded = [];

    public function getUrl()
    {
        return url(Storage::url($this->caminho . $this->nome));
    }
}
