<?php
// src/Controller/PainelController.php

class PainelController
{
    public function __construct(private PDO $pdo) {}

    public function index(): void
    {
        date_default_timezone_set('America/Sao_Paulo');

        $atendimentoModel = new AtendimentoModel($this->pdo);
        $despesaModel     = new DespesaModel($this->pdo);

        // Mês selecionado ou atual — nomes iguais ao que a view espera
        $mes_selecionado = isset($_GET['mes']) ? $_GET['mes'] : date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $mes_selecionado)) {
            $mes_selecionado = date('Y-m');
        }

        $dataInicio    = date('Y-m-01', strtotime($mes_selecionado));
        $dataFim       = date('Y-m-t',  strtotime($mes_selecionado));
        $mes_anterior  = date('Y-m', strtotime($dataInicio . ' -1 month'));
        $mes_proximo   = date('Y-m', strtotime($dataInicio . ' +1 month'));

        $faturamentoBruto = $atendimentoModel->faturamentoBruto($dataInicio . ' 00:00:00', $dataFim . ' 23:59:59');
        $lucroLiquido     = $atendimentoModel->lucroLiquido($dataInicio . ' 00:00:00', $dataFim . ' 23:59:59');
        $totalDespesas    = $despesaModel->totalNoPeriodo($dataInicio, $dataFim);

        // Paginação / busca
        $busca          = trim($_GET['busca'] ?? '');
        $pagina         = max(1, (int)($_GET['pagina'] ?? 1));
        $itensPorPagina = 10;
        $offset         = ($pagina - 1) * $itensPorPagina;

        $totalRegistros      = $atendimentoModel->contarPagos($busca);
        $totalPaginas        = ceil($totalRegistros / $itensPorPagina);
        $ultimosAtendimentos = $atendimentoModel->listarPagos($busca, $itensPorPagina, $offset);

        // Nome do mês em português — com fallback se extensão intl não estiver ativa
        if (class_exists('IntlDateFormatter')) {
            $formatter = new IntlDateFormatter(
                'pt_BR',
                IntlDateFormatter::FULL,
                IntlDateFormatter::NONE,
                'America/Sao_Paulo',
                IntlDateFormatter::GREGORIAN,
                "MMMM 'de' yyyy"
            );
            $mesAtual = $formatter->format(strtotime($dataInicio));
        } else {
            $meses = ['janeiro','fevereiro','março','abril','maio','junho',
                      'julho','agosto','setembro','outubro','novembro','dezembro'];
            $m = (int)date('n', strtotime($dataInicio)) - 1;
            $mesAtual = $meses[$m] . ' de ' . date('Y', strtotime($dataInicio));
        }

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/painel/index.php';
        require ROOT . '/src/View/layout/footer.php';
    }
}
