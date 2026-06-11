<?php
// src/View/layout/header.php
// Interface idêntica ao original — apenas as URLs foram adaptadas para o Front Controller

function isActive(array|string $rotas): bool {
    $rota_atual = $_GET['rota'] ?? 'painel';
    if (!is_array($rotas)) $rotas = [$rotas];
    foreach ($rotas as $r) {
        if (str_starts_with($rota_atual, $r)) return true;
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Prev Dentistas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <a href="<?= BASE_URL ?>?rota=painel" style="text-decoration:none; color:inherit;">🦷 Prev Dentistas</a>
        </div>

        <?php if (isset($_SESSION['usuario_id'])): ?>
        <div class="menu-toggle" id="mobile-menu">
            <span></span><span></span><span></span>
        </div>
        <?php endif; ?>

        <nav class="menu" id="navbar-menu">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="<?= BASE_URL ?>?rota=painel" class="<?= isActive('painel') ? 'active' : '' ?>">Dashboard</a>

                <div class="dropdown">
                    <a href="javascript:void(0)" class="<?= isActive(['atendimentos']) ? 'active' : '' ?>">
                        Novo Atendimento <small>▾</small>
                    </a>
                    <div class="dropdown-content">
                        <a href="<?= BASE_URL ?>?rota=atendimentos.novo">Lançar/Executar Procedimento</a>
                        <a href="<?= BASE_URL ?>?rota=atendimentos.pagamento">Confirmar Pagamento</a>
                    </div>
                </div>

                <?php if (is_admin() || is_dentista() || is_recepcionista()): ?>
                <div class="dropdown">
                    <a href="javascript:void(0)" class="<?= isActive(['procedimentos','despesas','usuarios','pacientes']) ? 'active' : '' ?>">
                        Cadastros <small>▾</small>
                    </a>
                    <div class="dropdown-content">
                        <a href="<?= BASE_URL ?>?rota=pacientes">Pacientes</a>
                        <?php if (is_admin()): ?>
                            <a href="<?= BASE_URL ?>?rota=procedimentos">Procedimentos</a>
                            <a href="<?= BASE_URL ?>?rota=despesas">Despesas</a>
                            <a href="<?= BASE_URL ?>?rota=usuarios">Usuários</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="dropdown">
                    <a href="javascript:void(0)" class="<?= isActive('relatorios') ? 'active' : '' ?>">
                        Relatórios <small>▾</small>
                    </a>
                    <div class="dropdown-content">
                        <a href="<?= BASE_URL ?>?rota=relatorios.diario">Diário</a>
                        <?php if (is_admin() || is_dentista()): ?>
                            <a href="<?= BASE_URL ?>?rota=relatorios.dentistas">Por Dentista</a>
                            <a href="<?= BASE_URL ?>?rota=relatorios.paciente">Por Paciente</a>
                        <?php endif; ?>
                        <?php if (is_admin()): ?>
                            <a href="<?= BASE_URL ?>?rota=relatorios">Financeiro Geral</a>
                            <a href="<?= BASE_URL ?>?rota=relatorios.procedimentos">Por Procedimentos</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?= BASE_URL ?>?rota=configuracoes" class="<?= isActive('configuracoes') ? 'active' : '' ?>">Configuração</a>
            <?php endif; ?>
        </nav>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div class="user-menu">
                <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
                <a href="<?= BASE_URL ?>?rota=logout" class="btn btn-secondary">Sair</a>
            </div>
        <?php endif; ?>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuToggle = document.getElementById('mobile-menu');
            const navMenu    = document.getElementById('navbar-menu');

            if (menuToggle && navMenu) {
                menuToggle.addEventListener('click', function () {
                    navMenu.classList.toggle('active');
                });
            }

            document.querySelectorAll('.dropdown').forEach(function (dropdown) {
                dropdown.querySelector('a').addEventListener('click', function (e) {
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        const content   = dropdown.querySelector('.dropdown-content');
                        const isVisible = content.style.display === 'block';
                        document.querySelectorAll('.dropdown-content').forEach(c => c.style.display = 'none');
                        content.style.display = isVisible ? 'none' : 'block';
                    }
                });
            });
        });
    </script>

    <main class="container">
    <script>
        /* BASE_URL disponibilizada para scripts de view */
        window.__BASE_URL = '<?= BASE_URL ?>';
    </script>

