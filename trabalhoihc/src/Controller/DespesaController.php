<?php
// src/Controller/DespesaController.php

class DespesaController
{
    private DespesaModel $model;

    public function __construct(private PDO $pdo)
    {
        $this->model = new DespesaModel($pdo);
    }

    public function listar(): void
    {
        $despesas = $this->model->listarTodas();
        $mensagem = $_GET['msg']  ?? null;
        $erro     = $_GET['erro'] ?? null;
        $msg      = $mensagem; // alias usado pela view

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/despesas/listar.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    public function salvar(): void
    {
        $descricao    = trim($_POST['descricao']    ?? '');
        $valor        = (float)($_POST['valor']     ?? 0);
        $tipo         = $_POST['tipo']              ?? '';
        $dataDespesa  = $_POST['data_despesa']      ?? '';

        if (empty($descricao) || $valor <= 0 || empty($tipo) || empty($dataDespesa)) {
            header("Location: " . BASE_URL . "?rota=despesas&erro=campos_obrigatorios");
            exit;
        }

        $this->model->inserir($descricao, $valor, $tipo, $dataDespesa);
        header("Location: " . BASE_URL . "?rota=despesas&msg=sucesso");
        exit;
    }

    public function excluir(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->model->excluir($id);
        header("Location: " . BASE_URL . "?rota=despesas&msg=excluido");
        exit;
    }
}
