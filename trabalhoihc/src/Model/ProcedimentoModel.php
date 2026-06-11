<?php
// src/Model/ProcedimentoModel.php

class ProcedimentoModel
{
    public function __construct(private PDO $pdo) {}

    public function listarTodos(): array
    {
        return $this->pdo->query("SELECT * FROM procedimentos ORDER BY nome ASC")->fetchAll();
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM procedimentos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function inserir(string $nome, string $categoria, ?float $valorBase, int $tipo): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO procedimentos (nome, categoria, valor_base, tipo) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$nome, $categoria, $valorBase, $tipo]);
    }

    public function excluir(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM procedimentos WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function estaEmUso(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM atendimento_procedimentos WHERE id_procedimento = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    // Retorna procedimentos com categoria e valor para o formulário de atendimento
    public function listarParaAtendimento(): array
    {
        return $this->pdo->query(
            "SELECT id, nome, categoria, valor_base, tipo FROM procedimentos ORDER BY nome ASC"
        )->fetchAll();
    }

    // Relatório: quantidade executada e valor bruto por procedimento
    public function relatorio(string $dataInicio, string $dataFim): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                p.nome AS procedimento_nome,
                SUM(ap.quantidade) AS quantidade_executada,
                SUM(ap.valor_procedimento) AS valor_bruto_total
            FROM atendimento_procedimentos ap
            JOIN atendimentos a  ON a.id  = ap.id_atendimento
            JOIN procedimentos p ON p.id  = ap.id_procedimento
            WHERE a.data_atendimento BETWEEN ? AND ?
              AND ap.status_execucao = 'feito'
              AND a.status_pagamento  = 'pago'
            GROUP BY p.id, p.nome
            ORDER BY quantidade_executada DESC, valor_bruto_total DESC
        ");
        $stmt->execute([$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59']);
        return $stmt->fetchAll();
    }

    public function totalProcedimentosExecutados(string $dataInicio, string $dataFim): int
    {
        $stmt = $this->pdo->prepare("
            SELECT SUM(ap.quantidade)
            FROM atendimento_procedimentos ap
            JOIN atendimentos a ON a.id = ap.id_atendimento
            WHERE a.data_atendimento BETWEEN ? AND ?
              AND ap.status_execucao = 'feito'
              AND a.status_pagamento  = 'pago'
        ");
        $stmt->execute([$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59']);
        return (int) ($stmt->fetchColumn() ?? 0);
    }
}
