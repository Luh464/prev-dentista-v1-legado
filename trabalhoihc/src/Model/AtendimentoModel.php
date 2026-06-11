<?php
// src/Model/AtendimentoModel.php

class AtendimentoModel
{
    public function __construct(private PDO $pdo) {}

    // ─── Faturamento / Métricas ───────────────────────────────────────────────

    public function faturamentoBruto(string $inicio, string $fim): float
    {
        // Inclui feito, finalizado e pendente para mostrar tudo que foi lançado
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(ap.valor_procedimento), 0)
            FROM atendimento_procedimentos ap
            JOIN atendimentos a ON ap.id_atendimento = a.id
            WHERE a.data_atendimento BETWEEN ? AND ?
              AND a.status_pagamento IN ('pago','pendente')
              AND ap.status_execucao IN ('feito','finalizado','pendente')
        ");
        $stmt->execute([$inicio, $fim]);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    public function lucroLiquido(string $inicio, string $fim): float
    {
        $stmt = $this->pdo->prepare("
            SELECT SUM(valor_liquido_clinica)
            FROM atendimentos
            WHERE data_atendimento BETWEEN ? AND ?
              AND status_pagamento = 'pago'
        ");
        $stmt->execute([$inicio, $fim]);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    public function totalTaxas(string $data): float
    {
        $stmt = $this->pdo->prepare("
            SELECT SUM(a.taxa_cartao)
            FROM atendimentos a
            WHERE DATE(a.data_atendimento) = ?
              AND a.status_pagamento = 'pago'
              AND EXISTS (
                  SELECT 1 FROM atendimento_procedimentos ap
                  WHERE ap.id_atendimento = a.id AND ap.status_execucao = 'feito'
              )
        ");
        $stmt->execute([$data]);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    public function totalCustoAuxiliar(string $data): float
    {
        $stmt = $this->pdo->prepare("
            SELECT SUM(a.custo_auxiliar)
            FROM atendimentos a
            WHERE DATE(a.data_atendimento) = ?
              AND a.status_pagamento = 'pago'
              AND EXISTS (
                  SELECT 1 FROM atendimento_procedimentos ap
                  WHERE ap.id_atendimento = a.id AND ap.status_execucao = 'feito'
              )
        ");
        $stmt->execute([$data]);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    // ─── Listagem paginada (Dashboard) ───────────────────────────────────────

    public function contarPagos(string $busca): int
    {
        $sql    = "SELECT COUNT(DISTINCT a.id)
                   FROM atendimentos a
                   JOIN pacientes p ON a.paciente_id = p.id
                   WHERE a.status_pagamento IN ('pago','pendente')";
        $params = [];

        if (!empty($busca)) {
            $sql .= " AND p.nome LIKE ?";
            $params[] = "%$busca%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function listarPagos(string $busca, int $limit, int $offset): array
    {
        $sql = "
            SELECT
                a.id, a.data_atendimento,
                p.nome   AS paciente_nome,
                a.status_pagamento,
                a.taxa_cartao, a.valor_liquido_clinica,
                a.custo_auxiliar, a.comissao_dentista, a.url_arquivo,
                u.nome   AS dentista,
                SUM(CASE WHEN ap.status_execucao IN ('feito','finalizado','pendente') THEN ap.valor_procedimento ELSE 0 END) AS valor_bruto_total,
                GROUP_CONCAT(
                    CASE WHEN ap.status_execucao IN ('feito','finalizado','pendente') THEN proc.nome END
                    SEPARATOR ', '
                ) AS procedimentos
            FROM atendimentos a
            JOIN pacientes   p    ON a.paciente_id    = p.id
            JOIN usuarios    u    ON a.id_dentista     = u.id
            LEFT JOIN atendimento_procedimentos ap   ON a.id = ap.id_atendimento
            LEFT JOIN procedimentos             proc ON ap.id_procedimento = proc.id
            WHERE a.status_pagamento IN ('pago','pendente')
        ";
        $params = [];

        if (!empty($busca)) {
            $sql .= " AND p.nome LIKE ?";
            $params[] = "%$busca%";
        }

        $sql .= " GROUP BY a.id ORDER BY a.data_atendimento DESC LIMIT ? OFFSET ?";
        $stmt = $this->pdo->prepare($sql);
        // bindValue posicional para garantir tipos corretos no MariaDB
        $pos = 1;
        if (!empty($busca)) {
            $stmt->bindValue($pos++, "%$busca%", PDO::PARAM_STR);
        }
        $stmt->bindValue($pos++, $limit,  PDO::PARAM_INT);
        $stmt->bindValue($pos,   $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ─── Inserção ─────────────────────────────────────────────────────────────

    public function inserir(array $dados): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO atendimentos
                (paciente_id, id_dentista, data_atendimento, status_pagamento,
                 valor_total, comissao_dentista, custo_auxiliar, taxa_cartao,
                 valor_liquido_clinica, url_arquivo)
            VALUES
                (:paciente_id, :id_dentista, :data_atendimento, :status_pagamento,
                 :valor_total, :comissao_dentista, :custo_auxiliar, :taxa_cartao,
                 :valor_liquido_clinica, :url_arquivo)
        ");
        $stmt->execute($dados);
        return (int) $this->pdo->lastInsertId();
    }

    public function inserirProcedimento(array $dados): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO atendimento_procedimentos
                (id_atendimento, id_procedimento, quantidade, valor_procedimento,
                 local, natureza, custo_auxiliar, descricao, status_execucao, url_arquivo)
            VALUES
                (:id_atendimento, :id_procedimento, :quantidade, :valor_procedimento,
                 :local, :natureza, :custo_auxiliar, :descricao, :status_execucao, :url_arquivo)
        ");
        $stmt->execute($dados);
        return (int) $this->pdo->lastInsertId();
    }

    public function deletarProcedimentos(array $ids): void
    {
        if (empty($ids)) return;
        $ids      = array_filter($ids, 'is_numeric');
        $inQuery  = implode(',', array_fill(0, count($ids), '?'));
        $stmt     = $this->pdo->prepare("DELETE FROM atendimento_procedimentos WHERE id IN ($inQuery)");
        $stmt->execute(array_values($ids));
    }

    // ─── Pagamento ────────────────────────────────────────────────────────────

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM atendimentos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function ultimoPendenteDoPaciente(int $pacienteId): int|false
    {
        // Prioriza o mais recente com pagamento pendente
        $stmt = $this->pdo->prepare("
            SELECT id FROM atendimentos
            WHERE paciente_id = ? AND status_pagamento = 'pendente'
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute([$pacienteId]);
        $id = $stmt->fetchColumn();

        // Se não há pendente, pega o último de qualquer status (para reprocessar)
        if (!$id) {
            $stmt = $this->pdo->prepare("
                SELECT id FROM atendimentos
                WHERE paciente_id = ?
                ORDER BY id DESC LIMIT 1
            ");
            $stmt->execute([$pacienteId]);
            $id = $stmt->fetchColumn();
        }
        return $id;
    }

    public function procedimentosFinalizadosDoAtendimento(int $atendimentoId): array
    {
        // Busca procedimentos finalizado OU pendente — ambos aparecem no resumo de pagamento
        $stmt = $this->pdo->prepare("
            SELECT ap.id, p.nome, ap.valor_procedimento, ap.quantidade
            FROM atendimento_procedimentos ap
            JOIN procedimentos p ON ap.id_procedimento = p.id
            WHERE ap.id_atendimento = ?
              AND ap.status_execucao IN ('finalizado', 'pendente')
        ");
        $stmt->execute([$atendimentoId]);
        return $stmt->fetchAll();
    }

    public function procedimentosParaComissao(int $atendimentoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT ap.valor_procedimento, ap.custo_auxiliar, ap.natureza, p.categoria
            FROM atendimento_procedimentos ap
            JOIN procedimentos p ON ap.id_procedimento = p.id
            WHERE ap.id_atendimento = ?
              AND ap.status_execucao IN ('finalizado', 'pendente')
        ");
        $stmt->execute([$atendimentoId]);
        return $stmt->fetchAll();
    }

    public function inserirPagamento(int $atendimentoId, string $forma, float $valor, int $parcelas): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO atendimento_pagamentos (id_atendimento, forma_pagamento, valor, qtd_parcelas)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$atendimentoId, $forma, $valor, $parcelas]);
    }

    public function atualizarPagamento(
        int    $atendimentoId,
        float  $taxaCartao,
        float  $comissaoDentista,
        float  $custoAuxiliar,
        float  $valorLiquido
    ): void {
        $stmt = $this->pdo->prepare("
            UPDATE atendimentos
            SET status_pagamento       = 'pago',
                taxa_cartao            = ?,
                comissao_dentista      = ?,
                custo_auxiliar         = ?,
                valor_liquido_clinica  = ?
            WHERE id = ?
        ");
        $stmt->execute([$taxaCartao, $comissaoDentista, $custoAuxiliar, $valorLiquido, $atendimentoId]);
    }

    public function marcarProcedimentosComoFeito(int $atendimentoId): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE atendimento_procedimentos
            SET status_execucao = 'feito'
            WHERE id_atendimento = ?
              AND status_execucao IN ('finalizado', 'pendente')
        ");
        $stmt->execute([$atendimentoId]);
    }

    public function temPagamentoPendente(int $pacienteId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM atendimentos WHERE paciente_id = ? AND status_pagamento = 'pendente'"
        );
        $stmt->execute([$pacienteId]);
        return $stmt->fetchColumn() > 0;
    }

    // ─── Histórico do paciente ────────────────────────────────────────────────

    public function historicoCompletoPaciente(int $pacienteId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                ap.id, p.nome AS procedimento_nome,
                ap.local, ap.descricao, ap.status_execucao,
                a.data_atendimento, a.status_pagamento
            FROM atendimento_procedimentos ap
            JOIN atendimentos  a ON ap.id_atendimento  = a.id
            JOIN procedimentos p ON ap.id_procedimento = p.id
            WHERE a.paciente_id = ?
              AND ap.status_execucao IN ('feito', 'pendente')
            ORDER BY a.data_atendimento DESC
        ");
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }

    public function procedimentosPendentesDoPaciente(int $pacienteId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                ap.id AS atendimento_procedimento_id,
                ap.id_procedimento,
                p.nome AS procedimento_nome,
                p.categoria,
                ap.quantidade, ap.valor_procedimento,
                ap.local, ap.custo_auxiliar, ap.descricao, ap.natureza
            FROM atendimento_procedimentos ap
            JOIN atendimentos  a ON ap.id_atendimento  = a.id
            JOIN procedimentos p ON ap.id_procedimento = p.id
            WHERE a.paciente_id = ? AND ap.status_execucao = 'pendente'
        ");
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }

    // ─── Remoção de procedimento / anexo ─────────────────────────────────────

    public function buscarProcedimentoParaRemocao(int $id): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT ap.status_execucao, a.status_pagamento, ap.url_arquivo
            FROM atendimento_procedimentos ap
            JOIN atendimentos a ON ap.id_atendimento = a.id
            WHERE ap.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function excluirProcedimentoUnitario(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM atendimento_procedimentos WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function removerAnexoProcedimento(int $id): string|false
    {
        $stmt = $this->pdo->prepare("SELECT url_arquivo FROM atendimento_procedimentos WHERE id = ?");
        $stmt->execute([$id]);
        $caminho = $stmt->fetchColumn();

        if (!$caminho) return false;

        $stmt = $this->pdo->prepare("UPDATE atendimento_procedimentos SET url_arquivo = NULL WHERE id = ?");
        $stmt->execute([$id]);
        return $caminho;
    }

    public function atualizarUrlArquivoProcedimento(int $id, string $url): void
    {
        $stmt = $this->pdo->prepare("UPDATE atendimento_procedimentos SET url_arquivo = ? WHERE id = ?");
        $stmt->execute([$url, $id]);
    }

    // ─── Relatório diário ────────────────────────────────────────────────────

    public function pagamentoPorDentistaNoDia(string $data, ?int $dentistaId = null): array
    {
        $sql    = "
            SELECT u.nome, SUM(a.comissao_dentista) AS total_comissao
            FROM atendimentos a
            JOIN usuarios u ON a.id_dentista = u.id
            WHERE DATE(a.data_atendimento) = ? AND a.status_pagamento = 'pago'
              AND EXISTS (
                  SELECT 1 FROM atendimento_procedimentos ap
                  WHERE ap.id_atendimento = a.id AND ap.status_execucao = 'feito'
              )
        ";
        $params = [$data];

        if ($dentistaId) {
            $sql .= " AND a.id_dentista = ?";
            $params[] = $dentistaId;
        }

        $sql .= " GROUP BY u.nome HAVING total_comissao > 0 ORDER BY u.nome";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ─── Relatório por dentista ───────────────────────────────────────────────

    public function relatorioPorDentista(string $inicio, string $fim, mixed $dentistaId): array
    {
        $params = [
            ':inicio' => $inicio . ' 00:00:00',
            ':fim'    => $fim    . ' 23:59:59',
        ];

        $filtroDentista = '';
        if ($dentistaId !== 'todos') {
            $filtroDentista = 'AND a.id_dentista = :dentista_id';
            $params[':dentista_id'] = $dentistaId;
        }

        // Calcula faturamento bruto como soma dos procedimentos feitos/pendentes
        // valor_liquido_clinica e comissao_dentista vêm direto do atendimento (já calculados)
        $sql = "
            SELECT
                u.id   AS dentista_id,
                u.nome AS dentista_nome,
                COUNT(DISTINCT a.id)                        AS total_atendimentos,
                COALESCE(SUM(ap.valor_procedimento), 0)     AS faturamento_bruto,
                COALESCE(SUM(a.valor_liquido_clinica), 0)   AS valor_para_clinica,
                COALESCE(SUM(a.comissao_dentista), 0)       AS valor_para_dentista
            FROM usuarios u
            INNER JOIN atendimentos a
                    ON a.id_dentista = u.id
                   AND a.data_atendimento BETWEEN :inicio AND :fim
                   AND a.status_pagamento IN ('pago', 'pendente')
                   $filtroDentista
            LEFT JOIN atendimento_procedimentos ap
                   ON ap.id_atendimento = a.id
                  AND ap.status_execucao IN ('feito','finalizado','pendente')
            GROUP BY u.id, u.nome
            ORDER BY u.nome
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ─── Relatório por paciente ───────────────────────────────────────────────

    public function procedimentosDoPacientePaginado(int $pacienteId, int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                ap.id AS atendimento_procedimento_id,
                proc.nome AS procedimento_nome,
                ap.local, ap.descricao,
                a.data_atendimento,
                ap.status_execucao, a.status_pagamento, ap.url_arquivo
            FROM atendimento_procedimentos ap
            JOIN atendimentos  a    ON ap.id_atendimento  = a.id
            JOIN procedimentos proc ON ap.id_procedimento = proc.id
            WHERE a.paciente_id = :paciente_id
            ORDER BY a.data_atendimento DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':paciente_id', $pacienteId, PDO::PARAM_INT);
        $stmt->bindValue(':limit',       $limit,      PDO::PARAM_INT);
        $stmt->bindValue(':offset',      $offset,     PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contarProcedimentosDoPaciente(int $pacienteId): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(ap.id)
            FROM atendimento_procedimentos ap
            JOIN atendimentos a ON ap.id_atendimento = a.id
            WHERE a.paciente_id = ?
        ");
        $stmt->execute([$pacienteId]);
        return (int) $stmt->fetchColumn();
    }

    public function statusDentesDoOdontograma(int $pacienteId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT ap.local, ap.status_execucao
            FROM atendimento_procedimentos ap
            JOIN atendimentos a ON ap.id_atendimento = a.id
            WHERE a.paciente_id = ? AND ap.local IS NOT NULL AND ap.local != ''
        ");
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }

    // ─── Relatório financeiro geral ───────────────────────────────────────────

    public function contarAtendimentosPagos(string $inicio, string $fim): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(DISTINCT a.id)
            FROM atendimentos a
            WHERE a.data_atendimento BETWEEN ? AND ?
              AND a.status_pagamento = 'pago'
        ");
        $stmt->execute([$inicio . ' 00:00:00', $fim . ' 23:59:59']);
        return (int) $stmt->fetchColumn();
    }

    public function listarAtendimentosPagos(string $inicio, string $fim, int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                a.id, a.data_atendimento,
                p.nome   AS paciente_nome,
                a.valor_liquido_clinica,
                u.nome   AS dentista,
                GROUP_CONCAT(CASE WHEN ap.status_execucao = 'feito' THEN proc.nome END SEPARATOR ', ') AS procedimento,
                SUM(CASE WHEN ap.status_execucao = 'feito' THEN ap.valor_procedimento ELSE 0 END) AS valor_bruto
            FROM atendimentos a
            JOIN pacientes p    ON a.paciente_id    = p.id
            JOIN usuarios  u    ON a.id_dentista     = u.id
            LEFT JOIN atendimento_procedimentos ap   ON a.id  = ap.id_atendimento
            LEFT JOIN procedimentos             proc ON ap.id_procedimento = proc.id
            WHERE a.data_atendimento BETWEEN ? AND ?
              AND a.status_pagamento = 'pago'
            GROUP BY a.id
            ORDER BY a.data_atendimento DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $inicio . ' 00:00:00', PDO::PARAM_STR);
        $stmt->bindValue(2, $fim    . ' 23:59:59', PDO::PARAM_STR);
        $stmt->bindValue(3, $limit,                PDO::PARAM_INT);
        $stmt->bindValue(4, $offset,               PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function faturamentoBrutoMensal(string $inicioMes, string $fimMes): float
    {
        $stmt = $this->pdo->prepare("
            SELECT SUM(ap.valor_procedimento)
            FROM atendimento_procedimentos ap
            JOIN atendimentos a ON ap.id_atendimento = a.id
            WHERE a.data_atendimento BETWEEN ? AND ?
        ");
        $stmt->execute([$inicioMes, $fimMes]);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    // ─── Recibo ───────────────────────────────────────────────────────────────

    public function dadosCompletosParaRecibo(int $id): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT
                a.id, a.data_atendimento, a.valor_total,
                a.taxa_cartao, a.custo_auxiliar, a.comissao_dentista,
                a.valor_liquido_clinica, a.status_pagamento,
                p.nome  AS paciente_nome, p.cpf AS paciente_cpf,
                p.telefone AS paciente_telefone,
                u.nome  AS dentista_nome
            FROM atendimentos a
            JOIN pacientes p ON a.paciente_id  = p.id
            JOIN usuarios  u ON a.id_dentista  = u.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function procedimentosDoRecibo(int $atendimentoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                proc.nome   AS procedimento_nome,
                ap.quantidade,
                ap.valor_procedimento,
                ap.local,
                ap.status_execucao
            FROM atendimento_procedimentos ap
            JOIN procedimentos proc ON ap.id_procedimento = proc.id
            WHERE ap.id_atendimento = ?
            ORDER BY proc.nome ASC
        ");
        $stmt->execute([$atendimentoId]);
        return $stmt->fetchAll();
    }

    public function pagamentosDoRecibo(int $atendimentoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT forma_pagamento, valor, qtd_parcelas
            FROM atendimento_pagamentos
            WHERE id_atendimento = ?
            ORDER BY id ASC
        ");
        $stmt->execute([$atendimentoId]);
        return $stmt->fetchAll();
    }
}
