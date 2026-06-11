<div class="card">
    <h2>Gestão de Despesas</h2>

    <!-- Formulário para Adicionar Despesa -->
    <div class="card" style="margin-top: 2rem;">
        <h3>Nova Despesa</h3>
        <form action="<?= BASE_URL ?>?rota=despesas.salvar" method="POST">
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" id="descricao" required>
            </div>
            <div class="form-group">
                <label for="valor">Valor (R$)</label>
                <input type="number" step="0.01" name="valor" id="valor" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo" required>
                    <option value="fixa">Fixa</option>
                    <option value="variavel">Variável</option>
                </select>
            </div>
            <div class="form-group">
                <label for="data_despesa">Data</label>
                <input type="date" name="data_despesa" id="data_despesa" required>
            </div>
            <button type="submit" class="btn btn-success">Salvar Despesa</button>
        </form>
    </div>

    <!-- Tabela de Despesas -->
    <h3 style="margin-top: 2rem;">Despesas Lançadas</h3>
    <table class="mobile-card-table" style="margin-top: 1rem;">
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($despesas) > 0): ?>
                <?php foreach ($despesas as $despesa): ?>
                    <tr>
                        <td data-label="Data"><?= date('d/m/Y', strtotime($despesa['data_despesa'])) ?></td>
                        <td data-label="Descrição"><?= htmlspecialchars($despesa['descricao']) ?></td>
                        <td data-label="Valor">R$ <?= number_format($despesa['valor'], 2, ',', '.') ?></td>
                        <td data-label="Tipo"><?= ucfirst($despesa['tipo']) ?></td>
                        <td data-label="Ação">
                            <a href="<?= BASE_URL ?>?rota=despesas.excluir&id=<?= $despesa['id'] ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja remover esta despesa?');">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Nenhuma despesa registrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php  ?>
