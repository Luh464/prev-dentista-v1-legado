<div id="toast-notification" class="toast"></div>
<style>
    .toast { position: fixed; top: 20px; right: 20px; padding: 15px 20px; border-radius: 5px; color: white; font-size: 16px; z-index: 9999; opacity: 0; visibility: hidden; transition: opacity 0.5s, visibility 0.5s, transform 0.5s; transform: translateX(100%); }
    .toast.show { opacity: 1; visibility: visible; transform: translateX(0); }
    .toast.error { background-color: #dc3545; } /* red */
    .toast.success { background-color: #28a745; } /* green */
</style>
<div class="card">
    <h2>Confirmar Pagamento</h2>

    <fieldset style="border:1px solid #ddd; border-radius:8px; padding:1rem; margin-bottom:1.5rem;">
        <legend style="font-weight:bold; padding:0 8px;">Buscar Paciente</legend>
        <div class="form-group busca-wrapper" style="margin-bottom:0;">
            <label for="paciente_busca_odonto">Paciente</label>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <input type="text" id="paciente_busca_odonto"
                       placeholder="Digite o nome para buscar..."
                       autocomplete="off" style="flex-grow:1;"
                       value="<?= htmlspecialchars($paciente_nome ?? '') ?>"
                       oninput="buscaOdonto(this.value)">
                <?php if (!empty($paciente_nome)): ?>
                <a href="<?= BASE_URL ?>?rota=atendimentos.pagamento" class="btn btn-danger">✕ Limpar</a>
                <?php endif; ?>
            </div>
            <ul id="drop_odonto" class="busca-dropdown"></ul>
            <span id="status_odonto" class="busca-status">
                <?= !empty($paciente_nome) ? ('✓ Paciente: <strong>'.htmlspecialchars($paciente_nome).'</strong>') : 'Digite o nome para buscar o paciente.' ?>
            </span>
        </div>
    </fieldset>


    <?php if ($paciente_id && $paciente_nome): ?>

        <?php if (!$ultimo_atendimento_id): ?>
            <div class="card" style="border-left: 4px solid #e67e22; padding: 1rem; margin-top:1rem;">
                <strong style="color:#e67e22;">⚠ Nenhum atendimento encontrado</strong>
                <p>O paciente <strong><?= htmlspecialchars($paciente_nome) ?></strong> não possui atendimentos registrados. Faça um lançamento primeiro.</p>
                <a href="<?= BASE_URL ?>?rota=atendimentos.novo" class="btn btn-primary" style="margin-top:0.5rem;">Lançar Atendimento</a>
            </div>
        <?php else: ?>

        <form id="form-pagamento" action="<?= BASE_URL ?>?rota=atendimentos.pagar" method="POST">
            <input type="hidden" name="paciente_id"    value="<?= (int)($paciente_id ?? 0) ?>">
            <input type="hidden" name="atendimento_id" value="<?= (int)($ultimo_atendimento_id ?? 0) ?>">

            <div class="card" style="margin-top:1rem;">
                <h3 style="margin-bottom:0.8rem;">📋 Procedimentos do Atendimento</h3>

                <?php if (!empty($atendimentos)): ?>
                <table class="mobile-card-table">
                    <thead><tr><th>Procedimento</th><th>Qtd</th><th style="text-align:right;">Valor</th></tr></thead>
                    <tbody>
                        <?php foreach ($atendimentos as $at): ?>
                        <tr>
                            <td><?= htmlspecialchars($at['nome']) ?></td>
                            <td><?= (int)$at['quantidade'] ?>x</td>
                            <td style="text-align:right; font-weight:600;">R$ <?= number_format($at['valor_procedimento'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="color:#888; padding:1rem 0;">Nenhum procedimento listado. O valor total será informado manualmente.</p>
                <?php endif; ?>

                <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                    <strong>Valor Total do Atendimento:</strong>
                    <span style="font-size:1.3rem; font-weight:700; color:#005b96;">
                        R$ <?= number_format($valor_total, 2, ',', '.') ?>
                    </span>
                </div>

                <input type="hidden" id="valor_total_hidden" value="<?= number_format($valor_total, 2, '.', '') ?>">
            </div>

            <div class="card" style="margin-top:1rem;">
                <h3 style="margin-bottom:0.8rem;">💳 Formas de Pagamento</h3>
                <p style="color:#888; font-size:13px; margin-bottom:1rem;">
                    Distribua o valor entre uma ou mais formas de pagamento. A soma deve ser igual ao total.
                </p>

                <div id="pagamentos_container"></div>

                <button type="button" id="add_pagamento" class="btn btn-secondary" style="margin-top:0.5rem;">
                    + Adicionar Forma de Pagamento
                </button>

                <div style="margin-top:1rem; padding:0.8rem; background:#f8fafc; border-radius:6px; display:flex; gap:2rem; flex-wrap:wrap;">
                    <span>Total informado: <strong id="total_pago" style="color:#005b96;">R$ 0,00</strong></span>
                    <span>Restante: <strong id="restante_pagar" style="color:#e74c3c;">R$ 0,00</strong></span>
                </div>
            </div>

            <button type="submit" class="btn btn-success" style="width:100%; margin-top:1rem; padding:14px; font-size:16px;">
                ✓ Confirmar Pagamento
            </button>
        </form>

        <?php endif; ?>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
    // Autocomplete for patient search remains the same
    // busca de paciente via dropdown nativo (buscaOdonto)

    // ── Sistema de pagamento ─────────────────────────────────────────────────
    var container   = document.getElementById('pagamentos_container');
    var addBtn      = document.getElementById('add_pagamento');
    var totalSpan   = document.getElementById('total_pago');
    var restanteSpan = document.getElementById('restante_pagar');
    var valorTotal  = parseFloat(document.getElementById('valor_total_hidden')?.value || 0);

    if (!container) { /* Sem form de pagamento nesta renderização */ return; }

    var formasLabel = { dinheiro:'💵 Dinheiro', pix:'⚡ PIX', debito:'💳 Débito', credito:'💳 Crédito' };

    function criarLinhaPagamento(valorInicial) {
        var row = document.createElement('div');
        row.className = 'pagamento-row';
        row.style.cssText = 'display:flex; gap:8px; margin-bottom:10px; flex-wrap:wrap; align-items:center;';

        var sel = document.createElement('select');
        sel.name = 'pagamentos[forma][]';
        sel.style.cssText = 'flex:1; min-width:140px; padding:8px; border:1px solid #ccc; border-radius:6px;';
        Object.entries(formasLabel).forEach(function(e) {
            var o = document.createElement('option');
            o.value = e[0]; o.textContent = e[1];
            sel.appendChild(o);
        });

        var inp = document.createElement('input');
        inp.type = 'text';
        inp.name = 'pagamentos[valor][]';
        inp.placeholder = 'Valor (R$)';
        inp.style.cssText = 'flex:1; min-width:120px; padding:8px; border:1px solid #ccc; border-radius:6px;';
        inp.value = valorInicial ? valorInicial.toFixed(2).replace('.', ',') : '';

        var parcSel = document.createElement('select');
        parcSel.name = 'pagamentos[parcelas][]';
        parcSel.style.cssText = 'display:none; padding:8px; border:1px solid #ccc; border-radius:6px;';
        for (var i = 1; i <= 12; i++) {
            var o2 = document.createElement('option');
            o2.value = i; o2.textContent = i + 'x';
            parcSel.appendChild(o2);
        }

        var rem = document.createElement('button');
        rem.type = 'button';
        rem.textContent = '✕';
        rem.className = 'btn btn-danger';
        rem.style.padding = '8px 12px';
        rem.onclick = function() { row.remove(); recalcular(); };

        sel.addEventListener('change', function() {
            parcSel.style.display = sel.value === 'credito' ? 'inline-block' : 'none';
        });
        inp.addEventListener('input', recalcular);

        row.appendChild(sel);
        row.appendChild(inp);
        row.appendChild(parcSel);
        row.appendChild(rem);
        return row;
    }

    function recalcular() {
        var total = 0;
        document.querySelectorAll('input[name="pagamentos[valor][]"]').forEach(function(i) {
            var v = parseFloat(i.value.replace(',', '.'));
            if (!isNaN(v)) total += v;
        });
        var restante = valorTotal - total;
        if (totalSpan)    totalSpan.textContent    = 'R$ ' + total.toFixed(2).replace('.', ',');
        if (restanteSpan) {
            restanteSpan.textContent = 'R$ ' + Math.abs(restante).toFixed(2).replace('.', ',');
            restanteSpan.style.color = Math.abs(restante) < 0.01 ? '#27ae60' : '#e74c3c';
        }
    }

    if (addBtn) {
        addBtn.addEventListener('click', function() {
            // Sugerir o restante automaticamente
            var total = 0;
            document.querySelectorAll('input[name="pagamentos[valor][]"]').forEach(function(i) {
                var v = parseFloat(i.value.replace(',', '.'));
                if (!isNaN(v)) total += v;
            });
            var restante = Math.max(0, valorTotal - total);
            container.appendChild(criarLinhaPagamento(restante > 0 ? restante : null));
            recalcular();
        });
    }

    // Criar primeira linha automaticamente com o valor total pré-preenchido
    if (container) {
        container.appendChild(criarLinhaPagamento(valorTotal > 0 ? valorTotal : null));
        recalcular();
    }

    // Submit do form
    var formPag = document.getElementById('form-pagamento');
    if (formPag) {
        formPag.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = formPag.querySelector('button[type="submit"]');

            // Validar soma
            var totalPago = 0;
            document.querySelectorAll('input[name="pagamentos[valor][]"]').forEach(function(i) {
                var v = parseFloat(i.value.replace(',', '.'));
                if (!isNaN(v)) totalPago += v;
            });

            if (valorTotal > 0 && Math.abs(totalPago - valorTotal) > 0.01) {
                showToast('A soma dos pagamentos (R$ ' + totalPago.toFixed(2).replace('.',',') + ') deve ser igual ao total (R$ ' + valorTotal.toFixed(2).replace('.',',') + ').', 'error');
                return;
            }
            if (totalPago <= 0) {
                showToast('Informe pelo menos um valor de pagamento.', 'error');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Processando...';

            // Normalizar vírgulas para pontos nos valores
            document.querySelectorAll('input[name="pagamentos[valor][]"]').forEach(function(i) {
                i.value = i.value.replace(',', '.');
            });

            var fd = new FormData(formPag);

            fetch(formPag.getAttribute('action'), { method: 'POST', body: fd })
                .then(function(r) {
                    return r.text();
                })
                .then(function(txt) {
                    var data;
                    try { data = JSON.parse(txt); }
                    catch(ex) { throw new Error('Resposta inválida do servidor: ' + txt.substring(0, 200)); }

                    if (data.sucesso) {
                        showToast(data.mensagem || 'Pagamento confirmado!', 'success');
                        setTimeout(function() {
                            window.location.href = window.__BASE_URL + '?rota=painel';
                        }, 1500);
                    } else {
                        showToast(data.erro || 'Erro desconhecido.', 'error');
                        btn.disabled = false;
                        btn.textContent = '✓ Confirmar Pagamento';
                    }
                })
                .catch(function(err) {
                    console.error('Pagamento erro:', err);
                    showToast('Erro de comunicação: ' + err.message, 'error');
                    btn.disabled = false;
                    btn.textContent = '✓ Confirmar Pagamento';
                });
        });
    }

    function showToast(message, type = 'success') {
        const toast = $('#toast-notification');
        
        toast.text(message).removeClass('success error').addClass(type).addClass('show');
        
        setTimeout(() => {
            toast.removeClass('show');
        }, 5000);
    }
});
</script>

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
var _tOdonto = null;
function buscaOdonto(term) {
    var lista  = document.getElementById('drop_odonto');
    var status = document.getElementById('status_odonto');
    clearTimeout(_tOdonto);
    lista.innerHTML = ''; lista.style.display = 'none';
    if (!term) { status.innerHTML = 'Digite o nome para buscar o paciente.'; return; }
    status.innerHTML = 'Buscando...';
    _tOdonto = setTimeout(function() {
        fetch(window.__BASE_URL + 'ajax/buscar_paciente.php?term=' + encodeURIComponent(term))
            .then(function(r){ return r.json(); })
            .then(function(data) {
                lista.innerHTML = '';
                if (!data.length) { status.innerHTML = 'Nenhum paciente encontrado.'; return; }
                data.forEach(function(p) {
                    var li = document.createElement('li');
                    li.style.cssText = 'padding:10px 14px;cursor:pointer;border-bottom:1px solid #eee;font-size:14px;';
                    li.innerHTML = '<strong>' + (p.nome||'').replace(/</g,'&lt;') + '</strong>'
                        + (p.cpf ? ' <span style="color:#999;font-size:12px;"> · CPF: '+p.cpf+'</span>' : '');
                    li.onmouseover = function(){ this.style.background='#f0f4ff'; };
                    li.onmouseout  = function(){ this.style.background=''; };
                    li.onmousedown = function(e) {
                        e.preventDefault();
                        window.location.href = window.__BASE_URL + '?rota=atendimentos.pagamento&paciente_id=' + p.id + '&paciente_nome=' + encodeURIComponent(p.nome);
                    };
                    lista.appendChild(li);
                });
                lista.style.display = 'block';
                status.innerHTML = data.length + ' encontrado(s). Clique para confirmar pagamento.';
            })
            .catch(function(){ status.innerHTML = 'Erro na busca.'; });
    }, 220);
}
document.addEventListener('click', function(e) {
    var lista = document.getElementById('drop_odonto');
    var input = document.getElementById('paciente_busca_odonto');
    if (lista && input && !input.contains(e.target) && !lista.contains(e.target))
        lista.style.display = 'none';
});
// Remover o autocomplete jQuery UI herdado se ainda existir
if (typeof $ !== 'undefined' && $('#paciente_busca_odonto').autocomplete)
    try { $('#paciente_busca_odonto').autocomplete('destroy'); } catch(e) {}
</script>
