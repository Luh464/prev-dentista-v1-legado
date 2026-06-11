<div class="card">
    <h2>Relatório de Desempenho por Dentista</h2>

    <form method="GET" action="<?= BASE_URL ?>index.php" class="card" style="margin-top: 1rem;">
        <input type="hidden" name="rota" value="relatorios.dentistas">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <div class="form-group">
                <label for="inicio">Data Início</label>
                <input type="date" name="inicio" id="inicio" value="<?= $data_inicio ?>">
            </div>
            <div class="form-group">
                <label for="fim">Data Fim</label>
                <input type="date" name="fim" id="fim" value="<?= $data_fim ?>">
            </div>
            <?php if (is_admin()): ?>
            <div class="form-group">
                <label for="dentista_id">Dentista</label>
                <select name="dentista_id" id="dentista_id">
                    <option value="todos">Todos</option>
                    <?php foreach ($dentistas as $dentista): ?>
                        <option value="<?= $dentista['id'] ?>" <?= $dentista_id == $dentista['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dentista['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <div style="margin-top: 2rem;">
        <table class="mobile-card-table" style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Dentista</th>
                    <th>Nº de Atendimentos</th>
                    <th>Faturamento Bruto</th>
                    <th>Valor p/ Dentista</th>
                    <th>Valor p/ Clínica</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($relatorio_dentistas) > 0): ?>
                    <?php foreach($relatorio_dentistas as $rel): ?>
                    <tr>
                        <td data-label = "Dentista"><?= htmlspecialchars($rel['dentista_nome']) ?></td>
                        <td data-label = "Atendimentos"><?= $rel['total_atendimentos'] ?></td>
                        <td data-label = "Faturamento Bruto">R$ <?= number_format($rel['faturamento_bruto'], 2, ',', '.') ?></td>
                        <td data-label = "Faturamento Dentista" style="color: var(--success-color); font-weight: bold;"><?= number_format($rel['valor_para_dentista'], 2, ',', '.') ?></td>
                        <td data-label = "Faturamento Clínica" style="color: var(--success-color); font-weight: bold;"><?= number_format($rel['valor_para_clinica'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Nenhum dado encontrado para os filtros selecionados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php  ?>
