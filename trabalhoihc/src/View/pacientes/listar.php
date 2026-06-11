<div class="card">
    <h2>Gestão de Pacientes</h2>

    <?php if (isset($_GET['erro'])):
        $erro = $_GET['erro'];
        if ($erro === 'conflito_atendimento') {
            echo "<p class='error'>Não é possível excluir o paciente, pois ele está vinculado a um ou mais atendimentos.</p>";
        }
    endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso'): ?>
        <p class="success">Paciente salvo com sucesso!</p>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'excluido'): ?>
        <p class="success">Paciente excluído com sucesso!</p>
    <?php endif; ?>

    <!-- Formulário para Adicionar Paciente -->
    <div class="card" style="margin-top: 2rem;">
        <h3>Novo Paciente</h3>
        <form action="<?= BASE_URL ?>?rota=pacientes.salvar" method="POST">
             <div class="grid-container">
                <div class="form-group grid-col-6">
                    <label for="paciente_nome">Nome Completo</label>
                    <input type="text" name="paciente_nome" id="paciente_nome" required>
                </div>
                <div class="form-group grid-col-3">
                    <label for="paciente_cpf">CPF</label>
                    <input type="text" name="paciente_cpf" id="paciente_cpf" maxlength="14" oninput="mascaraCPF(this)">
                </div>
                 <div class="form-group grid-col-3">
                    <label for="paciente_data_nascimento">Data de Nascimento</label>
                    <input type="date" name="paciente_data_nascimento" id="paciente_data_nascimento">
                </div>
                <div class="form-group grid-col-3">
                    <label for="paciente_telefone">Telefone</label>
                    <input type="text" name="paciente_telefone" id="paciente_telefone" maxlength="15" oninput="mascaraTelefone(this)">
                </div>
                <div class="form-group grid-col-3">
                    <label for="paciente_email">E-mail</label>
                    <input type="email" name="paciente_email" id="paciente_email" onblur="validarEmail(this)">
                    <span id="email-error" style="color: red; font-size: 0.8em; display: none;">E-mail inválido</span>
                </div>
                <div class="form-group grid-col-2">
                    <label for="paciente_cep">CEP</label>
                    <input type="text" name="paciente_cep" id="paciente_cep" maxlength="9" oninput="mascaraCEP(this)">
                </div>
                <div class="form-group grid-col-4">
                    <label for="paciente_endereco">Endereço</label>
                    <input type="text" name="paciente_endereco" id="paciente_endereco">
                </div>
                <div class="form-group grid-col-2">
                    <label for="paciente_numero">Número</label>
                    <input type="text" name="paciente_numero" id="paciente_numero">
                </div>
                <div class="form-group grid-col-4">
                    <label for="paciente_bairro">Bairro</label>
                    <input type="text" name="paciente_bairro" id="paciente_bairro">
                </div>
                <div class="form-group grid-col-4">
                    <label for="paciente_cidade">Cidade</label>
                    <input type="text" name="paciente_cidade" id="paciente_cidade">
                </div>
                <div class="form-group grid-col-2">
                    <label for="paciente_estado">Estado</label>
                    <input type="text" name="paciente_estado" id="paciente_estado" maxlength="2">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Salvar Novo Paciente</button>
        </form>
    </div>

    <!-- Tabela de Pacientes -->
    <h3 style="margin-top: 2rem;">Pacientes Cadastrados</h3>
    <div class="busca-wrapper" style="margin-bottom:1rem;">
        <input type="text" id="busca_pacientes"
               placeholder="Filtrar por nome ou CPF..."
               value="<?= htmlspecialchars($busca) ?>"
               style="padding:8px 12px; border:1px solid #ccc; border-radius:6px; width:100%; box-sizing:border-box;"
               autocomplete="off">
        <ul id="drop_pacientes" class="busca-dropdown"></ul>
        <span id="status_pacientes" class="busca-status"></span>
    </div>

    <table class="mobile-card-table" id="tabela_pacientes" style="margin-top: 1rem;">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($pacientes) > 0): ?>
                <?php foreach ($pacientes as $paciente): ?>
                    <tr>
                        <td data-label="Nome"><?= htmlspecialchars($paciente['nome']) ?></td>
                        <td data-label="CPF"><?= htmlspecialchars($paciente['cpf'] ?? '') ?></td>
                        <td data-label="Telefone"><?= htmlspecialchars($paciente['telefone'] ?? '') ?></td>
                        <td data-label="Ações" style="display: flex; gap: 0.5rem;">
                            <a href=BASE_URL . "?rota=pacientes.editar&id=<?= $paciente['id'] ?>" class="btn btn-primary">Editar</a>
                            <a href="<?= BASE_URL ?>?rota=pacientes.excluir&id=<?= $paciente['id'] ?>" class="btn btn-danger" onclick="return confirm('Você realmente deseja remover este paciente? Esta ação não pode ser desfeita.');">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Nenhum paciente encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <?php if ($totalPaginas > 1): ?>
    <div style="display: flex; justify-content: flex-end; margin-top: 1rem; gap: 0.5rem;">
        <?php for ($i = 1; $i <= $totalPaginas; $i++):
            $queryParams = $_GET;
            $queryParams['pagina'] = $i;
            $url = '?' . http_build_query($queryParams);
        ?>
            <a href="<?= $url ?>" class="btn <?= $i === $pagina ? 'btn-primary' : 'btn-secondary' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.success { color: green; background: #e8f5e9; padding: 1rem; border-radius: 6px; }
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

<style>
.busca-wrapper { position: relative; }
.busca-dropdown {
    display:none; position:absolute; top:100%; left:0; right:0;
    background:#fff; border:1px solid #ccc; border-radius:4px;
    max-height:240px; overflow-y:auto; margin:2px 0 0; padding:0;
    list-style:none; z-index:99999; box-shadow:0 4px 12px rgba(0,0,0,.15);
}
.busca-status { font-size:12px; color:#888; margin-top:3px; display:block; min-height:16px; }
</style>

<script>
(function() {
    // ── buscaLiveTable: filtra uma <table> nos dados já renderizados ──────────
    window.buscaLiveTable = function(cfg) {
        // cfg: { inputId, tableId, colIndex, rota, mes }
        var input   = document.getElementById(cfg.inputId);
        var table   = document.getElementById(cfg.tableId);
        if (!input || !table) return;

        // Previne submit do form pai ao pressionar Enter
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') e.preventDefault();
        });

        var _t = null;
        input.addEventListener('input', function() {
            clearTimeout(_t);
            var term = this.value.toLowerCase().trim();
            _t = setTimeout(function() {
                var rows = table.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    if (cfg.colIndex !== undefined) {
                        var cell = row.cells[cfg.colIndex];
                        var txt  = cell ? cell.textContent.toLowerCase() : '';
                        row.style.display = (!term || txt.includes(term)) ? '' : 'none';
                    } else {
                        // Busca em todas as colunas
                        var txt = row.textContent.toLowerCase();
                        row.style.display = (!term || txt.includes(term)) ? '' : 'none';
                    }
                });
            }, 150);
        });
    };

    // ── buscaLiveDropdown: dropdown de pacientes via AJAX ────────────────────
    window.buscaLiveDropdown = function(cfg) {
        // cfg: { inputId, listId, statusId, onSelect }
        var input  = document.getElementById(cfg.inputId);
        var lista  = document.getElementById(cfg.listId);
        var status = cfg.statusId ? document.getElementById(cfg.statusId) : null;
        if (!input || !lista) return;

        // Previne submit do form pai ao pressionar Enter
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); lista.style.display='none'; }
        });

        var _t = null;
        input.addEventListener('input', function() {
            var term = this.value;
            clearTimeout(_t);
            lista.innerHTML = ''; lista.style.display = 'none';
            if (!term) { if(status) status.textContent = ''; return; }
            if(status) status.textContent = 'Buscando...';
            _t = setTimeout(function() {
                fetch(window.__BASE_URL + 'ajax/buscar_paciente.php?term=' + encodeURIComponent(term))
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        lista.innerHTML = '';
                        if (!data.length) {
                            if(status) status.textContent = 'Nenhum paciente encontrado.';
                            lista.style.display = 'none';
                            if (cfg.onNone) cfg.onNone(term);
                            return;
                        }
                        data.forEach(function(p) {
                            var li = document.createElement('li');
                            li.style.cssText = 'padding:10px 14px;cursor:pointer;border-bottom:1px solid #eee;font-size:14px;';
                            li.innerHTML = '<strong>' + esc(p.nome) + '</strong>'
                                + (p.cpf      ? ' <span style="color:#999;font-size:12px;"> · CPF: '  + esc(p.cpf)      + '</span>' : '')
                                + (p.telefone ? ' <span style="color:#999;font-size:12px;"> · '        + esc(p.telefone) + '</span>' : '');
                            li.onmouseover = function(){ this.style.background='#f0f4ff'; };
                            li.onmouseout  = function(){ this.style.background=''; };
                            li.onmousedown = function(e) {
                                e.preventDefault();
                                lista.style.display = 'none';
                                if (cfg.onSelect) cfg.onSelect(p);
                            };
                            lista.appendChild(li);
                        });
                        lista.style.display = 'block';
                        if(status) status.textContent = data.length + ' encontrado(s).';
                    })
                    .catch(function(err){ if(status) status.textContent = 'Sem conexão com o servidor.'; console.error('Busca AJAX erro:', err); });
            }, 220);
        });

        // Fechar ao clicar fora
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !lista.contains(e.target))
                lista.style.display = 'none';
        });
    };

    function esc(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
})();
</script>

<script>
// Filtra a tabela ao digitar
buscaLiveTable({ inputId: 'busca_pacientes', tableId: 'tabela_pacientes', colIndex: 0 });
// Dropdown de sugestões (clica e vai para editar)
buscaLiveDropdown({
    inputId:  'busca_pacientes',
    listId:   'drop_pacientes',
    statusId: 'status_pacientes',
    onSelect: function(p) {
        window.location.href = window.__BASE_URL + '?rota=pacientes.editar&id=' + p.id;
    }
});
</script>
