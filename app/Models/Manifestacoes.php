<?php

namespace App\Models;

use App\Models\Manifest\Recurso;
use Illuminate\Database\Eloquent\Model;

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

    public function estadoProcesso()
    {
        return $this->hasOne(EstadosProcesso::class, 'id','estado_processo_id');
    }

    public function tipoManifestacao()
    {
        return $this->hasOne(TiposManifestacao::class, 'id', 'tipo_manifestacao_id');
    }

    public function situacao()
    {
        return $this->hasOne(Situacao::class, 'id', 'situacao_id');
    }


    public function prorrogacao()
    {
        return $this->hasMany(Prorrogacao::class,'manifestacao_id', 'id');
    }

    public function recursos(){
        return $this->hasMany(Recurso::class, 'id_manifestacao', 'id');
    }
    
    public function motivacao(){
        return $this->hasOne(Motivacao::class, 'id', 'motivacao_id');
    }

    public function historico(){
        return $this->hasMany(Historico::class, 'manifestacao_id', 'id');
    }
    
    // public function historico(){
    //     return $this->hasOne(Historico::class, 'id', 'historico_id');
    // }
}
