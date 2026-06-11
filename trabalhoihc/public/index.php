<?php
// public/index.php  —  Front Controller / Roteador Central
error_reporting(0);
ini_set('display_errors', '0');
ob_start(); // captura qualquer output acidental (warnings, notices)

define('ROOT', dirname(__DIR__));

require_once ROOT . '/config/app.php';
require_once ROOT . '/config/session.php';
require_once ROOT . '/config/controle_acesso.php';

// Autoload simples dos Controllers e Models
spl_autoload_register(function (string $class): void {
    $dirs = [
        ROOT . '/src/Controller/',
        ROOT . '/src/Model/',
    ];
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ──────────────────────────────────────────────────────────────
// Tabela de rotas: rota => [Controller, método, perfis_permitidos]
// perfis_permitidos = [] significa que só precisa estar logado
// ──────────────────────────────────────────────────────────────
$rotas = [
    // Auth (públicas)
    'login'                    => ['AuthController',        'exibirLogin',              null],
    'login.post'               => ['AuthController',        'processar',                null],
    'logout'                   => ['AuthController',        'logout',                   null],

    // Painel
    'painel'                   => ['PainelController',      'index',                    []],

    // Pacientes
    'pacientes'                => ['PacienteController',    'listar',                   []],
    'pacientes.criar'          => ['PacienteController',    'criar',                    []],
    'pacientes.salvar'         => ['PacienteController',    'salvar',                   []],
    'pacientes.editar'         => ['PacienteController',    'editar',                   []],
    'pacientes.excluir'        => ['PacienteController',    'excluir',                  []],
    'pacientes.buscar'         => ['PacienteController',    'buscar',                   null],        // AJAX público

    // Atendimentos
    'atendimentos.novo'        => ['AtendimentoController', 'novo',                     []],
    'atendimentos.salvar'      => ['AtendimentoController', 'salvar',                   []],
    'atendimentos.pagamento'   => ['AtendimentoController', 'confirmarPagamento',        []],
    'atendimentos.pagar'       => ['AtendimentoController', 'processarPagamento',        []],
    'atendimentos.historico'   => ['AtendimentoController', 'historicoAjax',            null],        // AJAX
    'atendimentos.pendentes'   => ['AtendimentoController', 'procedimentosPendentes',   null],        // AJAX
    'atendimentos.remProc'     => ['AtendimentoController', 'removerProcedimento',       []],          // AJAX
    'atendimentos.remAnexo'    => ['AtendimentoController', 'removerAnexo',             []],          // AJAX
    'atendimentos.salvarArq'   => ['AtendimentoController', 'salvarArquivo',            []],
    'atendimentos.verPagPend'  => ['AtendimentoController', 'verificarPagamentoPendente',null],       // AJAX
    'atendimentos.detalhes'    => ['AtendimentoController', 'detalhes',                 []],          // AJAX

    // Procedimentos
    'procedimentos'            => ['ProcedimentoController','listar',                   ['proprietario']],
    'procedimentos.salvar'     => ['ProcedimentoController','salvar',                   ['proprietario']],
    'procedimentos.excluir'    => ['ProcedimentoController','excluir',                  ['proprietario']],

    // Despesas
    'despesas'                 => ['DespesaController',     'listar',                   ['proprietario']],
    'despesas.salvar'          => ['DespesaController',     'salvar',                   ['proprietario']],
    'despesas.excluir'         => ['DespesaController',     'excluir',                  ['proprietario']],

    // Usuários
    'usuarios'                 => ['UsuarioController',     'listar',                   ['proprietario']],
    'usuarios.salvar'          => ['UsuarioController',     'salvar',                   ['proprietario']],
    'usuarios.editar'          => ['UsuarioController',     'editar',                   ['proprietario']],
    'usuarios.excluir'         => ['UsuarioController',     'excluir',                  ['proprietario']],

    // Configurações (próprio usuário)
    'configuracoes'            => ['UsuarioController',     'configuracoes',            []],
    'configuracoes.salvar'     => ['UsuarioController',     'salvarConfiguracoes',      []],

    // Recibo
    'recibo'                   => ['ReciboController',      'gerar',                    []],

    // Relatórios
    'relatorios'               => ['RelatorioController',   'geral',                    ['proprietario']],
    'relatorios.diario'        => ['RelatorioController',   'diario',                   []],
    'relatorios.dentistas'     => ['RelatorioController',   'dentistas',                ['proprietario','dentista']],
    'relatorios.paciente'      => ['RelatorioController',   'paciente',                 ['proprietario','dentista']],
    'relatorios.procedimentos' => ['RelatorioController',   'procedimentos',            ['proprietario']],
];

// ──────────────────────────────────────────────────────────────
// Resolve a rota solicitada
// ──────────────────────────────────────────────────────────────
$rota = $_GET['rota'] ?? 'login';

// Rotas POST recebem sufixo ".post" automaticamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($rotas[$rota . '.post'])) {
    $rota = $rota . '.post';
}

if (!isset($rotas[$rota])) {
    // Rota não encontrada: redireciona ao painel ou login
    $destino = isset($_SESSION['usuario_id']) ? '?rota=painel' : '?rota=login';
    header("Location: " . BASE_URL . $destino);
    exit;
}

[$controllerClass, $metodo, $perfis] = $rotas[$rota];

// ──────────────────────────────────────────────────────────────
// Controle de acesso
// ──────────────────────────────────────────────────────────────
if ($perfis !== null) {
    // Rota protegida: exige sessão
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: " . BASE_URL . "?rota=login");
        exit;
    }
    // Se a lista de perfis não está vazia, verifica o perfil
    if (!empty($perfis) && !has_access($perfis)) {
        header("Location: " . BASE_URL . "?rota=painel");
        exit;
    }
}

// ──────────────────────────────────────────────────────────────
// Instancia o controller e executa o método
// ──────────────────────────────────────────────────────────────
require_once ROOT . '/config/database.php';

$controller = new $controllerClass($pdo);
$controller->$metodo();
