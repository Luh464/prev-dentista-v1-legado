<?php
// src/Model/RelatorioModel.php
// Consultas analíticas para fluxo de caixa e balanços gerais

class RelatorioModel
{
    public function __construct(private PDO $pdo) {}

    public function faturamentoBruto(string $inicio, string $fim): float
    {
        $stmt = $this->pdo->prepare("
            SELECT SUM(ap.valor_procedimento)
            FROM atendimentos a
            JOIN atendimento_procedimentos ap ON a.id = ap.id_atendimento
            WHERE a.data_atendimento BETWEEN ? AND ?
              AND a.status_pagamento IN ('pago','pendente')
              AND ap.status_execucao  IN ('feito','finalizado','pendente')
        ");
        $stmt->execute([$inicio . ' 00:00:00', $fim . ' 23:59:59']);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    public function liquidoClinica(string $inicio, string $fim): float
    {
        $stmt = $this->pdo->prepare("
            SELECT SUM(valor_liquido_clinica)
            FROM atendimentos
            WHERE data_atendimento BETWEEN ? AND ?
              AND status_pagamento = 'pago'
        ");
        $stmt->execute([$inicio . ' 00:00:00', $fim . ' 23:59:59']);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    public function totalDespesas(string $inicio, string $fim): float
    {
        $stmt = $this->pdo->prepare(
            "SELECT SUM(valor) FROM despesas WHERE data_despesa BETWEEN ? AND ?"
        );
        $stmt->execute([$inicio, $fim]);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    public function listarDespesasNoPeriodo(string $inicio, string $fim, int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM despesas
            WHERE data_despesa BETWEEN ? AND ?
            ORDER BY data_despesa DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $inicio,  PDO::PARAM_STR);
        $stmt->bindValue(2, $fim,     PDO::PARAM_STR);
        $stmt->bindValue(3, $limit,   PDO::PARAM_INT);
        $stmt->bindValue(4, $offset,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contarDespesasNoPeriodo(string $inicio, string $fim): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM despesas WHERE data_despesa BETWEEN ? AND ?"
        );
        $stmt->execute([$inicio, $fim]);
        return (int) $stmt->fetchColumn();
    }
}
