<?php
// src/Controller/RelatorioController.php

class RelatorioController
{
    private AtendimentoModel  $atModel;
    private DespesaModel      $despModel;
    private ProcedimentoModel $procModel;
    private PacienteModel     $pacModel;

    public function __construct(private PDO $pdo)
    {
        $this->atModel   = new AtendimentoModel($pdo);
        $this->despModel = new DespesaModel($pdo);
        $this->procModel = new ProcedimentoModel($pdo);
        $this->pacModel  = new PacienteModel($pdo);
    }

    /** Relatório Financeiro Geral */
    public function geral(): void
    {
        $data_inicio = $_GET['inicio'] ?? date('Y-m-01');
        $data_fim    = $_GET['fim']    ?? date('Y-m-t');

        $itensPorPagina = 10;
        $pagina_at = max(1, (int)($_GET['pagina_at'] ?? 1));
        $pagina_de = max(1, (int)($_GET['pagina_de'] ?? 1));
        $offsetAt  = ($pagina_at - 1) * $itensPorPagina;
        $offsetDe  = ($pagina_de - 1) * $itensPorPagina;

        $relModel = new RelatorioModel($this->pdo);

        $bruto    = $relModel->faturamentoBruto($data_inicio, $data_fim);
        $liquido  = $relModel->liquidoClinica($data_inicio, $data_fim);
        $despesas = $relModel->totalDespesas($data_inicio, $data_fim);

        $totalAt   = $this->atModel->contarAtendimentosPagos($data_inicio, $data_fim);
        $paginasAt = ceil($totalAt / $itensPorPagina);
        $atendimentos = $this->atModel->listarAtendimentosPagos($data_inicio, $data_fim, $itensPorPagina, $offsetAt);

        $totalDe   = $relModel->contarDespesasNoPeriodo($data_inicio, $data_fim);
        $paginasDe = ceil($totalDe / $itensPorPagina);
        $listaDespesas = $relModel->listarDespesasNoPeriodo($data_inicio, $data_fim, $itensPorPagina, $offsetDe);

        $financas = ['bruto' => $bruto, 'liquido' => $liquido];

        // Dados para os gráficos Chart.js — evolução diária no período
        $labels          = [];
        $faturamentoData = [];
        $despesaData     = [];
        $lucroLiquidoData = [];
        $pagamentoLabels = ['Dinheiro', 'PIX', 'Débito', 'Crédito'];
        $pagamentoData   = [0, 0, 0, 0];

        try {
            // Evolução diária (uma query por dia — eficiente para períodos até 90 dias)
            $d1 = new DateTime($data_inicio);
            $d2 = new DateTime($data_fim);
            $diff = $d1->diff($d2)->days;

            if ($diff <= 90) {
                $cur = clone $d1;
                while ($cur <= $d2) {
                    $d = $cur->format('Y-m-d');
                    $labels[]           = $cur->format('d/m');
                    $faturamentoData[]  = (float)($relModel->faturamentoBruto($d, $d) ?: 0);
                    $despesaData[]      = (float)($relModel->totalDespesas($d, $d) ?: 0);
                    $lucroLiquidoData[] = (float)($relModel->liquidoClinica($d, $d) ?: 0);
                    $cur->modify('+1 day');
                }
            } else {
                // Período longo: mostrar só os totais
                $labels           = ['Total do período'];
                $faturamentoData  = [$bruto];
                $despesaData      = [$despesas];
                $lucroLiquidoData = [$liquido];
            }

            // Formas de pagamento
            $stmtPag = $this->pdo->prepare("
                SELECT ap.forma_pagamento, SUM(ap.valor) as total
                FROM atendimento_pagamentos ap
                JOIN atendimentos a ON ap.id_atendimento = a.id
                WHERE DATE(a.data_atendimento) BETWEEN ? AND ?
                  AND a.status_pagamento = 'pago'
                GROUP BY ap.forma_pagamento
            ");
            $stmtPag->execute([$data_inicio, $data_fim]);
            $pagRows = $stmtPag->fetchAll();
            $pagMap  = array_column($pagRows, 'total', 'forma_pagamento');
            $pagamentoData = [
                (float)($pagMap['dinheiro'] ?? 0),
                (float)($pagMap['pix']      ?? 0),
                (float)($pagMap['debito']   ?? 0),
                (float)($pagMap['credito']  ?? 0),
            ];
        } catch (Exception $e) {
            $labels = $faturamentoData = $despesaData = $lucroLiquidoData = [];
            $pagamentoData = [0, 0, 0, 0];
        }

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/relatorios/geral.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    /** Relatório Diário */
    public function diario(): void
    {
        $data_selecionada = $_GET['data'] ?? date('Y-m-d');
        $dataObj   = new DateTime($data_selecionada);
        $data_anterior = (clone $dataObj)->modify('-1 day')->format('Y-m-d');
        $data_posterior = (clone $dataObj)->modify('+1 day')->format('Y-m-d');

        $faturamento_bruto   = $this->atModel->faturamentoBruto($data_selecionada . ' 00:00:00', $data_selecionada . ' 23:59:59');
        $total_taxas         = $this->atModel->totalTaxas($data_selecionada);
        $total_custo_auxiliar = $this->atModel->totalCustoAuxiliar($data_selecionada);

        $dentistaId = is_dentista() && !is_admin() ? (int)$_SESSION['usuario_id'] : null;
        $pagamento_dentistas = $this->atModel->pagamentoPorDentistaNoDia($data_selecionada, $dentistaId);
        $total_comissoes     = array_sum(array_column($pagamento_dentistas, 'total_comissao'));

        $despesas_dia  = $this->despModel->listarNoDia($data_selecionada);
        $total_despesas = array_sum(array_column($despesas_dia, 'valor'));
        $lucro_liquido  = $faturamento_bruto - $total_taxas - $total_comissoes - $total_despesas - $total_custo_auxiliar;

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/relatorios/diario.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    /** Relatório por Dentista */
    public function dentistas(): void
    {
        $data_inicio = $_GET['inicio'] ?? date('Y-m-01');
        $data_fim    = $_GET['fim']    ?? date('Y-m-t');

        if (is_dentista() && !is_admin()) {
            $dentistaId = $_SESSION['usuario_id'];
        } else {
            $dentistaId = $_GET['dentista_id'] ?? 'todos';
        }

        $dentistas           = is_admin() ? (new UsuarioModel($this->pdo))->listarDentistas() : [];
        $dentista_id         = $dentistaId;
        $mes                 = $data_inicio; // compatibilidade com a view
        $relatorio_dentistas = $this->atModel->relatorioPorDentista($data_inicio, $data_fim, $dentistaId);

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/relatorios/dentistas.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    /** Relatório por Paciente */
    public function paciente(): void
    {
        $paciente_nome = trim($_GET['paciente_nome'] ?? '');
        $paciente      = null;
        $procedimentos = [];
        $totalPaginas  = 0;
        $dente_status_color      = [];
        $procedimentos_agrupados = [];
        $procedimentos_todos     = [];
        $pagina         = max(1, (int)($_GET['pagina'] ?? 1));
        $itensPorPagina = 20;
        $offset         = ($pagina - 1) * $itensPorPagina;

        if (!empty($paciente_nome)) {
            $stmt = $this->pdo->prepare("SELECT * FROM pacientes WHERE LOWER(nome) LIKE LOWER(?)");
            $stmt->execute(['%' . $paciente_nome . '%']);
            $paciente = $stmt->fetch();

            if ($paciente) {
                $totalRegistros = $this->atModel->contarProcedimentosDoPaciente($paciente['id']);
                $totalPaginas   = ceil($totalRegistros / $itensPorPagina);
                $procedimentos  = $this->atModel->procedimentosDoPacientePaginado($paciente['id'], $itensPorPagina, $offset);

                // Agrupar por local (dente) e lista geral para o odontograma
                foreach ($procedimentos as $proc) {
                    $local = $proc['local'] ?? 'Geral';
                    $procedimentos_agrupados[$local][] = $proc;
                    $procedimentos_todos[] = $proc;
                }

                // Cores do odontograma
                $todos = $this->atModel->statusDentesDoOdontograma($paciente['id']);
                $raw   = [];
                foreach ($todos as $p) {
                    $local = $p['local'];
                    if (!isset($raw[$local])) $raw[$local] = ['pendente' => false, 'feito' => false];
                    if ($p['status_execucao'] === 'feito') $raw[$local]['feito'] = true;
                    if (in_array($p['status_execucao'], ['pendente', 'finalizado'])) $raw[$local]['pendente'] = true;
                }
                foreach ($raw as $local => $s) {
                    $dente_status_color[$local] = ($s['feito'] && $s['pendente']) ? 'yellow'
                        : ($s['feito'] ? 'green' : ($s['pendente'] ? 'red' : 'none'));
                }
            }
        }

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/relatorios/paciente.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    /** Relatório por Procedimentos */
    public function procedimentos(): void
    {
        $data_inicio = $_GET['inicio'] ?? date('Y-m-01');
        $data_fim    = $_GET['fim']    ?? date('Y-m-t');

        $totalProcedimentos      = $this->procModel->totalProcedimentosExecutados($data_inicio, $data_fim);
        $procedimentos_relatorio = $this->procModel->relatorio($data_inicio, $data_fim);

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/relatorios/procedimentos.php';
        require ROOT . '/src/View/layout/footer.php';
    }
}
