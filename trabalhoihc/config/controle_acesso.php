<?php
// config/controle_acesso.php
require_once __DIR__ . '/session.php';

function is_admin(): bool {
    return isset($_SESSION['usuario_perfil']) && $_SESSION['usuario_perfil'] === 'proprietario';
}

function is_dentista(): bool {
    return isset($_SESSION['usuario_perfil']) && $_SESSION['usuario_perfil'] === 'dentista';
}

function is_recepcionista(): bool {
    return isset($_SESSION['usuario_perfil']) && $_SESSION['usuario_perfil'] === 'recepcionista';
}

function has_access(array|string $perfis_permitidos): bool {
    if (!is_array($perfis_permitidos)) {
        $perfis_permitidos = [$perfis_permitidos];
    }
    if (!isset($_SESSION['usuario_perfil'])) {
        return false;
    }
    return in_array($_SESSION['usuario_perfil'], $perfis_permitidos);
}
