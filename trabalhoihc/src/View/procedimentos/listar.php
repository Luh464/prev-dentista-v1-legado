<div class="card">
    <h2>Gestão de Procedimentos</h2>

    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'conflito'): ?>
        <p class="error">Não é possível excluir o procedimento, pois ele já está vinculado a um ou mais atendimentos.</p>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'sucesso'): ?>
        <p style="color: green; background: #e8f5e9; padding: 1rem; border-radius: 6px;">Procedimento salvo com sucesso!</p>
    <?php endif; ?>

    <!-- Formulário para Adicionar Procedimento -->
    <div class="card" style="margin-top: 2rem;">
        <h3>Novo Procedimento</h3>
        <form action="<?= BASE_URL ?>?rota=procedimentos.salvar" method="POST">
            <div class="form-group">
                <label for="nome">Nome do Procedimento</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoria</label>
                <select name="categoria" id="categoria" required>
                    <option value="geral">Geral</option>
                    <option value="especializado">Especializado</option>
                    <option value="protese">Prótese</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo">Arquivo</label>
                <select name="tipo" id="tipo" required>
                    <option value="0">Sem Arquivo</option>
                    <option value="1">Com Arquivo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="valor_base">Valor Base (R$)</label>
                <input type="number" step="0.01" name="valor_base" id="valor_base">
            </div>
            <button type="submit" class="btn btn-success">Salvar Procedimento</button>
        </form>
    </div>

    <!-- Tabela de Procedimentos -->
    <h3 style="margin-top: 2rem;">Procedimentos Cadastrados</h3>
    <table class="mobile-card-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Valor Base</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($procedimentos) > 0): ?>
                <?php foreach ($procedimentos as $procedimento): ?>
                    <tr>
                        <td data-label="Nome"><?= htmlspecialchars($procedimento['nome']) ?></td>
                        <td data-label="Categoria"><?= ucfirst($procedimento['categoria']) ?></td>
                        <td data-label="Valor Base">R$ <?= number_format($procedimento['valor_base'], 2, ',', '.') ?></td>
                        <td data-label="Ações">
                            <a href="<?= BASE_URL ?>?rota=procedimentos.excluir&id=<?= $procedimento['id'] ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja remover este procedimento?');">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Nenhum procedimento registrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php  ?>
