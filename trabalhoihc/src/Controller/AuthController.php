<?php
// src/Controller/AuthController.php

class AuthController
{
    public function __construct(private PDO $pdo) {}

    public function exibirLogin(): void
    {
        // Se já logado, vai ao painel
        if (isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "?rota=painel");
            exit;
        }
        $erro = $_GET['erro'] ?? null;
        require ROOT . '/src/View/autenticacao/login.php';
    }

    public function processar(): void
    {
        $login = trim($_POST['login'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $model   = new UsuarioModel($this->pdo);
        $usuario = $model->buscarPorLogin($login);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nome']   = $usuario['nome'];
            $_SESSION['usuario_perfil'] = $usuario['perfil'];
            header("Location: " . BASE_URL . "?rota=painel");
        } else {
            header("Location: " . BASE_URL . "?rota=login&erro=1");
        }
        exit;
    }

    public function logout(): void
    {
        session_destroy();
        header("Location: " . BASE_URL . "?rota=login");
        exit;
    }
}
