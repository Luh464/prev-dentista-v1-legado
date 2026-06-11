<div class="card">
    <h2>Relatório por Procedimentos</h2>

    <form method="GET" action="<?= BASE_URL ?>index.php" class="card" style="margin-top: 1rem;">
        <input type="hidden" name="rota" value="relatorios.procedimentos">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <div class="form-group">
                <label for="inicio">Data Início</label>
                <input type="date" name="inicio" id="inicio" value="<?= htmlspecialchars($data_inicio) ?>">
            </div>
            <div class="form-group">
                <label for="fim">Data Fim</label>
                <input type="date" name="fim" id="fim" value="<?= htmlspecialchars($data_fim) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <div style="margin-top: 2rem;">
        <table class="mobile-card-table" style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Procedimento</th>
                    <th>Vezes Executado</th>
                    <th>Representação (%)</th>
                    <th>Valor Bruto Gerado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($procedimentos_relatorio) > 0): ?>
                    <?php foreach($procedimentos_relatorio as $proc): 
                        $porcentagem = $totalProcedimentos > 0 ? ($proc['quantidade_executada'] / $totalProcedimentos) * 100 : 0;
                    ?>
                    <tr>
                        <td data-label="Procedimento"><?= htmlspecialchars($proc['procedimento_nome']) ?></td>
                        <td data-label="Vezes Executado"><?= htmlspecialchars($proc['quantidade_executada']) ?></td>
                        <td data-label="Representação (%)"><?= number_format($porcentagem, 2, ',', '.') ?>%</td>
                        <td data-label="Valor Bruto Gerado" style="color: var(--success-color); font-weight: bold;">R$ <?= number_format($proc['valor_bruto_total'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">Nenhum procedimento encontrado para o período selecionado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <?php if (count($procedimentos_relatorio) > 0): ?>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td>Total</td>
                    <td data-label="Total Executado"><?= $totalProcedimentos ?></td>
                    <td data-label="Total %">100,00%</td>
                    <td data-label="Soma Valor Bruto" style="color: var(--success-color);">R$ <?= number_format(array_sum(array_column($procedimentos_relatorio, 'valor_bruto_total')), 2, ',', '.') ?></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php  ?>