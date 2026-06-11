<?php
// src/Controller/UsuarioController.php

class UsuarioController
{
    private UsuarioModel $model;

    public function __construct(private PDO $pdo)
    {
        $this->model = new UsuarioModel($pdo);
    }

    public function listar(): void
    {
        $usuarios = $this->model->listarTodos();
        $mensagem = $_GET['msg']  ?? null;
        $erro     = $_GET['erro'] ?? null;

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/usuarios/listar.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    public function editar(): void
    {
        $id      = (int)($_GET['id'] ?? 0);
        $usuario = $this->model->buscarPorId($id);

        if (!$usuario) {
            header("Location: " . BASE_URL . "?rota=usuarios");
            exit;
        }

        $erro = $_GET['erro'] ?? null;

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/usuarios/editar.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    public function salvar(): void
    {
        $id     = $_POST['id']     ?? null;
        $nome   = trim($_POST['nome']   ?? '');
        $login  = trim($_POST['login']  ?? '');
        $senha  = $_POST['senha']       ?? '';
        $perfil = $_POST['perfil']      ?? '';

        $perfisPermitidos = ['proprietario', 'dentista', 'recepcionista'];
        if (!in_array($perfil, $perfisPermitidos) || empty($nome) || empty($login)) {
            header("Location: " . BASE_URL . "?rota=usuarios&erro=campos_invalidos");
            exit;
        }

        try {
            if ($id) {
                $hash = !empty($senha) ? password_hash($senha, PASSWORD_BCRYPT) : null;
                $this->model->atualizar((int)$id, $nome, $login, $perfil, $hash);
            } else {
                if (empty($senha)) {
                    header("Location: " . BASE_URL . "?rota=usuarios&erro=senha_obrigatoria");
                    exit;
                }
                $this->model->inserir($nome, $login, password_hash($senha, PASSWORD_BCRYPT), $perfil);
            }
            header("Location: " . BASE_URL . "?rota=usuarios&msg=sucesso");
        } catch (PDOException $e) {
            $redir = $id ? "?rota=usuarios.editar&id=$id&erro=login_duplicado" : "?rota=usuarios&erro=login_duplicado";
            header("Location: " . BASE_URL . $redir);
        }
        exit;
    }

    public function excluir(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id === (int)$_SESSION['usuario_id']) {
            header("Location: " . BASE_URL . "?rota=usuarios&erro=autoexclusao");
            exit;
        }

        if ($this->model->possuiAtendimentos($id)) {
            header("Location: " . BASE_URL . "?rota=usuarios&erro=conflito_atendimento");
            exit;
        }

        $this->model->excluir($id);
        header("Location: " . BASE_URL . "?rota=usuarios&msg=excluido");
        exit;
    }

    public function configuracoes(): void
    {
        $usuario = $this->model->buscarPorId((int)$_SESSION['usuario_id']);
        if (!$usuario) {
            session_destroy();
            header("Location: " . BASE_URL . "?rota=login");
            exit;
        }

        $mensagem = $_GET['msg']  ?? null;
        $erro     = $_GET['erro'] ?? null;

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/usuarios/configuracoes.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    public function salvarConfiguracoes(): void
    {
        $userId         = (int)$_SESSION['usuario_id'];
        $nome           = trim($_POST['nome']            ?? '');
        $senhaAntiga    = $_POST['senha_antiga']          ?? '';
        $novaSenha      = $_POST['nova_senha']            ?? '';
        $confirmarSenha = $_POST['confirmar_senha']       ?? '';

        if (empty($nome)) {
            header("Location: " . BASE_URL . "?rota=configuracoes&erro=geral");
            exit;
        }

        // Buscar hash da senha atual para verificação
        $stmt = $this->pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $row  = $stmt->fetch();
        if (!$row) {
            header("Location: " . BASE_URL . "?rota=configuracoes&erro=geral");
            exit;
        }

        $hash = null;

        if (!empty($senhaAntiga) || !empty($novaSenha) || !empty($confirmarSenha)) {
            if (empty($senhaAntiga) || empty($novaSenha) || empty($confirmarSenha)) {
                header("Location: " . BASE_URL . "?rota=configuracoes&erro=campos_vazios"); exit;
            }
            if (!password_verify($senhaAntiga, $row['senha'])) {
                header("Location: " . BASE_URL . "?rota=configuracoes&erro=senha_incorreta"); exit;
            }
            if ($novaSenha !== $confirmarSenha) {
                header("Location: " . BASE_URL . "?rota=configuracoes&erro=senhas_nao_coincidem"); exit;
            }
            $hash = password_hash($novaSenha, PASSWORD_BCRYPT);
        }

        $this->model->atualizarPerfil($userId, $nome, $hash);
        $_SESSION['usuario_nome'] = $nome;
        header("Location: " . BASE_URL . "?rota=configuracoes&msg=sucesso");
        exit;
    }
}
