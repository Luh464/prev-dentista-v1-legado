<div class="card">
    <h2>Novo Paciente</h2>

    <?php if (isset($erro)): ?>
        <p class="error"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?rota=pacientes.salvar" method="POST">
        <div class="grid-container">
            <div class="form-group grid-col-6"><label>Nome Completo</label><input type="text" name="paciente_nome" required></div>
            <div class="form-group grid-col-3"><label>CPF</label><input type="text" name="paciente_cpf" maxlength="14" oninput="mascaraCPF(this)"></div>
            <div class="form-group grid-col-3"><label>Data de Nascimento</label><input type="date" name="paciente_data_nascimento"></div>
            <div class="form-group grid-col-3"><label>Telefone</label><input type="text" name="paciente_telefone" maxlength="15" oninput="mascaraTelefone(this)"></div>
            <div class="form-group grid-col-3"><label>E-mail</label><input type="email" name="paciente_email" onblur="validarEmail(this)"><span id="email-error" style="color:red;font-size:.8em;display:none;">E-mail inválido</span></div>
            <div class="form-group grid-col-2"><label>CEP</label><input type="text" name="paciente_cep" maxlength="9" oninput="mascaraCEP(this)"></div>
            <div class="form-group grid-col-4"><label>Endereço</label><input type="text" name="paciente_endereco"></div>
            <div class="form-group grid-col-2"><label>Número</label><input type="text" name="paciente_numero"></div>
            <div class="form-group grid-col-4"><label>Bairro</label><input type="text" name="paciente_bairro"></div>
            <div class="form-group grid-col-4"><label>Cidade</label><input type="text" name="paciente_cidade"></div>
            <div class="form-group grid-col-2"><label>Estado</label><input type="text" name="paciente_estado" maxlength="2"></div>
        </div>
        <div style="display:flex;gap:1rem;">
            <button type="submit" class="btn btn-success">Salvar Novo Paciente</button>
            <a href="<?= BASE_URL ?>?rota=pacientes" class="btn btn-cancel">Cancelar</a>
        </div>
    </form>
</div>
<style>
.grid-container{display:grid;grid-template-columns:repeat(6,1fr);gap:1rem;margin-bottom:1rem;}
.grid-col-2{grid-column:span 2;}.grid-col-3{grid-column:span 3;}
.grid-col-4{grid-column:span 4;}.grid-col-6{grid-column:span 6;}
@media(max-width:768px){.grid-col-2,.grid-col-3,.grid-col-4,.grid-col-6{grid-column:span 6;}}
</style>
<script>
function mascaraCPF(i){var v=i.value;v=v.replace(/\D/g,'');v=v.replace(/(\d{3})(\d)/,'$1.$2');v=v.replace(/(\d{3})(\d)/,'$1.$2');v=v.replace(/(\d{3})(\d{1,2})$/,'$1-$2');i.value=v;}
function mascaraTelefone(i){var v=i.value;v=v.replace(/\D/g,'');v=v.replace(/^(\d{2})(\d)/g,'($1) $2');v=v.replace(/(\d)(\d{4})$/,'$1-$2');i.value=v;}
function mascaraCEP(i){var v=i.value;v=v.replace(/\D/g,'');v=v.replace(/^(\d{5})(\d)/,'$1-$2');i.value=v;}
function validarEmail(f){const e=document.getElementById('email-error');if(!f.value){e.style.display='none';return;}const ok=/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(f.value);e.style.display=ok?'none':'block';}
</script>
