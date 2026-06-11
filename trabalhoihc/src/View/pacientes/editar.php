<div class="card">
    <h2>Editar Paciente</h2>

    <?php if (isset($_GET['erro'])):
        // Tratamento de erros, se houver
    endif; ?>

    <form action="<?= BASE_URL ?>?rota=pacientes.salvar" method="POST">
        <input type="hidden" name="paciente_id" value="<?= $paciente['id'] ?>">

        <div class="grid-container">
            <div class="form-group grid-col-6">
                <label for="paciente_nome">Nome Completo</label>
                <input type="text" name="paciente_nome" id="paciente_nome" required value="<?= htmlspecialchars($paciente['nome'] ?? '') ?>">
            </div>
            <div class="form-group grid-col-3">
                <label for="paciente_cpf">CPF</label>
                <input type="text" name="paciente_cpf" id="paciente_cpf" maxlength="14" oninput="mascaraCPF(this)" value="<?= htmlspecialchars($paciente['cpf'] ?? '') ?>">
            </div>
            <div class="form-group grid-col-3">
                <label for="paciente_telefone">Telefone</label>
                <input type="text" name="paciente_telefone" id="paciente_telefone" maxlength="15" oninput="mascaraTelefone(this)" value="<?= htmlspecialchars($paciente['telefone'] ?? '') ?>">
            </div>
            <div class="form-group grid-col-6">
                <label for="paciente_email">E-mail</label>
                <input type="email" name="paciente_email" id="paciente_email" onblur="validarEmail(this)" value="<?= htmlspecialchars($paciente['email'] ?? '') ?>">
                <span id="email-error" style="color: red; font-size: 0.8em; display: none;">E-mail inválido</span>
            </div>
            <div class="form-group grid-col-3">
                <label for="paciente_cep">CEP</label>
                <input type="text" name="paciente_cep" id="paciente_cep" maxlength="9" oninput="mascaraCEP(this)" value="<?= htmlspecialchars($paciente['cep'] ?? '') ?>">
            </div>
            <div class="form-group grid-col-3">
                <label for="paciente_data_nascimento">Data de Nascimento</label>
                <input type="date" name="paciente_data_nascimento" id="paciente_data_nascimento" value="<?= htmlspecialchars($paciente['data_nascimento'] ?? '') ?>">
            </div>
            <div class="form-group grid-col-6">
                <label for="paciente_endereco">Endereço</label>
                <input type="text" name="paciente_endereco" id="paciente_endereco" value="<?= htmlspecialchars($paciente['endereco'] ?? '') ?>">
            </div>
            <div class="form-group grid-col-2">
                <label for="paciente_numero">Número</label>
                <input type="text" name="paciente_numero" id="paciente_numero" value="<?= htmlspecialchars($paciente['numero'] ?? '') ?>">
            </div>
            <div class="form-group grid-col-4">
                <label for="paciente_bairro">Bairro</label>
                <input type="text" name="paciente_bairro" id="paciente_bairro" value="<?= htmlspecialchars($paciente['bairro'] ?? '') ?>">
            </div>
            <div class="form-group grid-col-4">
                <label for="paciente_cidade">Cidade</label>
                <input type="text" name="paciente_cidade" id="paciente_cidade" value="<?= htmlspecialchars($paciente['cidade'] ?? '') ?>">
            </div>
            <div class="form-group grid-col-2">
                <label for="paciente_estado">Estado</label>
                <input type="text" name="paciente_estado" id="paciente_estado" maxlength="2" value="<?= htmlspecialchars($paciente['estado'] ?? '') ?>">
            </div>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="<?= BASE_URL ?>?rota=pacientes" class="btn btn-cancel">Cancelar</a>
        </div>
    </form>
</div>

<style>
.grid-container { display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; margin-bottom: 1rem; }
.grid-col-2 { grid-column: span 2; }
.grid-col-3 { grid-column: span 3; }
.grid-col-4 { grid-column: span 4; }
.grid-col-6 { grid-column: span 6; }
@media (max-width: 768px) {
    .grid-col-2, .grid-col-3, .grid-col-4, .grid-col-6 { grid-column: span 6; }
}
</style>

<script>
function mascaraCPF(i) {
    var v = i.value;
    v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
    v = v.replace(/(\d{3})(\d)/, "$1.$2"); //Coloca um ponto entre o terceiro e o quarto dígitos
    v = v.replace(/(\d{3})(\d)/, "$1.$2"); //Coloca um ponto entre o terceiro e o quarto dígitos
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); //Coloca um hífen entre o terceiro e o quarto dígitos
    i.value = v;
}

function mascaraTelefone(i) {
    var v = i.value;
    v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v = v.replace(/(\d)(\d{4})$/, "$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
    i.value = v;
}

function mascaraCEP(i) {
    var v = i.value;
    v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
    v = v.replace(/^(\d{5})(\d)/, "$1-$2"); //Coloca hífen entre o quinto e o sexto dígitos
    i.value = v;
}

function validarEmail(field) {
    const usuario = field.value.substring(0, field.value.indexOf("@"));
    const dominio = field.value.substring(field.value.indexOf("@")+ 1, field.value.length);
    const errorSpan = document.getElementById('email-error');

    // Remove a possível estilização inline para garantir o estilo do CSS
    field.style.borderColor = '';

    if (field.value === '') {
        errorSpan.style.display = 'none';
        return;
    }

    if ((usuario.length >=1) && (dominio.length >=3) && (usuario.search("@")==-1) && (dominio.search("@")==-1) && (usuario.search(" ")==-1) && (dominio.search(" ")==-1) && (dominio.search(".")!=-1) && (dominio.indexOf(".") >=1)&& (dominio.lastIndexOf(".") < dominio.length - 1)) {
        errorSpan.style.display = 'none';
    } else {
        errorSpan.style.display = 'block';
    }
}
</script>

<?php  ?>
