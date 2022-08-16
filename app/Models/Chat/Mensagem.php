<?php

namespace App\Models\Chat;

use App\Models\AppUser;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table = 'mensagens';
    protected $guarded = [];

    const TIPO_OUVIDOR = 1;
    const TIPO_APP_USER = 2;

    const TIPO_MSG = [
        self::TIPO_OUVIDOR => 'Ouvidor',
        self::TIPO_APP_USER => 'AppUser',
    ];

    public function autorMsgUser()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function autorMsgAppUser()
    {
        return $this->belongsTo(AppUser::class, 'id_app_user', 'id');
    }

    public function canalMensagens()
    {
        return $this->belongsTo(CanalMensagem::class, 'id_canal_mensagem', 'id');
    }

    public function anexos()
    {
        return $this->hasMany(AnexoMensagem::class, 'id_mensagem', 'id');
    }
}
