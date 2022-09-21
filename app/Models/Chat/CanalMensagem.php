<?php

namespace App\Models\Chat;

use App\Models\AppUser;
use App\Models\Manifest\Manifest;
use Illuminate\Database\Eloquent\Model;

class CanalMensagem extends Model
{
    protected $table = 'canais_mensagem';
    protected $guarded = [];

    const STATUS_RESPONDIDO = 1;
    const STATUS_AGUARDANDO_RESPOSTA = 2;
    const STATUS_ENCERRADO = 3;

    const STATUS_CANAL_MSG = [
        self::STATUS_RESPONDIDO => 'Respondido',
        self::STATUS_AGUARDANDO_RESPOSTA => 'Aguardando Resposta',
        self::STATUS_ENCERRADO => 'Encerrado'
    ];

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class, 'id_canal_mensagem', 'id');
    }

    public function autor()
    {
        return $this->hasOne(AppUser::class, 'id', 'id_app_user');
    }

    public function manifestacao()
    {
        return $this->belongsTo(Manifest::class, 'id_manifestacao', 'id');
    }
}
