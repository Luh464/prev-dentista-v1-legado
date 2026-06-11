<?php
// src/Controller/PacienteController.php

class PacienteController
{
    private PacienteModel $model;

    public function __construct(private PDO $pdo)
    {
        $this->model = new PacienteModel($pdo);
    }

    public function listar(): void
    {
        $busca          = trim($_GET['busca'] ?? '');
        $pagina         = max(1, (int)($_GET['pagina'] ?? 1));
        $itensPorPagina = 10;
        $offset         = ($pagina - 1) * $itensPorPagina;

        $totalRegistros = $this->model->contar($busca);
        $totalPaginas   = ceil($totalRegistros / $itensPorPagina);
        $pacientes      = $this->model->listar($busca, $itensPorPagina, $offset);
        $erro           = $_GET['erro'] ?? null;
        $mensagem       = $_GET['msg']  ?? null;

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/pacientes/listar.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    public function criar(): void
    {
        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/pacientes/criar.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    public function editar(): void
    {
        $id      = (int)($_GET['id'] ?? 0);
        $paciente = $this->model->buscarPorId($id);

        if (!$paciente) {
            header("Location: " . BASE_URL . "?rota=pacientes");
            exit;
        }

        require ROOT . '/src/View/layout/header.php';
        require ROOT . '/src/View/pacientes/editar.php';
        require ROOT . '/src/View/layout/footer.php';
    }

    public function salvar(): void
    {
        $pacienteId = $_POST['paciente_id'] ?? null;

        $data = [
            ':nome'             => $_POST['paciente_nome']             ?? '',
            ':cpf'              => !empty($_POST['paciente_cpf'])              ? $_POST['paciente_cpf']              : null,
            ':data_nascimento'  => !empty($_POST['paciente_data_nascimento'])  ? $_POST['paciente_data_nascimento']  : null,
            ':email'            => !empty($_POST['paciente_email'])            ? (filter_var($_POST['paciente_email'], FILTER_VALIDATE_EMAIL) ? $_POST['paciente_email'] : null) : null,
            ':telefone'         => !empty($_POST['paciente_telefone'])         ? $_POST['paciente_telefone']         : null,
            ':cep'              => !empty($_POST['paciente_cep'])              ? $_POST['paciente_cep']              : null,
            ':endereco'         => !empty($_POST['paciente_endereco'])         ? $_POST['paciente_endereco']         : null,
            ':numero'           => !empty($_POST['paciente_numero'])           ? $_POST['paciente_numero']           : null,
            ':bairro'           => !empty($_POST['paciente_bairro'])           ? $_POST['paciente_bairro']           : null,
            ':cidade'           => !empty($_POST['paciente_cidade'])           ? $_POST['paciente_cidade']           : null,
            ':estado'           => !empty($_POST['paciente_estado'])           ? $_POST['paciente_estado']           : null,
        ];

        try {
            if ($pacienteId) {
                $this->model->atualizar((int)$pacienteId, $data);
            } else {
                $this->model->inserir($data);
            }
            header("Location: " . BASE_URL . "?rota=pacientes&msg=sucesso");
        } catch (PDOException $e) {
            $redir = $pacienteId
                ? "?rota=pacientes.editar&id=$pacienteId"
                : "?rota=pacientes.criar";
            $msg   = $e->getCode() == '23000'
                ? "Já existe um paciente com este CPF."
                : "Erro ao salvar: " . $e->getMessage();
            header("Location: " . BASE_URL . $redir . "&erro=" . urlencode($msg));
        }
        exit;
    }

    public function excluir(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($this->model->possuiAtendimentos($id)) {
            header("Location: " . BASE_URL . "?rota=pacientes&erro=conflito_atendimento");
            exit;
        }

        $this->model->excluir($id);
        header("Location: " . BASE_URL . "?rota=pacientes&msg=excluido");
        exit;
    }

    /** AJAX – autocomplete de paciente */
    public function buscar(): void
    {
        // Limpar qualquer output acidental (notices, warnings do PHP)
        if (ob_get_level()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        $term = trim($_GET['term'] ?? '');

        // Aceitar a partir de 1 caractere
        if (strlen($term) < 1) {
            echo json_encode([]);
            exit;
        }

        try {
            $resultado = $this->model->buscarPorNomeOuCpf($term);
            echo json_encode($resultado);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['erro' => $e->getMessage()]);
        }
        exit;
    }
}
