<?php
// src/Controller/ReciboController.php

class ReciboController
{
    private AtendimentoModel $model;

    public function __construct(private PDO $pdo)
    {
        $this->model = new AtendimentoModel($pdo);
    }

    public function gerar(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            header("Location: " . BASE_URL . "?rota=painel");
            exit;
        }

        $atendimento  = $this->model->dadosCompletosParaRecibo($id);

        if (!$atendimento) {
            header("Location: " . BASE_URL . "?rota=painel");
            exit;
        }

        $procedimentos = $this->model->procedimentosDoRecibo($id);
        $pagamentos    = $this->model->pagamentosDoRecibo($id);

        require ROOT . '/src/View/recibo/gerar.php';
    }
}
