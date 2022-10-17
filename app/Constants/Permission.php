<?php

namespace App\Constants;

class Permission
{

    //Unidade Secretaria
    public const UNIDADE_SECRETARIA_LIST = 'listar unidade secretaria';
    public const UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA = 'unidade secretaria acessar qualquer secretaria';
    public const UNIDADE_SECRETARIA_CREATE = 'criar unidade secretaria';
    public const UNIDADE_SECRETARIA_CREATE_ANY = 'criar unidade de qualquer secretaria';
    public const UNIDADE_SECRETARIA_VIEW = 'visualizar unidade secretaria';
    public const UNIDADE_SECRETARIA_UPDATE = 'atualizar unidade secretaria';
    public const UNIDADE_SECRETARIA_TOGGLE_ATIVO = 'atualizar unidade secretaria';
    public const UNIDADE_SECRETARIA_DELETE = 'deletar unidade secretaria';

    //Resumo das avaliaçoes
    public const RESUMO_AVALIACOES_GERAL_VIEW = 'visualizar resumo geral';
    public const RESUMO_AVALIACOES_SECRETARIA_VIEW = 'visualizar resumo secretaria';
    public const RESUMO_AVALIACOES_UNIDADE_VIEW = 'visualizar resumo unidade';

    public const PERMISSIONS = [
        //Unidade Secretaria
        self::UNIDADE_SECRETARIA_LIST,
        self::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA,
        self::UNIDADE_SECRETARIA_CREATE,
        self::UNIDADE_SECRETARIA_CREATE_ANY,
        self::UNIDADE_SECRETARIA_VIEW,
        self::UNIDADE_SECRETARIA_UPDATE,
        self::UNIDADE_SECRETARIA_TOGGLE_ATIVO,
        self::UNIDADE_SECRETARIA_DELETE,
        self::RESUMO_AVALIACOES_GERAL_VIEW,
        self::RESUMO_AVALIACOES_SECRETARIA_VIEW,
    ];
}
