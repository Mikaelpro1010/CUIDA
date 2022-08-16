<?php

namespace App\Models\Manifest;

use App\Models\AppUser;
use App\Models\Chat\CanalMensagem;
use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    protected $table = 'manifests';
    protected $guarded = [];

    const ESTADO_PROCESSO_INICIAL = 1; //"INICIAL";
    const ESTADO_PROCESSO_RECURSO = 2; //"RECURSO";

    const ESTADO_PROCESSO = [
        self::ESTADO_PROCESSO_INICIAL => "Inicial",
        self::ESTADO_PROCESSO_RECURSO => "Recurso",
    ];

    const MOTIVACAO_MANIFESTACAO = 1; //"MANIFESTACAO";
    const MOTIVACAO_PEDIDO_INFORMACAO = 2; //"PEDIDO_INFORMACAO";

    const MOTIVACAO = [
        self::MOTIVACAO_MANIFESTACAO => "Manifestacao",
        self::MOTIVACAO_PEDIDO_INFORMACAO => "Solicitação de Informação",
    ];

    const TIPO_SUGESTAO = 6;
    const TIPO_ELOGIO = 7;
    const TIPO_SOLICITACAO = 8;
    const TIPO_RECLAMACAO = 9;
    const TIPO_SOLICITACAO_DE_INFORMACAO = 21;
    const TIPO_DENÚNCIA = 13;
    const TIPO_DENUNCIA_FAKE_NEWS = 41;

    const TIPO_MANIFESTACAO = [
        self::TIPO_SUGESTAO => "Sugestão",
        self::TIPO_ELOGIO => "Elogio",
        self::TIPO_SOLICITACAO => "Solicitação",
        self::TIPO_RECLAMACAO => "Reclamação",
        self::TIPO_SOLICITACAO_DE_INFORMACAO => "Solicitação de Informação",
        self::TIPO_DENÚNCIA => "Denúncia",
        self::TIPO_DENUNCIA_FAKE_NEWS => "Denúncia Fake News",
    ];

    const COR_MANIFESTACAO = [
        self::TIPO_SUGESTAO => '#0092ed', //azul claro "Sugestão"
        self::TIPO_ELOGIO => '#122578', //azul "Elogio"
        self::TIPO_SOLICITACAO => '#d4bc06', //amarelo "Solicitação"
        self::TIPO_RECLAMACAO => '#bd7800', //laranja "Reclamação"
        self::TIPO_SOLICITACAO_DE_INFORMACAO => '#d4bc06', //amarelo "Solicitação de Informação"
        self::TIPO_DENÚNCIA => '#800200', //vermelho // "Denúncia"
        self::TIPO_DENUNCIA_FAKE_NEWS => '#800200', //vermelho "Denúncia Fake News"
    ];
    /**
     * if ($tipo->nome == 'DENÚNCIA FAKE NEWS' || $tipo->nome == 'DENÚNCIA') {
        $item['cor'] = '#800200'; //vermelho
    } elseif ($tipo->nome == 'SOLICITAÇÃO DE INFORMAÇÃO' || $tipo->nome == 'SOLICITAÇÃO ') {
        $item['cor'] = '#d4bc06'; //amarelo
    } elseif ($tipo->nome == 'RECLAMAÇÃO') {
        $item['cor'] = '#bd7800'; //laranja
    } elseif ($tipo->nome == 'ELOGIO') {
        $item['cor'] = '#122578'; //azul
    } elseif ($tipo->nome == 'SUGESTÃO') {
        $item['cor'] = '#0092ed'; //azul claro
    } else {
        $item['cor'] = '#c3c3c3'; //cinza
     */

    const SITUACAO_ABERTA = 1;
    const SITUACAO_EM_ANDAMENTO = 2;
    const SITUACAO_COMPARTILHADA = 3;
    const SITUACAO_RESPONDIDA_COMPARTILHAMENTO = 4;
    const SITUACAO_RESPONDIDA_PRORROGACAO = 5;
    const SITUACAO_AGUARDANDO_PRORROGACAO = 6;
    const SITUACAO_PRE_CONCLUIDA = 7;
    const SITUACAO_CONCLUIDA = 8;
    const SITUACAO_BLOQUEADA = 10;
    const SITUACAO_RECURSO = 11;

    const SITUACAO = [
        self::SITUACAO_ABERTA => 'Aberta',
        self::SITUACAO_EM_ANDAMENTO => 'Em Andamento',
        self::SITUACAO_COMPARTILHADA => 'Compartilhada',
        self::SITUACAO_RESPONDIDA_COMPARTILHAMENTO => 'Respondida do Compartilhamento',
        self::SITUACAO_RESPONDIDA_PRORROGACAO => 'Respondida da Porrogação',
        self::SITUACAO_AGUARDANDO_PRORROGACAO => 'Aguardando Porrogação',
        self::SITUACAO_PRE_CONCLUIDA => 'Pré-Concluída',
        self::SITUACAO_CONCLUIDA => 'Concluída',
        self::SITUACAO_BLOQUEADA => 'Bloqueada',
        self::SITUACAO_RECURSO => 'Recurso',
    ];

    public function autor()
    {
        return $this->belongsTo(AppUser::class, 'id_app_user', 'id');
    }

    public function location()
    {
        return $this->hasOne(Location::class, 'id_manifestacao', 'id');
    }

    public function recursos()
    {
        return $this->hasMany(Recurso::class, 'id_manifestacao', 'id');
    }

    public function canalMensagem()
    {
        return $this->hasOne(CanalMensagem::class, 'id_manifestacao', 'id');
    }
}
