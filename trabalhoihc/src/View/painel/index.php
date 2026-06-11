<style>
    /* Estilos para o Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.6);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        margin: 5% auto;
        padding: 25px;
        border: 1px solid #888;
        width: 80%;
        max-width: 700px;
        border-radius: 8px;
        position: relative;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        max-height: 85vh;
        display: flex;
        flex-direction: column;
    }
    .modal-close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }
    #modalBody {
        overflow-y: auto;
        line-height: 1.6;
    }
    .modal-content .form-group {
        margin-bottom: 1rem;
    }
    .modal-content .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: var(--text-muted);
    }
    .modal-content .form-group input[readonly],
    .modal-content .form-group textarea[readonly] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        background-color: #f8f9fa;
        border-radius: 4px;
    }
</style>

<div style="display:flex; justify-content:space-between; align-items:center;">
    <h1>Dashboard Financeiro</h1>
    <div style="display:flex; align-items:center; gap: 1rem;">
        <a href="<?= BASE_URL ?>?rota=painel&mes=<?= $mes_anterior ?>" class="btn btn-secondary" title="Mês Anterior">&lt;</a>
        <h2 style="color: var(--text-muted); margin: 0;"><?= ucfirst($mesAtual) ?></h2>
        <a href="<?= BASE_URL ?>?rota=painel&mes=<?= $mes_proximo ?>" class="btn btn-secondary" title="Próximo Mês">&gt;</a>
    </div>
</div>

<?php if (is_admin()): ?>
<div class="dashboard-grid" style="margin-top: 2rem;">
    <div class="stat-card">
        <h3>Faturamento Bruto</h3>
        <div class="stat-value">R$ <?= number_format($faturamentoBruto, 2, ',', '.') ?></div>
        <p class="text-muted">Total transacionado no mês</p>
    </div>
    
    <div class="stat-card" style="border-left-color: var(--danger-color);">
        <h3>Total de Despesas</h3>
        <div class="stat-value">R$ <?= number_format($totalDespesas, 2, ',', '.') ?></div>
        <p class="text-muted">Soma de custos do mês</p>
    </div>

    <div class="stat-card" style="border-left-color: var(--success-color);">
        <h3>Resultado Líquido</h3>
        <div class="stat-value">R$ <?= number_format($lucroLiquido - $totalDespesas, 2, ',', '.') ?></div>
        <p class="text-muted">Lucro de atendimentos - despesas no mês</p>
    </div>
</div>
<?php endif; ?>

<div class="card" style="margin-top: 2rem;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Histórico de Atendimentos</h3>
        <div style="display:flex; gap: 1rem; align-items:center;">
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <input type="text" id="busca_painel"
                       placeholder="Filtrar por paciente..."
                       value="<?= htmlspecialchars($busca ?? '') ?>"
                       style="padding:6px 10px; border:1px solid #ccc; border-radius:6px; min-width:220px;"
                       autocomplete="off">
            </div>
            <?php if (is_admin()): ?>
            <a href="<?= BASE_URL ?>?rota=atendimentos.novo" class="btn btn-primary">Novo Lançamento +</a>
            <?php endif; ?>
        </div>
    </div>
    
    <table class="mobile-card-table" id="tabela_painel" style="margin-top: 1rem;">
        <thead>
            <tr>
                <th>Data</th>
                <th>Paciente</th>
                <th>Ações</th>
                <th>Procedimentos</th>
                <th>Dentista</th>
                <?php if (is_admin()): ?>
                <th>Valor Líquido (Clínica)</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if(count($ultimosAtendimentos) > 0): ?>
                <?php foreach($ultimosAtendimentos as $at): ?>
                    <?php
                        $isNaoAplicavel = $at['status_pagamento'] === 'nao_aplicavel';
                    ?>
                <tr class="clickable-row" 
                    data-id="<?= $at['id'] ?>"
                    data-data="<?= date('d/m/Y H:i', strtotime($at['data_atendimento'])) ?>"
                    data-paciente="<?= htmlspecialchars($at['paciente_nome']) ?>"
                    data-taxa-cartao="<?= $isNaoAplicavel ? 'N/A' : 'R$ ' . number_format($at['taxa_cartao'], 2, ',', '.') ?>"
                    data-custo-auxiliar="<?= $isNaoAplicavel ? 'N/A' : 'R$ ' . number_format($at['custo_auxiliar'], 2, ',', '.') ?>"
                    data-comissao-dentista="<?= $isNaoAplicavel ? 'N/A' : 'R$ ' . number_format($at['comissao_dentista'], 2, ',', '.') ?>"
                    data-procedimentos="<?= htmlspecialchars($at['procedimentos'] ?? '') ?>"
                    data-dentista="<?= htmlspecialchars($at['dentista']) ?>"
                    data-arquivo="<?= htmlspecialchars($at['url_arquivo'] ?? '') ?>"
                    data-valor="<?= $isNaoAplicavel ? 'N/A' : 'R$ ' . number_format($at['valor_liquido_clinica'], 2, ',', '.') ?>"
                    data-bruto="<?= $isNaoAplicavel ? 'N/A' : 'R$ ' . number_format($at['valor_bruto_total'] ?? 0, 2, ',', '.') ?>"
                    style="cursor: pointer;" title="Clique para ver detalhes">
                    <td data-label="Data"><?= date('d/m/Y H:i', strtotime($at['data_atendimento'])) ?></td>
                    <td data-label="Paciente"><?= htmlspecialchars($at['paciente_nome']) ?></td>
                    <td data-label="Ações">
                        <a href="<?= BASE_URL ?>?rota=recibo&id=<?= $at['id'] ?>" class="btn btn-secondary" target="_blank" title="Gerar Recibo">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                    <td data-label="Procedimentos"><?= htmlspecialchars($at['procedimentos'] ?? '') ?></td>
                    <td data-label="Dentista"><?= htmlspecialchars($at['dentista']) ?></td>
                    <?php if (is_admin()): ?>
                    <td data-label="Valor Líquido" style="color: green; font-weight: bold;">
                        <?= $isNaoAplicavel ? 'N/A' : 'R$ ' . number_format($at['valor_liquido_clinica'], 2, ',', '.') ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center;">Nenhum atendimento registrado ainda.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <?php if ($totalPaginas > 1): ?>
    <div style="display: flex; justify-content: flex-end; margin-top: 1rem; gap: 0.5rem;">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <?php 
                $active = $i === $pagina ? 'background-color: var(--primary-color); color: white;' : 'background-color: #eee; color: #333;';
                // Monta a URL mantendo os parâmetros existentes (mes, busca)
                $queryParams = $_GET;
                $queryParams['pagina'] = $i;
                $url = '?' . http_build_query($queryParams);
            ?>
            <a href="<?= $url ?>" style="padding: 5px 10px; text-decoration: none; border-radius: 4px; <?= $active ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Modal de Detalhes do Atendimento -->
<div id="detalhesModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" id="modalCloseBtn">&times;</span>
        <h2>Detalhes do Atendimento</h2>
        <div id="modalBody" style="line-height: 1.6;">
            <!-- Conteúdo será preenchido via JS -->
        </div>
        <div class="modal-footer">
            <button id="modalFooterCloseBtn" class="btn btn-secondary">Fechar</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detalhesModal');
    const modalBody = document.getElementById('modalBody');
    const closeModalBtns = [document.getElementById('modalCloseBtn'), document.getElementById('modalFooterCloseBtn')];

    const closeModal = () => {
        modal.style.display = 'none';
        modalBody.innerHTML = ''; // Limpa o conteúdo ao fechar
    };

    closeModalBtns.forEach(btn => btn.addEventListener('click', closeModal));

    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            closeModal();
        }
    });

    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            // Simplificado para usar os dados já presentes na linha, evitando o erro de fetch.
            const data = this.dataset.data;
            const paciente = this.dataset.paciente;
            const procedimentos = this.dataset.procedimentos;
            const dentista = this.dataset.dentista;
            const valor = this.dataset.valor;
            const taxaCartao = this.dataset.taxaCartao;
            const custoAuxiliar = this.dataset.custoAuxiliar;
            const comissaoDentista = this.dataset.comissaoDentista;
            const valorBruto = this.dataset.bruto;
            const arquivo = this.dataset.arquivo;
            const isAdmin = <?= is_admin() ? 'true' : 'false' ?>;
            const baseUrl = '<?= BASE_URL ?>';
            
            // Monta o HTML do modal com formato de formulário (campos de leitura)
            let html = `
                <div class="form-group">
                    <label>Data do Atendimento</label>
                    <input type="text" value="${data}" readonly>
                </div>
                <div class="form-group">
                    <label>Paciente</label>
                    <input type="text" value="${paciente}" readonly>
                </div>
                <div class="form-group">
                    <label>Procedimentos</label>
                    <textarea readonly rows="3">${procedimentos}</textarea>
                </div>
                <div class="form-group">
                    <label>Dentista</label>
                    <input type="text" value="${dentista}" readonly>
                </div>
            `;

            if (isAdmin) {
                html += `
                    <div class="form-group">
                        <label>Valor Bruto</label>
                        <input type="text" value="${valorBruto}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Taxa do Cartão</label>
                        <input type="text" value="${taxaCartao}" readonly>
                    </div>
                `;
            }

            html += `
                <div class="form-group">
                    <label>Custo Auxiliar</label>
                    <input type="text" value="${custoAuxiliar}" readonly>
                </div>
                <div class="form-group">
                    <label>Comissão do Dentista</label>
                    <input type="text" value="${comissaoDentista}" readonly>
                </div>
            `;

            if (isAdmin) {
                html += `
                    <div class="form-group">
                        <label>Valor Líquido (Clínica)</label>
                        <input type="text" value="${valor}" readonly>
                    </div>
                `;
            }

            if (arquivo) {
                html += `
                    <div class="form-group" style="margin-top: 1rem; border-top: 1px solid #eee; padding-top: 1rem;">
                        <label>Arquivo Anexado</label>
                        <div style="display: flex; gap: 10px;">
                            <a href="${baseUrl}${arquivo}" target="_blank" class="btn btn-info" style="display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
                                <span style="font-size: 1.2em;">👁</span> Visualizar
                            </a>
                            <a href="${baseUrl}${arquivo}" download class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
                                <span style="font-size: 1.2em;">⬇</span> Download
                            </a>
                        </div>
                    </div>
                `;
            }

            modalBody.innerHTML = html;
            modal.style.display = 'block';
        });
    });
});
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
buscaLiveTable({ inputId: 'busca_painel', tableId: 'tabela_painel' });
</script>
