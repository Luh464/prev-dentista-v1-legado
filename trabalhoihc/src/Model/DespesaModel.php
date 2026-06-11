<?php
// src/Model/DespesaModel.php

class DespesaModel
{
    public function __construct(private PDO $pdo) {}

    public function listarTodas(): array
    {
        return $this->pdo->query(
            "SELECT * FROM despesas ORDER BY data_despesa DESC"
        )->fetchAll();
    }

    public function totalNoPeriodo(string $dataInicio, string $dataFim): float
    {
        $stmt = $this->pdo->prepare(
            "SELECT SUM(valor) FROM despesas WHERE data_despesa BETWEEN ? AND ?"
        );
        $stmt->execute([$dataInicio, $dataFim]);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    public function listarNoDia(string $data): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM despesas WHERE data_despesa = ? ORDER BY descricao"
        );
        $stmt->execute([$data]);
        return $stmt->fetchAll();
    }

    public function inserir(string $descricao, float $valor, string $tipo, string $data): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO despesas (descricao, valor, tipo, data_despesa) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$descricao, $valor, $tipo, $data]);
    }

    public function excluir(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM despesas WHERE id = ?");
        $stmt->execute([$id]);
    }
}
