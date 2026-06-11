<?php
// src/Model/UsuarioModel.php

class UsuarioModel
{
    public function __construct(private PDO $pdo) {}

    public function buscarPorLogin(string $login): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);
        return $stmt->fetch();
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, login, perfil FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function listarTodos(): array
    {
        return $this->pdo->query("SELECT id, nome, login, perfil FROM usuarios ORDER BY nome ASC")->fetchAll();
    }

    public function listarDentistas(): array
    {
        $stmt = $this->pdo->prepare("SELECT id, nome FROM usuarios WHERE perfil = 'dentista' ORDER BY nome ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function inserir(string $nome, string $login, string $senhaHash, string $perfil): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuarios (nome, login, senha, perfil) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$nome, $login, $senhaHash, $perfil]);
    }

    public function atualizar(int $id, string $nome, string $login, string $perfil, ?string $senhaHash = null): void
    {
        if ($senhaHash) {
            $stmt = $this->pdo->prepare(
                "UPDATE usuarios SET nome = ?, login = ?, perfil = ?, senha = ? WHERE id = ?"
            );
            $stmt->execute([$nome, $login, $perfil, $senhaHash, $id]);
        } else {
            $stmt = $this->pdo->prepare(
                "UPDATE usuarios SET nome = ?, login = ?, perfil = ? WHERE id = ?"
            );
            $stmt->execute([$nome, $login, $perfil, $id]);
        }
    }

    public function atualizarPerfil(int $id, string $nome, ?string $senhaHash = null): void
    {
        if ($senhaHash) {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ?, senha = ? WHERE id = ?");
            $stmt->execute([$nome, $senhaHash, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ? WHERE id = ?");
            $stmt->execute([$nome, $id]);
        }
    }

    public function excluir(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function possuiAtendimentos(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM atendimentos WHERE id_dentista = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
