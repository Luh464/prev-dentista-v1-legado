<div class="card">
    <h2>Editar Usuário</h2>

    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'login_duplicado'): ?>
        <p class='error'>O login informado já está em uso por outro usuário.</p>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?rota=usuarios.salvar" method="POST" style="margin-top: 1rem;">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        
        <div class="form-group">
            <label for="nome">Nome Completo</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" value="<?= htmlspecialchars($usuario['login']) ?>" required>
        </div>

        <div class="form-group">
            <label for="senha">Nova Senha (deixe em branco para não alterar)</label>
            <input type="password" name="senha" id="senha">
        </div>

        <div class="form-group">
            <label for="perfil">Perfil</label>
            <select name="perfil" id="perfil" required>
                <option value="recepcionista" <?= $usuario['perfil'] === 'recepcionista' ? 'selected' : '' ?>>Recepcionista</option>
                <option value="dentista" <?= $usuario['perfil'] === 'dentista' ? 'selected' : '' ?>>Dentista</option>
                <option value="proprietario" <?= $usuario['perfil'] === 'proprietario' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="<?= BASE_URL ?>?rota=usuarios" class="btn btn-cancel">Cancelar</a>
        </div>
    </form>
</div>

<?php  ?>
