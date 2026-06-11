<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo #<?= $atendimento['id'] ?> — Clínica Prev Dentistas</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f9;
            color: #222;
            padding: 20px;
        }

        .recibo-wrapper {
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,.12);
            overflow: hidden;
        }

        /* ── Cabeçalho ── */
        .recibo-header {
            background: #005b96;
            color: #fff;
            padding: 28px 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .recibo-header .clinica-nome {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: .5px;
        }
        .recibo-header .clinica-sub {
            font-size: 13px;
            opacity: .8;
            margin-top: 4px;
        }
        .recibo-header .recibo-num {
            text-align: right;
        }
        .recibo-header .recibo-num h2 {
            font-size: 28px;
            font-weight: 800;
        }
        .recibo-header .recibo-num p {
            font-size: 13px;
            opacity: .8;
            margin-top: 4px;
        }

        /* ── Status badge ── */
        .status-bar {
            padding: 12px 36px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .status-bar.pago    { background: #e8f5e9; color: #2e7d32; }
        .status-bar.pendente{ background: #fff3e0; color: #e65100; }
        .status-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
        .pago    .status-dot { background: #2e7d32; }
        .pendente .status-dot { background: #e65100; }

        /* ── Corpo ── */
        .recibo-body { padding: 28px 36px; }

        /* ── Grid de infos ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }
        .info-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            border-left: 4px solid #005b96;
        }
        .info-box h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #005b96;
            margin-bottom: 8px;
        }
        .info-box p { font-size: 14px; line-height: 1.6; color: #333; }
        .info-box strong { color: #111; }

        /* ── Tabela de procedimentos ── */
        h3.section-title {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #005b96;
            border-bottom: 2px solid #005b96;
            padding-bottom: 6px;
            margin-bottom: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 14px;
        }
        thead th {
            background: #005b96;
            color: #fff;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            color: #333;
        }
        tbody td.valor { text-align: right; font-weight: 600; }
        .dente-badge {
            display: inline-block;
            background: #e3f0fb;
            color: #005b96;
            border-radius: 4px;
            padding: 1px 6px;
            font-size: 11px;
            font-weight: 700;
        }
        .status-proc {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
        }
        .status-proc.feito     { background: #e8f5e9; color: #2e7d32; }
        .status-proc.pendente  { background: #fff3e0; color: #e65100; }
        .status-proc.finalizado{ background: #e3f0fb; color: #005b96; }

        /* ── Resumo financeiro ── */
        .financeiro-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .fin-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            border-bottom: 1px dashed #e0e0e0;
        }
        .fin-row:last-child { border-bottom: none; }
        .fin-row.total {
            font-size: 17px;
            font-weight: 700;
            color: #005b96;
            border-top: 2px solid #005b96;
            margin-top: 8px;
            padding-top: 10px;
        }
        .fin-row.desconto { color: #c62828; }

        /* ── Formas de pagamento ── */
        .pagamentos-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 24px;
        }
        .pagamento-chip {
            background: #e3f0fb;
            color: #005b96;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
        }
        .pagamento-chip span { opacity: .7; font-weight: 400; }

        /* ── Rodapé do recibo ── */
        .recibo-footer {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 2px dashed #ddd;
            text-align: center;
        }
        .assinatura-box {
            display: inline-block;
            margin: 0 40px;
            text-align: center;
        }
        .assinatura-box .linha {
            width: 200px;
            border-top: 1px solid #333;
            margin: 40px auto 6px;
        }
        .assinatura-box p { font-size: 12px; color: #555; }
        .assinatura-box strong { font-size: 13px; color: #222; }

        .recibo-obs {
            margin-top: 20px;
            font-size: 11px;
            color: #888;
            text-align: center;
            line-height: 1.6;
        }

        /* ── Botões de ação (não aparecem na impressão) ── */
        .acoes-imprimir {
            max-width: 720px;
            margin: 16px auto;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
        .btn {
            padding: 10px 22px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-block;
        }
        .btn-print   { background: #005b96; color: #fff; }
        .btn-print:hover { background: #004a7c; }
        .btn-voltar  { background: #eee; color: #333; }
        .btn-voltar:hover { background: #ddd; }

        @media print {
            body { background: #fff; padding: 0; }
            .acoes-imprimir { display: none; }
            .recibo-wrapper { box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>

<!-- Botões de ação -->
<div class="acoes-imprimir">
    <a href="javascript:history.back()" class="btn btn-voltar">← Voltar</a>
    <button onclick="window.print()" class="btn btn-print">🖨 Imprimir / Salvar PDF</button>
</div>

<div class="recibo-wrapper">

    <!-- Cabeçalho -->
    <div class="recibo-header">
        <div>
            <div class="clinica-nome">🦷 Clínica Prev Dentistas</div>
            <div class="clinica-sub">Responsável Técnico: Dra. Luciana Farias</div>
        </div>
        <div class="recibo-num">
            <h2>RECIBO #<?= str_pad($atendimento['id'], 4, '0', STR_PAD_LEFT) ?></h2>
            <p><?= date('d/m/Y H:i', strtotime($atendimento['data_atendimento'])) ?></p>
        </div>
    </div>

    <!-- Status -->
    <?php
        $status = $atendimento['status_pagamento'];
        $statusClass = ($status === 'pago') ? 'pago' : 'pendente';
        $statusTexto = ($status === 'pago') ? '✓ Pagamento Confirmado' : '⏳ Pagamento Pendente';
    ?>
    <div class="status-bar <?= $statusClass ?>">
        <span class="status-dot"></span>
        <?= $statusTexto ?>
    </div>

    <!-- Corpo -->
    <div class="recibo-body">

        <!-- Info grid -->
        <div class="info-grid">
            <div class="info-box">
                <h4>Paciente</h4>
                <p>
                    <strong><?= htmlspecialchars($atendimento['paciente_nome']) ?></strong><br>
                    <?php if ($atendimento['paciente_cpf']): ?>
                        CPF: <?= htmlspecialchars($atendimento['paciente_cpf']) ?><br>
                    <?php endif; ?>
                    <?php if ($atendimento['paciente_telefone']): ?>
                        Tel: <?= htmlspecialchars($atendimento['paciente_telefone']) ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="info-box">
                <h4>Profissional</h4>
                <p>
                    <strong><?= htmlspecialchars($atendimento['dentista_nome']) ?></strong><br>
                    Data: <?= date('d/m/Y', strtotime($atendimento['data_atendimento'])) ?><br>
                    Hora: <?= date('H:i', strtotime($atendimento['data_atendimento'])) ?>
                </p>
            </div>
        </div>

        <!-- Procedimentos -->
        <h3 class="section-title">Procedimentos Realizados</h3>
        <table>
            <thead>
                <tr>
                    <th>Procedimento</th>
                    <th style="text-align:center;">Dente</th>
                    <th style="text-align:center;">Qtd</th>
                    <th style="text-align:right;">Valor</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($procedimentos)): ?>
                    <tr><td colspan="5" style="text-align:center; color:#888; padding:20px;">Nenhum procedimento registrado.</td></tr>
                <?php else: ?>
                    <?php foreach ($procedimentos as $proc): ?>
                    <tr>
                        <td><?= htmlspecialchars($proc['procedimento_nome']) ?></td>
                        <td style="text-align:center;">
                            <?php if ($proc['local']): ?>
                                <span class="dente-badge"><?= htmlspecialchars($proc['local']) ?></span>
                            <?php else: ?>
                                <span style="color:#aaa;">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center;"><?= (int)$proc['quantidade'] ?></td>
                        <td class="valor">R$ <?= number_format($proc['valor_procedimento'], 2, ',', '.') ?></td>
                        <td style="text-align:center;">
                            <?php
                                $sc = $proc['status_execucao'];
                                $sl = ['feito' => 'Feito', 'pendente' => 'Pendente', 'finalizado' => 'Finalizado'];
                            ?>
                            <span class="status-proc <?= $sc ?>"><?= $sl[$sc] ?? ucfirst($sc) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Resumo financeiro -->
        <h3 class="section-title">Resumo Financeiro</h3>
        <div class="financeiro-box">
            <div class="fin-row">
                <span>Valor Total dos Procedimentos</span>
                <strong>R$ <?= number_format($atendimento['valor_total'], 2, ',', '.') ?></strong>
            </div>
            <?php if ((float)$atendimento['taxa_cartao'] > 0): ?>
            <div class="fin-row desconto">
                <span>Taxa de Máquina (cartão)</span>
                <span>- R$ <?= number_format($atendimento['taxa_cartao'], 2, ',', '.') ?></span>
            </div>
            <?php endif; ?>
            <div class="fin-row total">
                <span>Total a Pagar</span>
                <strong>R$ <?= number_format($atendimento['valor_total'], 2, ',', '.') ?></strong>
            </div>
        </div>

        <!-- Formas de pagamento -->
        <?php if (!empty($pagamentos)): ?>
        <h3 class="section-title">Forma de Pagamento</h3>
        <div class="pagamentos-grid">
            <?php
                $formasLabel = [
                    'dinheiro' => '💵 Dinheiro',
                    'pix'      => '⚡ PIX',
                    'debito'   => '💳 Débito',
                    'credito'  => '💳 Crédito',
                ];
            ?>
            <?php foreach ($pagamentos as $pag): ?>
                <div class="pagamento-chip">
                    <?= $formasLabel[$pag['forma_pagamento']] ?? ucfirst($pag['forma_pagamento']) ?>
                    — R$ <?= number_format($pag['valor'], 2, ',', '.') ?>
                    <?php if ($pag['forma_pagamento'] === 'credito' && $pag['qtd_parcelas'] > 1): ?>
                        <span>(<?= $pag['qtd_parcelas'] ?>x)</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Assinaturas -->
        <div class="recibo-footer">
            <div class="assinatura-box">
                <div class="linha"></div>
                <strong><?= htmlspecialchars($atendimento['paciente_nome']) ?></strong>
                <p>Paciente / Responsável</p>
            </div>
            <div class="assinatura-box">
                <div class="linha"></div>
                <strong><?= htmlspecialchars($atendimento['dentista_nome']) ?></strong>
                <p>Cirurgião(ã)-Dentista</p>
            </div>
        </div>

        <p class="recibo-obs">
            Clínica Prev Dentistas · Documento gerado em <?= date('d/m/Y \à\s H:i') ?><br>
            Este recibo é válido como comprovante de atendimento odontológico.
        </p>

    </div><!-- /recibo-body -->
</div><!-- /recibo-wrapper -->

</body>
</html>
