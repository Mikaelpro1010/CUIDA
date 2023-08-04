<?php

namespace App\Constants;

class Permission
{
    // Perfil de usuário
    public const PERFIL_USUARIO_VIEW = 'Visualizar Perfil de Usuário';
    public const PERFIL_USUARIO_EDIT = 'Editar Perfil de Usuário';
    public const PERFIL_USUARIO_EDIT_PASSWORD = 'Editar Senha de Usuário';

    public const PERFIL_USUARIO = [
        self::PERFIL_USUARIO_VIEW,
        self::PERFIL_USUARIO_EDIT,
        self::PERFIL_USUARIO_EDIT_PASSWORD,
    ];

    //Manifestaçoes//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public const MANIFESTACAO_LIST = 'Listar Manifestações';
    public const MANIFESTACAO_VIEW = 'Visualizar Manifestações';
    public const MANIFESTACAO_CREATE = 'Criar Manifestações';
    public const MANIFESTACAO_REPLY = 'Responder Manifestações';
    public const MANIFESTACAO_RECURSOS_CREATE = 'Criar recursos para Manifestações';
    public const MANIFESTACAO_RECURSOS_REPLY = 'Responder recursos para Manifestações';
    public const MANIFESTACAO_COMPARTILHAMENTO_CREATE = 'Compartilhar Manifestações';
    public const MANIFESTACAO_COMPARTILHAMENTO_REPLY = 'Compartilhar Manifestações';
    public const MANIFESTACAO_PRORROGACAO_REQUEST = 'Prorrogar Manifestações';
    public const MANIFESTACAO_PRORROGACAO_ACCEPT = 'Prorrogar Manifestações';
    public const MANIFESTACAO_INVALIDATE = 'Invalidar Manifestações';


    public const MANIFESTACOES = [
        self::MANIFESTACAO_LIST,
        self::MANIFESTACAO_VIEW,
        self::MANIFESTACAO_CREATE,
        self::MANIFESTACAO_REPLY,
        self::MANIFESTACAO_RECURSOS_CREATE,
        self::MANIFESTACAO_RECURSOS_REPLY,
        self::MANIFESTACAO_COMPARTILHAMENTO_CREATE,
        self::MANIFESTACAO_COMPARTILHAMENTO_REPLY,
        self::MANIFESTACAO_PRORROGACAO_REQUEST,
        self::MANIFESTACAO_PRORROGACAO_ACCEPT,
        self::MANIFESTACAO_INVALIDATE,
    ];

    // ouvidoria chat
    public const CHAT_MANIFESTACAO_LIST = 'Listar Chats das Manifestações';
    public const CHAT_MANIFESTACAO_VIEW = 'Visualizar Chat da Manifestação';
    public const CHAT_MANIFESTACAO_RESPONDER = 'Responder Chat da Manifestação';
    public const CHAT_MANIFESTACAO_ENCERRAR = 'Encerrar Chat da Manifestação';

    public const OUVIDORIA_CHAT = [
        self::CHAT_MANIFESTACAO_LIST,
        self::CHAT_MANIFESTACAO_VIEW,
        self::CHAT_MANIFESTACAO_RESPONDER,
        self::CHAT_MANIFESTACAO_ENCERRAR,
    ];

    //Gerenciamento//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Usuários
    public const GERENCIAR_USUARIOS_LIST = 'Listar Usuários';
    public const GERENCIAR_USUARIOS_VIEW = 'Visualizar Usuários';
    public const GERENCIAR_USUARIOS_VIEW_DELETED = 'Visualizar Usuários deletados';
    public const GERENCIAR_USUARIOS_CREATE = 'Criar Usuários';
    public const GERENCIAR_USUARIOS_EDIT = 'Editar Usuários';
    public const GERENCIAR_USUARIOS_EDIT_PASSWORD = 'Alterar Senha de Usuários';
    public const GERENCIAR_USUARIOS_DELETE = 'Deletar Usuários';

    public const GERENCIAR_USUARIOS = [
        self::GERENCIAR_USUARIOS_LIST,
        self::GERENCIAR_USUARIOS_VIEW,
        self::GERENCIAR_USUARIOS_VIEW_DELETED,
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

    //configurações//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

    // Tipos de Manifestaçao
    public const GERENCIAR_TIPOS_MANIFESTACAO_LIST = 'Listar Tipos de Manifestacao';
    public const GERENCIAR_TIPOS_MANIFESTACAO_VIEW = 'Visualizar Tipos de Manifestacao';
    public const GERENCIAR_TIPOS_MANIFESTACAO_CREATE = 'Criar Tipos de Manifestacao';
    public const GERENCIAR_TIPOS_MANIFESTACAO_EDIT = 'Editar Tipos de Manifestacao';
    public const GERENCIAR_TIPOS_MANIFESTACAO_DELETE = 'Deletar Tipos de Manifestacao';

    public const GERENCIAR_TIPOS_MANIFESTACAO = [
        self::GERENCIAR_TIPOS_MANIFESTACAO_LIST,
        self::GERENCIAR_TIPOS_MANIFESTACAO_VIEW,
        self::GERENCIAR_TIPOS_MANIFESTACAO_CREATE,
        self::GERENCIAR_TIPOS_MANIFESTACAO_EDIT,
        self::GERENCIAR_TIPOS_MANIFESTACAO_DELETE,
    ];

    // Estado do processo de manifestaçao
    public const GERENCIAR_ESTADOS_PROCESSO_LIST = 'Listar Estados do Processo';
    public const GERENCIAR_ESTADOS_PROCESSO_VIEW = 'Visualizar Estados do Processo';
    public const GERENCIAR_ESTADOS_PROCESSO_CREATE = 'Criar Estados do Processo';
    public const GERENCIAR_ESTADOS_PROCESSO_EDIT = 'Editar Estados do Processo';
    public const GERENCIAR_ESTADOS_PROCESSO_DELETE = 'Deletar Estados do Processo';

    public const GERENCIAR_ESTADOS_PROCESSO = [
        self::GERENCIAR_ESTADOS_PROCESSO_LIST,
        self::GERENCIAR_ESTADOS_PROCESSO_VIEW,
        self::GERENCIAR_ESTADOS_PROCESSO_CREATE,
        self::GERENCIAR_ESTADOS_PROCESSO_EDIT,
        self::GERENCIAR_ESTADOS_PROCESSO_DELETE,
    ];

    // Motivaçao da manifestacao
    public const GERENCIAR_MOTIVACOES_LIST = 'Listar Motivações';
    public const GERENCIAR_MOTIVACOES_VIEW = 'Visualizar Motivações';
    public const GERENCIAR_MOTIVACOES_CREATE = 'Criar Motivações';
    public const GERENCIAR_MOTIVACOES_EDIT = 'Editar Motivações';
    public const GERENCIAR_MOTIVACOES_DELETE = 'Deletar Motivações';

    public const GERENCIAR_MOTIVACOES = [
        self::GERENCIAR_MOTIVACOES_LIST,
        self::GERENCIAR_MOTIVACOES_VIEW,
        self::GERENCIAR_MOTIVACOES_CREATE,
        self::GERENCIAR_MOTIVACOES_EDIT,
        self::GERENCIAR_MOTIVACOES_DELETE,
    ];

    // situacoes da manifestacao
    public const GERENCIAR_SITUACOES_LIST = 'Listar Situações';
    public const GERENCIAR_SITUACOES_VIEW = 'Visualizar Situações';
    public const GERENCIAR_SITUACOES_CREATE = 'Criar Situações';
    public const GERENCIAR_SITUACOES_EDIT = 'Editar Situações';
    public const GERENCIAR_SITUACOES_DELETE = 'Deletar Situações';

    public const GERENCIAR_SITUACOES = [
        self::GERENCIAR_SITUACOES_LIST,
        self::GERENCIAR_SITUACOES_VIEW,
        self::GERENCIAR_SITUACOES_CREATE,
        self::GERENCIAR_SITUACOES_EDIT,
        self::GERENCIAR_SITUACOES_DELETE,
    ];

    // FAQs
    public const GERENCIAR_FAQS_LIST = 'Listar FAQs';
    public const GERENCIAR_FAQS_VIEW = 'Visualizar FAQs';
    public const GERENCIAR_FAQS_CREATE = 'Criar FAQs';
    public const GERENCIAR_FAQS_EDIT = 'Editar FAQs';
    public const GERENCIAR_FAQS_DELETE = 'Deletar FAQs';

    public const GERENCIAR_FAQS = [
        self::GERENCIAR_FAQS_LIST,
        self::GERENCIAR_FAQS_VIEW,
        self::GERENCIAR_FAQS_CREATE,
        self::GERENCIAR_FAQS_EDIT,
        self::GERENCIAR_FAQS_DELETE,
    ];

    //Avaliacoes //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Relatorio das avaliaçoes
    public const RELATORIO_AVALIACOES_GERAL_VIEW = 'Visualizar Relatório Geral';
    public const RELATORIO_AVALIACOES_SECRETARIA_VIEW = 'Visualizar Relatório por Secretaria';
    public const RELATORIO_AVALIACOES_UNIDADE_VIEW = 'Visualizar Relatório por Unidade da Secretaria';

    public const RELATORIO_AVALIACOES = [
        self::RELATORIO_AVALIACOES_GERAL_VIEW,
        self::RELATORIO_AVALIACOES_SECRETARIA_VIEW,
        self::RELATORIO_AVALIACOES_UNIDADE_VIEW,
    ];

    // Unidades das Secretarias
    public const UNIDADE_SECRETARIA_LIST = 'Listar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA = 'Unidade Secretaria acessar qualquer secretaria';
    public const UNIDADE_SECRETARIA_CREATE = 'Criar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_CREATE_ANY = 'Criar Unidade de qualquer Secretaria';
    public const UNIDADE_SECRETARIA_VIEW = 'Visualizar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_EDIT = 'Editar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_TOGGLE_ATIVO = 'Ativar ou Desativar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_DELETE = 'Deletar Unidades da Secretaria';
    public const UNIDADE_SECRETARIA_GERAR_QRCODE = 'Gerar QrCode da Unidades da Secretaria';


    public const UNIDADE_SECRETARIA = [
        self::UNIDADE_SECRETARIA_LIST,
        self::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA,
        self::UNIDADE_SECRETARIA_CREATE,
        self::UNIDADE_SECRETARIA_CREATE_ANY,
        self::UNIDADE_SECRETARIA_VIEW,
        self::UNIDADE_SECRETARIA_EDIT,
        self::UNIDADE_SECRETARIA_TOGGLE_ATIVO,
        self::UNIDADE_SECRETARIA_DELETE,
        self::UNIDADE_SECRETARIA_GERAR_QRCODE,
    ];

    // Tipos de avaliacao
    public const GERENCIAR_TIPOS_AVALIACAO_LIST = 'Listar Tipos de Avaliação';
    public const GERENCIAR_TIPO_AVALIACAO_SECRETARIA_ACCESS_ANY_SECRETARIA = 'Tipos de Avaliação acessar qualquer secretaria';
    public const GERENCIAR_TIPOS_AVALIACAO_VIEW = 'Visualizar Tipos de Avaliação';
    public const GERENCIAR_TIPOS_AVALIACAO_CREATE_ANY_SECRETARIA = 'Criar Tipos de Avaliação para qualquer secretaria';
    public const GERENCIAR_TIPOS_AVALIACAO_CREATE = 'Criar Tipos de Avaliação';
    public const GERENCIAR_TIPOS_AVALIACAO_EDIT = 'Editar Tipos de Avaliação';
    public const GERENCIAR_TIPOS_AVALIACAO_DELETE = 'Deletar Tipos de Avaliação';
    public const GERENCIAR_TIPOS_AVALIACAO_ACTIVE_TOGGLE = 'Ativar ou Desativar Tipos de Avaliação';

    public const GERENCIAR_TIPOS_AVALIACAO = [
        self::GERENCIAR_TIPOS_AVALIACAO_LIST,
        self::GERENCIAR_TIPO_AVALIACAO_SECRETARIA_ACCESS_ANY_SECRETARIA,
        self::GERENCIAR_TIPOS_AVALIACAO_VIEW,
        self::GERENCIAR_TIPOS_AVALIACAO_CREATE,
        self::GERENCIAR_TIPOS_AVALIACAO_CREATE_ANY_SECRETARIA,
        self::GERENCIAR_TIPOS_AVALIACAO_EDIT,
        self::GERENCIAR_TIPOS_AVALIACAO_DELETE,
        self::GERENCIAR_TIPOS_AVALIACAO_ACTIVE_TOGGLE,
    ];

    //Setores
    public const SETOR_CREATE = 'Criar Setor da Unidade';
    public const SETOR_VIEW = 'Visualizar Setor da Unidade';
    public const SETOR_EDIT = 'Editar Setor da Unidade';
    public const SETOR_DELETE = 'Deletar Setor da Unidade';
    public const SETOR_TOGGLE_ATIVO = 'Ativar ou Desativar Setor da Unidade';
    public const SETOR_GERAR_QRCODE = 'Gerar Qr Code do Setor';

    public const SETOR = [
        self::SETOR_CREATE,
        self::SETOR_VIEW,
        self::SETOR_EDIT,
        self::SETOR_DELETE,
        self::SETOR_TOGGLE_ATIVO,
        self::SETOR_GERAR_QRCODE,
    ];

    //Comentarios das avaliações
    public const GERENCIAR_COMENTARIOS_AVALIACOES_LIST = 'Listar avaliações';
    public const GERENCIAR_COMENTARIOS_AVALIACOES_VIEW = 'Visualizar comentários das avaliações';
    public const GERENCIAR_COMENTARIOS_AVALIACOES_EXPORT = 'Exportar comentários das avaliações';

    //Comentarios das avaliações
    public const GERENCIAR_AVALIACOES_LIST = 'Listar todas avaliações';
    public const GERENCIAR_AVALIACOES_VIEW = 'Visualizar avaliações';


    // Quantidade de avaliações
    public const GERENCIAR_QUANTIDADE_AVALIACOES_LIST = 'Listar quantidade avaliações';
    public const GERENCIAR_QUANTIDADE_AVALIACOES_VIEW = 'Visualizar quantidade das avaliações';
    public const GERENCIAR_QUANTIDADE_AVALIACOES_EXPORT = 'Exportar quantidade das avaliações';

    public const COMENTARIOS_AVALIACOES = [
        self::GERENCIAR_COMENTARIOS_AVALIACOES_LIST,
        self::GERENCIAR_COMENTARIOS_AVALIACOES_VIEW,
        self::GERENCIAR_COMENTARIOS_AVALIACOES_EXPORT,
    ];
    public const TODAS_AVALIACOES = [
        self::GERENCIAR_AVALIACOES_LIST,
        self::GERENCIAR_AVALIACOES_VIEW,
    ];

    public const QUANTIDADE_AVALIACOES = [
        self::GERENCIAR_QUANTIDADE_AVALIACOES_LIST,
        self::GERENCIAR_QUANTIDADE_AVALIACOES_VIEW,
        self::GERENCIAR_QUANTIDADE_AVALIACOES_EXPORT,
    ];

    public const PERMISSIONS = [
        //perfil
        'Gerenciar Perfil de Usuário' => self::PERFIL_USUARIO,
        //Manifestaçoes
        // 'Manifestações' => self::MANIFESTACOES,
        //Gerenciar
        'Gerenciamento de Usuários' => self::GERENCIAR_USUARIOS,
        'Gerenciamento de Secretarias' => self::GERENCIAR_SECRETARIAS,
        //Configuraçoes
        'Configuração de Tipos de Usuário' => self::GERENCIAR_TIPOS_USUARIOS,
        'Configuração de Tipos de Avaliação' => self::GERENCIAR_TIPOS_AVALIACAO,
        // 'Configuração de Tipos de Manifestação' => self::GERENCIAR_TIPOS_MANIFESTACAO,
        // 'Configuração de Estados do Processo' => self::GERENCIAR_ESTADOS_PROCESSO,
        // 'Configuração de Motivações' => self::GERENCIAR_MOTIVACOES,
        // 'Configuração de Situações' => self::GERENCIAR_SITUACOES,
        // 'Configuração de FAQs' => self::GERENCIAR_FAQS,
        //avaliacao
        'Relatórios Modulo Avaliação' => self::RELATORIO_AVALIACOES,
        'Modulo Avaliação' => self::UNIDADE_SECRETARIA,
        'Setores das Unidades' => self::SETOR,
        'Comentários das Avaliações' => self::COMENTARIOS_AVALIACOES,
        'Quantidade das Avaliações' => self::QUANTIDADE_AVALIACOES,
        'TODAS_AVALIACOES' => self::TODAS_AVALIACOES,
    ];

    public const SUPER_MIGRAR_DADOS = 'Super usuário migrar dados';

    public const SUPERPERMISSIONS = [
        'Gerenciamento de Banco de Dados' => self::SUPER_MIGRAR_DADOS,
    ];
}
