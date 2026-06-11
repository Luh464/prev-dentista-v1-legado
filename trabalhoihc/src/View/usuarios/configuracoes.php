<div class="card">
    <h2>Minhas Configurações</h2>
    <p class="text-muted">Gerencie seus dados de acesso e perfil.</p>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso'): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
            Dados atualizados com sucesso!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['erro'])): ?>
        <div class="error">
            <?php
            switch ($_GET['erro']) {
                case 'senha_incorreta':
                    echo "A senha antiga informada está incorreta.";
                    break;
                case 'senhas_nao_coincidem':
                    echo "A nova senha e a confirmação não coincidem.";
                    break;
                case 'campos_vazios':
                    echo "Por favor, preencha todos os campos de senha para realizar a alteração.";
                    break;
                default:
                    echo "Ocorreu um erro ao salvar as alterações.";
            }
            ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?rota=configuracoes.salvar" method="POST" style="margin-top: 1.5rem;">
        
        <div class="form-group">
            <label for="nome">Nome de Exibição</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
        </div>

        <div class="form-group">
            <label for="login">Login (Usuário)</label>
            <input type="text" value="<?= htmlspecialchars($usuario['login']) ?>" disabled style="background-color: #e9ecef; cursor: not-allowed;">
            <small class="text-muted">O login não pode ser alterado.</small>
        </div>

        <hr style="margin: 2rem 0; border: 0; border-top: 1px solid #eee;">
        
        <h3>Alterar Senha</h3>
        <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 1rem;">Preencha os campos abaixo apenas se desejar alterar sua senha.</p>

        <div class="form-group">
            <label for="senha_antiga">Senha Antiga</label>
            <input type="password" name="senha_antiga" id="senha_antiga">
        </div>

        <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; padding: 0;">
            <div class="form-group">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" name="nova_senha" id="nova_senha">
            </div>
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Nova Senha</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha">
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="<?= BASE_URL ?>?rota=painel" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>


<?php  ?>