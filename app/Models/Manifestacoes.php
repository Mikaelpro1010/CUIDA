<?php

namespace App\Models;

use App\Models\Manifest\Recurso;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Manifestacoes extends Model
{
    protected $table = 'manifestacoes';

    protected $guarded = [];

    const CELULAR = 1;
    const FIXO = 2;
    const WHATSAPP = 3;

    const TIPO_TELEFONE = [
        self::CELULAR => 'celular',
        self::FIXO => 'fixo',
        self::WHATSAPP => 'whatsapp',
    ];

    public function estadoProcesso(): HasOne
    {
        return $this->hasOne(EstadosProcesso::class, 'id', 'estado_processo_id');
    }

    public function tipoManifestacao(): HasOne
    {
        return $this->hasOne(TiposManifestacao::class, 'id', 'tipo_manifestacao_id');
    }

    public function situacao(): HasOne
    {
        return $this->hasOne(Situacao::class, 'id', 'situacao_id');
    }


    public function prorrogacao(): HasMany
    {
        return $this->hasMany(Prorrogacao::class, 'manifestacao_id');
    }

    public function recursos(): HasMany
    {
        return $this->hasMany(Recurso::class);
    }

    public function motivacao(): HasOne
    {
        return $this->hasOne(Motivacao::class, 'id', 'motivacao_id');
    }

    public function historico(): HasMany
    {
        return $this->hasMany(Historico::class, 'manifestacao_id');
    }

    public function compartilhamentos(): HasMany
    {
        return $this->hasMany(Compartilhamento::class, 'manifestacao_id')->orderBy('created_at');
    }

    // public function historico(){
    //     return $this->hasOne(Historico::class, 'id', 'historico_id');
    // }
}
