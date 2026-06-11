<?php
// src/Model/PacienteModel.php

class PacienteModel
{
    public function __construct(private PDO $pdo) {}

    public function listar(string $busca, int $limit, int $offset): array
    {
        $sql = "SELECT * FROM pacientes WHERE 1=1";
        $params = [];

        if (!empty($busca)) {
            $sql .= " AND (nome LIKE :busca OR cpf LIKE :busca)";
            $params[':busca'] = "%$busca%";
        }

        $sql .= " ORDER BY nome ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        if (!empty($busca)) {
            $stmt->bindValue(':busca', "%$busca%", PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contar(string $busca): int
    {
        $sql    = "SELECT COUNT(id) FROM pacientes WHERE 1=1";
        $params = [];

        if (!empty($busca)) {
            $sql .= " AND (nome LIKE :busca OR cpf LIKE :busca)";
            $params[':busca'] = "%$busca%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pacientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function buscarPorNomeOuCpf(string $term, int $limit = 15): array
    {
        $like = '%' . $term . '%';
        $stmt = $this->pdo->prepare(
            "SELECT id, nome, cpf, telefone, email
             FROM pacientes
             WHERE nome LIKE ? OR cpf LIKE ?
             ORDER BY nome ASC
             LIMIT ?"
        );
        $stmt->bindValue(1, $like,  PDO::PARAM_STR);
        $stmt->bindValue(2, $like,  PDO::PARAM_STR);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function inserir(array $data): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO pacientes (nome, cpf, data_nascimento, email, telefone, cep, endereco, numero, bairro, cidade, estado)
             VALUES (:nome, :cpf, :data_nascimento, :email, :telefone, :cep, :endereco, :numero, :bairro, :cidade, :estado)"
        );
        $stmt->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function inserirSimplesOuObterPorNome(string $nome): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO pacientes (nome) VALUES (?)");
        $stmt->execute([$nome]);
        return (int) $this->pdo->lastInsertId();
    }

    public function atualizar(int $id, array $data): void
    {
        $data[':id'] = $id;
        $stmt = $this->pdo->prepare(
            "UPDATE pacientes SET
                nome = :nome, cpf = :cpf, data_nascimento = :data_nascimento,
                email = :email, telefone = :telefone, cep = :cep,
                endereco = :endereco, numero = :numero, bairro = :bairro,
                cidade = :cidade, estado = :estado
             WHERE id = :id"
        );
        $stmt->execute($data);
    }

    public function excluir(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM pacientes WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function possuiAtendimentos(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM atendimentos WHERE paciente_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function buscarNomePorId(int $id): string
    {
        $stmt = $this->pdo->prepare("SELECT nome FROM pacientes WHERE id = ?");
        $stmt->execute([$id]);
        return (string) $stmt->fetchColumn();
    }
}
