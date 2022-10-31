<?php

namespace App\Constants;

class Permission
{
    //Manifestaçoes
    public const MANIFESTACAO_LIST = 'Listar Manifestações';
    public const MANIFESTACAO_VIEW = 'Visualizar Manifestações';
    public const MANIFESTACAO_CREATE = 'Criar Manifestações';
    public const MANIFESTACAO_RESPONDER = 'Invalidar Manifestações';
    public const MANIFESTACAO_COMPARTILHAR = 'Compartilhar Manifestações';
    public const MANIFESTACAO_PRORROGACAO = 'Prorrogar Manifestações';
    public const MANIFESTACAO_INVALIDAR = 'Invalidar Manifestações';
    public const MANIFESTACAO_CHAT = 'Chat das Manifestações';

    public const MANIFESTACOES = [
        self::MANIFESTACAO_LIST,
        self::MANIFESTACAO_VIEW,
        self::MANIFESTACAO_CREATE,
        self::MANIFESTACAO_RESPONDER,
        self::MANIFESTACAO_COMPARTILHAR,
        self::MANIFESTACAO_PRORROGACAO,
        self::MANIFESTACAO_INVALIDAR,
        self::MANIFESTACAO_CHAT,
    ];

    //Gerenciamento
    //Usuários
    public const GERENCIAR_USUARIOS_LIST = 'Listar Usuários';
    public const GERENCIAR_USUARIOS_VIEW = 'Visualizar Usuários';
    public const GERENCIAR_USUARIOS_CREATE = 'Criar Usuários';
    public const GERENCIAR_USUARIOS_EDIT = 'Editar Usuários';
    public const GERENCIAR_USUARIOS_EDIT_PASSWORD = 'Alterar Senha de Usuários';
    public const GERENCIAR_USUARIOS_DELETE = 'Deletar Usuários';

    public const GERENCIAR_USUARIOS = [
        self::GERENCIAR_USUARIOS_LIST,
        self::GERENCIAR_USUARIOS_VIEW,
        self::GERENCIAR_USUARIOS_CREATE,
        self::GERENCIAR_USUARIOS_EDIT,
        self::GERENCIAR_USUARIOS_EDIT_PASSWORD,
        self::GERENCIAR_USUARIOS_DELETE,
    ];

    //Secretarias
    public const GERENCIAR_SECRETARIAS_LIST = 'Listar Secretarias';
    public const GERENCIAR_SECRETARIAS_VIEW = 'Visualizar Secretarias';
    public const GERENCIAR_SECRETARIAS_CREATE = 'Criar Secretarias';
    public const GERENCIAR_SECRETARIAS_EDIT = 'Editar Secretarias';
    public const GERENCIAR_SECRETARIAS_ACTIVE_TOGGLE = 'Ativar ou Desativar Secretarias';

    public const GERENCIAR_SECRETARIAS = [
        self::GERENCIAR_SECRETARIAS_LIST,
        self::GERENCIAR_SECRETARIAS_VIEW,
        self::GERENCIAR_SECRETARIAS_CREATE,
        self::GERENCIAR_SECRETARIAS_EDIT,
        self::GERENCIAR_SECRETARIAS_ACTIVE_TOGGLE,
    ];

    // Tipos de Usuário
    public const GERENCIAR_TIPOS_USUARIOS_LIST = 'Listar Tipos de Usuário';
    public const GERENCIAR_TIPOS_USUARIOS_VIEW = 'Visualizar Tipos de Usuário';
    public const GERENCIAR_TIPOS_USUARIOS_CREATE = 'Criar Tipos de Usuário';
    public const GERENCIAR_TIPOS_USUARIOS_EDIT = 'Editar Tipos de Usuário';
    public const GERENCIAR_TIPOS_USUARIOS_DELETE = 'Deletar Tipos de Usuário';

    public const GERENCIAR_TIPOS_USUARIOS = [
        self::GERENCIAR_TIPOS_USUARIOS_LIST,
        self::GERENCIAR_TIPOS_USUARIOS_VIEW,
        self::GERENCIAR_TIPOS_USUARIOS_CREATE,
        self::GERENCIAR_TIPOS_USUARIOS_EDIT,
        self::GERENCIAR_TIPOS_USUARIOS_DELETE,
    ];

    //Avaliacoes
    public const UNIDADE_SECRETARIA_LIST = 'Listar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA = 'Unidade Secretaria acessar qualquer secretaria';
    public const UNIDADE_SECRETARIA_CREATE = 'Criar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_CREATE_ANY = 'Criar Unidade de qualquer Secretaria';
    public const UNIDADE_SECRETARIA_VIEW = 'Visualizar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_UPDATE = 'Atualizar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_TOGGLE_ATIVO = 'Ativar ou Desativar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_DELETE = 'Deletar Unidades da Secretaria';

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

    //Relatorio das avaliaçoes
    public const MODULO_AVALIACOES = 'Acesso ao módulo de avaliações';
    public const RELATORIO_AVALIACOES_GERAL_VIEW = 'Visualizar Relatório Geral';
    public const RELATORIO_AVALIACOES_SECRETARIA_VIEW = 'Visualizar Relatório por Secretaria';
    public const RELATORIO_AVALIACOES_UNIDADE_VIEW = 'Visualizar Relatório por Unidade da Secretaria';

    public const RELATORIO_AVALIACOES = [
        self::RELATORIO_AVALIACOES_GERAL_VIEW,
        self::RELATORIO_AVALIACOES_SECRETARIA_VIEW,
        self::RELATORIO_AVALIACOES_UNIDADE_VIEW,
    ];

    public const PERMISSIONS = [
        'Gerenciamento de Usuários' => self::GERENCIAR_USUARIOS,
        'Gerenciamento de Secretarias' => self::GERENCIAR_SECRETARIAS,
        'Gerenciamento de Tipos de Usuário' => self::GERENCIAR_TIPOS_USUARIOS,
        'Manifestações' => self::MANIFESTACOES,
        'Modulo Avaliação' => self::UNIDADE_SECRETARIA,
        'Relatórios Modulo Avaliação' => self::RELATORIO_AVALIACOES,
    ];
}