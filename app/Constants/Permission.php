<?php

namespace App\Constants;

class Permission
{
    //Gerenciamento
    //Usuários
    public const GERENCIAR_USUARIOS_LIST = 'gerenciamento de usuarios listar';
    public const GERENCIAR_USUARIOS_VIEW = 'gerenciamento de usuarios visualizar';
    public const GERENCIAR_USUARIOS_CREATE = 'gerenciamento de usuarios criar';
    public const GERENCIAR_USUARIOS_EDIT = 'gerenciamento de usuarios editar';
    public const GERENCIAR_USUARIOS_EDIT_PASSWORD = 'gerenciamento de usuarios editar senha';
    public const GERENCIAR_USUARIOS_DELETE = 'gerenciamento de usuarios deletar';

    public const GERENCIAR_USUARIOS = [
        self::GERENCIAR_USUARIOS_LIST,
        self::GERENCIAR_USUARIOS_VIEW,
        self::GERENCIAR_USUARIOS_CREATE,
        self::GERENCIAR_USUARIOS_EDIT,
        self::GERENCIAR_USUARIOS_EDIT_PASSWORD,
        self::GERENCIAR_USUARIOS_DELETE,
    ];

    //Unidade Secretaria
    public const UNIDADE_SECRETARIA_LIST = 'listar unidade secretaria';
    public const UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA = 'unidade secretaria acessar qualquer secretaria';
    public const UNIDADE_SECRETARIA_CREATE = 'criar unidade secretaria';
    public const UNIDADE_SECRETARIA_CREATE_ANY = 'criar unidade de qualquer secretaria';
    public const UNIDADE_SECRETARIA_VIEW = 'visualizar unidade secretaria';
    public const UNIDADE_SECRETARIA_UPDATE = 'atualizar unidade secretaria';
    public const UNIDADE_SECRETARIA_TOGGLE_ATIVO = 'ativar/desativar unidade secretaria';
    public const UNIDADE_SECRETARIA_DELETE = 'deletar unidade secretaria';

    public const UNIDADE_SECRETARIA = [
        self::UNIDADE_SECRETARIA_LIST,
        self::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA,
        self::UNIDADE_SECRETARIA_CREATE,
        self::UNIDADE_SECRETARIA_CREATE_ANY,
        self::UNIDADE_SECRETARIA_VIEW,
        self::UNIDADE_SECRETARIA_UPDATE,
        self::UNIDADE_SECRETARIA_TOGGLE_ATIVO,
        self::UNIDADE_SECRETARIA_DELETE,
    ];

    //Resumo das avaliaçoes
    public const RESUMO_AVALIACOES_GERAL_VIEW = 'visualizar resumo geral';
    public const RESUMO_AVALIACOES_SECRETARIA_VIEW = 'visualizar resumo secretaria';
    public const RESUMO_AVALIACOES_UNIDADE_VIEW = 'visualizar resumo unidade';

    public const RESUMO_AVALIACOES = [
        self::RESUMO_AVALIACOES_GERAL_VIEW,
        self::RESUMO_AVALIACOES_SECRETARIA_VIEW,
        self::RESUMO_AVALIACOES_UNIDADE_VIEW,
    ];

    public const PERMISSIONS = [
        'Gerenciamento de Usuários' => self::GERENCIAR_USUARIOS,
        'Modulo Unidades das Secretarias' => self::UNIDADE_SECRETARIA,
        'Relatórios Modulo Avaliação' => self::RESUMO_AVALIACOES,
    ];
}
