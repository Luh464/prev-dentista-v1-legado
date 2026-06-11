<div class="card">
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <a href="<?= BASE_URL ?>?rota=relatorios.diario&data=<?= $data_anterior ?>" class="btn btn-secondary">&lt; Dia Anterior</a>
        <h2>Relatório do Dia: <?= date('d/m/Y', strtotime($data_selecionada)) ?></h2>
        <a href="<?= BASE_URL ?>?rota=relatorios.diario&data=<?= $data_posterior ?>" class="btn btn-secondary">Próximo Dia &gt;</a>
    </div>

    <form method="GET" action="<?= BASE_URL ?>index.php" class="card" style="margin-top: 1rem; margin-bottom: 2rem;">
        <input type="hidden" name="rota" value="relatorios.diario">
        <div class="form-group" style="max-width: 300px; margin: auto;">
            <label for="data">Selecionar outra data</label>
            <input type="date" name="data" id="data" value="<?= $data_selecionada ?>" onchange="this.form.submit()">
        </div>
    </form>

    <?php if (is_admin()): ?>
    <div class="dashboard-grid">
        
        <div class="stat-card">
            <h3>Entrada Bruta</h3>
            <div class="stat-value" style="color: var(--primary-color);">R$ <?= number_format($faturamento_bruto, 2, ',', '.') ?></div>
        </div>
        <div class="stat-card" style="border-left-color: var(--danger-color);">
            <h3>Taxas de Cartão</h3>
            <div class="stat-value" style="color: var(--danger-color);">- R$ <?= number_format($total_taxas, 2, ',', '.') ?></div>
        </div>
        <div class="stat-card" style="border-left-color: var(--danger-color);">
            <h3>Custo Auxiliar</h3>
            <div class="stat-value" style="color: var(--danger-color);">- R$ <?= number_format($total_custo_auxiliar, 2, ',', '.') ?></div>
        </div>
        <div class="stat-card" style="border-left-color: var(--danger-color);">
            <h3>Saídas (Despesas)</h3>
            <div class="stat-value" style="color: var(--danger-color);">- R$ <?= number_format($total_despesas, 2, ',', '.') ?></div>
        </div>
        <div class="stat-card" style="border-left-color: var(--success-color);">
            <h3>Lucro Líquido do Dia</h3>
            <div class="stat-value">R$ <?= number_format($lucro_liquido, 2, ',', '.') ?></div>
        </div>
    </div>
    <?php endif; ?>


    <div class="card" style="margin-top: 2rem;">
        <h3>Pagamentos por Dentista</h3>
        <table class="mobile-card-table" style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Dentista</th>
                    <th>Valor a Pagar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($pagamento_dentistas) > 0): ?>
                    <?php foreach ($pagamento_dentistas as $dentista): ?>
                    <tr>
                        <td data-label = "Nome"><?= htmlspecialchars($dentista['nome']) ?></td>
                        <td data-label = "Comissão Densista" style="color: var(--success-color); font-weight: bold;">R$ <?= number_format($dentista['total_comissao'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2" style="text-align: center;">Nenhum atendimento comissionado no dia.</td></tr>
                <?php endif; ?>
            </tbody>
            <?php if (is_admin()): ?>
             <tfoot>
                <tr style="font-weight: bold;">
                    <td>Total Comissões</td>
                    <td data-label = "Total" style="color: var(--danger-color);">- R$ <?= number_format($total_comissoes, 2, ',', '.') ?></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>

    <?php if (is_admin()): ?>
    <div class="card" style="margin-top: 2rem;">
        <h3>Despesas Detalhadas do Dia</h3>
        <table class="mobile-card-table" style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($despesas_dia) > 0): ?>
                    <?php foreach ($despesas_dia as $despesa): ?>
                    <tr>
                        <td data-label = "Descrição"><?= htmlspecialchars($despesa['descricao']) ?></td>
                        <td data-label = "Tipo"><?= ucfirst($despesa['tipo']) ?></td>
                        <td data-label = "Valor">R$ <?= number_format($despesa['valor'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align: center;">Nenhuma despesa registrada para este dia.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="font-weight: bold;">
                    <td colspan="2">Total Despesas</td>
                    <td>R$ <?= number_format($total_despesas, 2, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php  ?>
