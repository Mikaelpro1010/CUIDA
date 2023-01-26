<?php

namespace App\Constants;

class PermissionRolesConstants
{
    // SuperAdmin
    public const SUPERADM = Permission::PERMISSIONS;

    // Admin
    public const ADMIN = [
        Permission::PERFIL_USUARIO,
    ];

    // Gerente
    public const GERENTE = [
        Permission::PERFIL_USUARIO,
    ];

    // Ouvidor
    public const OUVIDOR = [
        Permission::PERFIL_USUARIO,
        Permission::GERENCIAR_TIPOS_MANIFESTACAO,
        Permission::GERENCIAR_ESTADOS_PROCESSO,
        Permission::GERENCIAR_MOTIVACOES,
        Permission::GERENCIAR_SITUACOES,
        Permission::GERENCIAR_FAQS,
        Permission::MANIFESTACOES,
    ];

    // Avaliador
    public const AVALIADOR = [
        Permission::PERFIL_USUARIO,
        // Modulo Avaliação
        Permission::UNIDADE_SECRETARIA_LIST,
        // Permission::UNIDADE_SECRETARIA_ACCESS_ANY_SECRETARIA,
        Permission::UNIDADE_SECRETARIA_CREATE,
        // Permission::UNIDADE_SECRETARIA_CREATE_ANY,
        Permission::UNIDADE_SECRETARIA_VIEW,
        Permission::UNIDADE_SECRETARIA_EDIT,
        Permission::UNIDADE_SECRETARIA_TOGGLE_ATIVO,
        Permission::UNIDADE_SECRETARIA_DELETE,
        // Relatórios Modulo Avaliação,
        Permission::RELATORIO_AVALIACOES_GERAL_VIEW,
        Permission::RELATORIO_AVALIACOES_SECRETARIA_VIEW,
        Permission::RELATORIO_AVALIACOES_UNIDADE_VIEW,
    ];
}
