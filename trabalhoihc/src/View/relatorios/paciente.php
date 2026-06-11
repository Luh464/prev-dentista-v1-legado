<div class="card">
    <h2>Relatório por Paciente</h2>

    <div class="card" style="margin-top:1rem;">
        <div class="form-group" style="position:relative; margin-bottom:0;">
            <label for="busca_pac_rel"><strong>Buscar Paciente</strong></label>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <input type="text" id="busca_pac_rel"
                       value="<?= htmlspecialchars($paciente_nome) ?>"
                       placeholder="Digite qualquer letra para buscar..."
                       autocomplete="off" style="flex-grow:1; padding:10px; border:1px solid #ccc; border-radius:6px;"
                       oninput="buscaPacRel(this.value)">
                <?php if ($paciente_nome): ?>
                <a href="<?= BASE_URL ?>?rota=relatorios.paciente" class="btn btn-danger" style="white-space:nowrap;">✕ Limpar</a>
                <?php endif; ?>
            </div>
            <ul id="lista_pac_rel" style="
                display:none; position:absolute; top:100%; left:0; right:0;
                background:#fff; border:1px solid #ccc; border-radius:4px;
                max-height:240px; overflow-y:auto; margin:2px 0 0; padding:0;
                list-style:none; z-index:99999; box-shadow:0 4px 12px rgba(0,0,0,.15);
            "></ul>
            <small id="status_pac_rel" style="color:#888; margin-top:4px; display:block;">
                <?php if ($paciente_nome && $paciente): ?>
                    ✓ Exibindo dados de <strong><?= htmlspecialchars($paciente['nome']) ?></strong>
                <?php elseif ($paciente_nome): ?>
                    Nenhum paciente encontrado com esse nome.
                <?php else: ?>
                    Digite o nome do paciente para buscar.
                <?php endif; ?>
            </small>
        </div>
    </div>

    <script>
    (function() {
        var _t = null;
        window.buscaPacRel = function(term) {
            var lista  = document.getElementById('lista_pac_rel');
            var status = document.getElementById('status_pac_rel');
            clearTimeout(_t);
            lista.innerHTML = ''; lista.style.display = 'none';
            if (!term) { status.innerHTML = 'Digite o nome do paciente para buscar.'; return; }
            status.innerHTML = 'Buscando...';
            _t = setTimeout(function() {
                fetch(window.__BASE_URL + 'ajax/buscar_paciente.php?term=' + encodeURIComponent(term))
                    .then(function(r){ return r.json(); })
                    .then(function(data) {
                        lista.innerHTML = '';
                        if (!data.length) {
                            status.innerHTML = 'Nenhum paciente encontrado.';
                            return;
                        }
                        data.forEach(function(p) {
                            var li = document.createElement('li');
                            li.style.cssText = 'padding:10px 14px;cursor:pointer;border-bottom:1px solid #eee;font-size:14px;';
                            li.innerHTML = '<strong>' + (p.nome||'').replace(/</g,'&lt;') + '</strong>'
                                + (p.cpf ? ' <span style="color:#999;font-size:12px;"> · CPF: ' + p.cpf + '</span>' : '')
                                + (p.telefone ? ' <span style="color:#999;font-size:12px;"> · ' + p.telefone + '</span>' : '');
                            li.onmouseover = function(){ this.style.background='#f0f4ff'; };
                            li.onmouseout  = function(){ this.style.background=''; };
                            li.onmousedown = function(e) {
                                e.preventDefault();
                                window.location.href = window.__BASE_URL + '?rota=relatorios.paciente&paciente_nome=' + encodeURIComponent(p.nome);
                            };
                            lista.appendChild(li);
                        });
                        lista.style.display = 'block';
                        status.innerHTML = data.length + ' resultado(s). Clique para ver o relatório.';
                    })
                    .catch(function(err){ status.innerHTML = 'Sem conexão com o servidor.'; console.error('Busca AJAX erro:', err); });
            }, 250);
        };
        document.addEventListener('click', function(e) {
            var lista = document.getElementById('lista_pac_rel');
            var campo = document.getElementById('busca_pac_rel');
            if (lista && campo && !campo.contains(e.target)) lista.style.display = 'none';
        });
    })();
    </script>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'upload_sucesso'): ?>
        <p style="color: green; background: #e8f5e9; padding: 1rem; border-radius: 6px;">Arquivo enviado com sucesso!</p>
    <?php elseif (isset($_GET['erro'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['erro']) ?></p>
    <?php endif; ?>

    <?php if ($paciente): ?>
        <div style="margin-top: 2rem; text-align: center;">
            <h3>Odontograma de <?= htmlspecialchars($paciente['nome']) ?></h3>
            
            <div class="canvas-container">
                <img src="<?= BASE_URL ?>assets/img/odontograma.png" usemap="#image-map" class="img-odontograma" id="odontograma-img">
                
                <svg class="odontograma-overlay" id="odontograma-svg"></svg>
                
                <map name="image-map" id="image-map">
                    <area data-dente="18" target="" onclick="abrirModal(18, 'Arcada Superior')" alt="Molar 18" id="d18" title="3º Molar" coords="53,251,99,157" shape="rect">
                    <area data-dente="17" target="" onclick="abrirModal(17, 'Arcada Superior')" alt="Molar 17" id="d17" title="2º Molar" coords="147,156,103,249" shape="rect">
                    <area data-dente="16" target="" onclick="abrirModal(16, 'Arcada Superior')" alt="Molar 16" title="Molar 16" coords="207,155,151,246" shape="rect">
                    <area data-dente="15" target="" onclick="abrirModal(15, 'Arcada Superior')" alt="Premolar 15" title="Premolar 15" coords="241,152,209,242" shape="rect">
                    <area data-dente="14" target="" onclick="abrirModal(14, 'Arcada Superior')" alt="Premolar 14" title="Premolar 14" coords="274,149,246,241" shape="rect">
                    <area data-dente="13" target="" onclick="abrirModal(13, 'Arcada Superior')" alt="Canino 13" title="Canino 13" coords="314,148,277,238" shape="rect">
                    <area data-dente="12" target="" onclick="abrirModal(12, 'Arcada Superior')" alt="Inciso 12" title="Inciso 12" coords="352,152,317,243" shape="rect">
                    <area data-dente="11" target="" onclick="abrirModal(11, 'Arcada Superior')" alt="Inciso 11" title="Inciso 11" coords="397,153,355,246" shape="rect">
                    <area data-dente="21" target="" onclick="abrirModal(21, 'Arcada Superior')" alt="Inciso 21" title="Inciso 21" coords="442,154,403,244" shape="rect">
                    <area data-dente="22" target="" onclick="abrirModal(22, 'Arcada Superior')" alt="Inciso 22" title="Inciso 22" coords="479,153,446,243" shape="rect">
                    <area data-dente="23" target="" onclick="abrirModal(23, 'Arcada Superior')" alt="Canino 23" title="Canino 23" coords="521,142,481,243" shape="rect">
                    <area data-dente="24" target="" onclick="abrirModal(24, 'Arcada Superior')" alt="Premolar 24" title="Premolar 24" coords="561,146,525,239" shape="rect">
                    <area data-dente="25" target="" onclick="abrirModal(25, 'Arcada Superior')" alt="Premolar 25" title="Premolar 25" coords="590,146,564,237" shape="rect">
                    <area data-dente="26" target="" onclick="abrirModal(26, 'Arcada Superior')" alt="Molar 26" title="Molar 26" coords="648,148,593,238" shape="rect">
                    <area data-dente="27" target="" onclick="abrirModal(27, 'Arcada Superior')" alt="Molar 27" title="Molar 27" coords="703,151,653,239" shape="rect">
                    <area data-dente="28" target="" onclick="abrirModal(28, 'Arcada Superior')" alt="Molar 28" id="d28" title="3º Molar" coords="741,149,705,241" shape="rect">

                    <area data-dente="48" target="" onclick="abrirModal(48, 'Arcada Inferior')" alt="Molar 48" id="d48" title="Molar 48" coords="51,285,103,360" shape="rect">
                    <area data-dente="47" target="" onclick="abrirModal(47, 'Arcada Inferior')" alt="Molar 47" title="Molar 47" coords="109,284,160,363" shape="rect">
                    <area data-dente="46" target="" onclick="abrirModal(46, 'Arcada Inferior')" alt="Molar 46" title="Molar 46" coords="167,281,219,363" shape="rect">
                    <area data-dente="45" target="" onclick="abrirModal(45, 'Arcada Inferior')" alt="Premolar 45" title="Premolar 45" coords="221,278,258,378" shape="rect">
                    <area data-dente="44" target="" onclick="abrirModal(44, 'Arcada Inferior')" alt="Premolar 44" title="Premolar 44" coords="260,275,296,390" shape="rect">
                    <area data-dente="43" target="" onclick="abrirModal(43, 'Arcada Inferior')" alt="Canino 43" title="Canino 43" coords="298,276,336,384" shape="rect">
                    <area data-dente="42" target="" onclick="abrirModal(42, 'Arcada Inferior')" alt="Inciso 42" title="Inciso 42" coords="338,275,368,384" shape="rect">
                    <area data-dente="41" target="" onclick="abrirModal(41, 'Arcada Inferior')" alt="Inciso 41" title="Inciso 41" coords="370,276,395,383" shape="rect">
                    <area data-dente="31" target="" onclick="abrirModal(31, 'Arcada Inferior')" alt="Inciso 31" title="Inciso 31" coords="398,275,426,380" shape="rect">
                    <area data-dente="32" target="" onclick="abrirModal(32, 'Arcada Inferior')" alt="Inciso 32" title="Inciso 32" coords="428,275,454,382" shape="rect">
                    <area data-dente="33" target="" onclick="abrirModal(33, 'Arcada Inferior')" alt="Canino 33" title="Canino 33" coords="456,274,493,391" shape="rect">
                    <area data-dente="34" target="" onclick="abrirModal(34, 'Arcada Inferior')" alt="Premolar 34" title="Premolar 34" coords="496,274,531,383" shape="rect">
                    <area data-dente="35" target="" onclick="abrirModal(35, 'Arcada Inferior')" alt="Premolar 35" title="Premolar 35" coords="534,274,571,379" shape="rect">
                    <area data-dente="36" target="" onclick="abrirModal(36, 'Arcada Inferior')" alt="Molar 36" title="Molar 36" coords="575,274,636,384" shape="rect">
                    <area data-dente="37" target="" onclick="abrirModal(37, 'Arcada Inferior')" alt="Molar 37" title="Molar 37" coords="640,274,688,384" shape="rect">
                    <area data-dente="38" target="" onclick="abrirModal(38, 'Arcada Inferior')" alt="Molar 38" title="Molar 38" coords="694,272,742,375" shape="rect">

                    <area data-dente="Todos" target="" onclick="abrirModal('Todos', 'Geral')" alt="Todos" title="Todos" coords="85,31,727,83" shape="rect">
                    <area data-dente="Todos" target="" onclick="abrirModal('Todos', 'Geral')" alt="Todos" title="Todos" coords="72,449,727,498" shape="rect">
                </map>
            </div>
        </div>

       <div style="margin-top: 2rem;">
            <h3>Histórico de Procedimentos</h3>

            <?php if (empty($procedimentos)): ?>
                <p style="text-align: center; margin-top: 1rem;">Nenhum procedimento encontrado para este paciente.</p>
            <?php else: ?>

                <?php if (!empty($procedimentos_todos)): ?>
                    <div class="card" style="margin-top: 1rem; border-left: 5px solid var(--primary-color);">
                        <h4>Tratamentos Gerais (Todos)</h4>
                        <?php foreach($procedimentos_todos as $proc): ?>
                            <div class="sub-card">
                                <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($proc['data_atendimento'])) ?></p>
                                <p><strong>Procedimento:</strong> <?= htmlspecialchars($proc['procedimento_nome']) ?></p>
                                <p><strong>Descrição:</strong> <?= htmlspecialchars($proc['descricao'] ?: 'N/A') ?></p>
                                <p><strong>Status Execução:</strong> <span class="status-badge status-<?= strtolower($proc['status_execucao']) ?>"><?= htmlspecialchars(ucfirst($proc['status_execucao'])) ?></span></p>
                                <p><strong>Status Pagamento:</strong> <span class="status-badge status-<?= strtolower($proc['status_pagamento']) ?>"><?= htmlspecialchars(ucfirst($proc['status_pagamento'])) ?></span></p>
                                <div class="arquivo-container" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee; display: flex; align-items: center; gap: 10px;">
                                    <?php if (!empty($proc['url_arquivo'])): ?>
                                        <strong>Arquivo:</strong>
                                        <a href="<?= BASE_URL . htmlspecialchars($proc['url_arquivo']) ?>" target="_blank" class="btn btn-info btn-sm" style="padding: 5px 10px;">Visualizar</a>
                                        <a href="<?= BASE_URL . htmlspecialchars($proc['url_arquivo']) ?>" download class="btn btn-secondary btn-sm" style="padding: 5px 10px;">Baixar</a>
                                        <button type="button" class="btn btn-danger btn-sm btn-remover-anexo" data-id-procedimento="<?= $proc['atendimento_procedimento_id'] ?>">Remover</button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-primary btn-sm" style="padding: 5px 10px;" onclick="abrirModalUpload(<?= $proc['atendimento_procedimento_id'] ?>)">Anexar Arquivo</button>
                                    <?php endif; ?>
                                </div>
                                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee; text-align: right;">
                                    <button type="button" class="btn btn-danger btn-sm btn-remover-procedimento" 
                                            data-id-procedimento="<?= $proc['atendimento_procedimento_id'] ?>"
                                            data-status-execucao="<?= htmlspecialchars($proc['status_execucao']) ?>"
                                            data-status-pagamento="<?= htmlspecialchars($proc['status_pagamento']) ?>">
                                        Remover Procedimento
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php foreach($procedimentos_agrupados as $local => $procs_dente): ?>
                    <div class="card" style="margin-top: 1rem; border-left: 5px solid var(--secondary-color);">
                        <h4>Dente <?= htmlspecialchars($local) ?></h4>
                        <?php foreach($procs_dente as $proc): ?>
                            <div class="sub-card">
                                <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($proc['data_atendimento'])) ?></p>
                                <p><strong>Procedimento:</strong> <?= htmlspecialchars($proc['procedimento_nome']) ?></p>
                                <p><strong>Descrição:</strong> <?= htmlspecialchars($proc['descricao'] ?: 'N/A') ?></p>
                                <p><strong>Status Execução:</strong> <span class="status-badge status-<?= strtolower($proc['status_execucao']) ?>"><?= htmlspecialchars(ucfirst($proc['status_execucao'])) ?></span></p>
                                <p><strong>Status Pagamento:</strong> <span class="status-badge status-<?= strtolower($proc['status_pagamento']) ?>"><?= htmlspecialchars(ucfirst($proc['status_pagamento'])) ?></span></p>
                                <div class="arquivo-container" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee; display: flex; align-items: center; gap: 10px;">
                                    <?php if (!empty($proc['url_arquivo'])): ?>
                                        <strong>Arquivo:</strong>
                                        <a href="<?= BASE_URL . htmlspecialchars($proc['url_arquivo']) ?>" target="_blank" class="btn btn-info btn-sm" style="padding: 5px 10px;">Visualizar</a>
                                        <a href="<?= BASE_URL . htmlspecialchars($proc['url_arquivo']) ?>" download class="btn btn-secondary btn-sm" style="padding: 5px 10px;">Baixar</a>
                                        <button type="button" class="btn btn-danger btn-sm btn-remover-anexo" data-id-procedimento="<?= $proc['atendimento_procedimento_id'] ?>">Remover</button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-primary btn-sm" style="padding: 5px 10px;" onclick="abrirModalUpload(<?= $proc['atendimento_procedimento_id'] ?>)">Anexar Arquivo</button>
                                    <?php endif; ?>
                                </div>
                                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee; text-align: right;">
                                    <button type="button" class="btn btn-danger btn-sm btn-remover-procedimento" 
                                            data-id-procedimento="<?= $proc['atendimento_procedimento_id'] ?>"
                                            data-status-execucao="<?= htmlspecialchars($proc['status_execucao']) ?>"
                                            data-status-pagamento="<?= htmlspecialchars($proc['status_pagamento']) ?>">
                                        Remover Procedimento
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

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

    <?php elseif (isset($_GET['paciente_nome'])): ?>
        <p style="margin-top: 2rem;">Nenhum paciente encontrado com o nome "<?= htmlspecialchars($paciente_nome) ?>".</p>
    <?php endif; ?>
</div>

<div id="modalTratamento" class="modal">
    <div class="modal-content">
        <h3 id="modal-title"></h3>
        <div id="modal-body" class="modal-body-content"></div>
        <div class="btn-group" style="margin-top: 1rem;">
            <button type="button" onclick="fecharModal()" class="btn-secondary">Fechar</button>
        </div>
    </div>
</div>

<div id="modalUpload" class="modal">
    <div class="modal-content">
        <h3>Anexar Arquivo ao Procedimento</h3>
        <form id="form-upload-arquivo" action="<?= BASE_URL ?>?rota=atendimentos.salvarArq" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="atendimento_procedimento_id" id="upload_atendimento_procedimento_id">
            <input type="hidden" name="paciente_nome_redirect" value="<?= htmlspecialchars($paciente_nome) ?>">
            <div class="form-group">
                <label for="arquivo_procedimento">Selecione o arquivo (PDF, JPG, PNG)</label>
                <input type="file" name="arquivo_procedimento" id="arquivo_procedimento" accept=".pdf,image/jpeg,image/png" required>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn-success">Enviar</button>
                <button type="button" onclick="fecharModalUpload()" class="btn-danger">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div id="modalConfirm" class="modal">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <h3 id="confirm-title" style="margin-top: 0; color: var(--text-color);">Confirmação</h3>
        <p id="confirm-message" style="font-size: 1.1rem; color: #555; margin: 20px 0;">Tem certeza?</p>
        <div class="btn-group" style="justify-content: center; margin-top: 20px;">
            <button id="btn-confirm-yes" class="btn btn-danger">Sim, confirmar</button>
            <button type="button" onclick="fecharModalConfirm()" class="btn btn-secondary">Cancelar</button>
        </div>
    </div>
</div>

<!-- Container para Notificações Toast -->
<div id="toast-container" class="toast-container"></div>

<style>
    /* CSS ATUALIZADO PARA RESPONSIVIDADE COM SVG */
    .canvas-container {
        position: relative;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        line-height: 0;
    }

    .img-odontograma {
        display: block;
        width: 100%;
        height: auto;
        position: relative;
        z-index: 1;
    }

    .odontograma-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
        pointer-events: none; /* Permite clicar no mapa abaixo */
    }

    area {
        cursor: pointer;
        outline: none;
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background-color: white;
        padding: 25px;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .modal-body-content {
        overflow-y: auto;
        margin-top: 1rem;
        padding-right: 10px;
    }
    .btn-group {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }



    .sub-card {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .sub-card:last-child {
        margin-bottom: 0;
    }
    .sub-card p {
        margin: 0 0 0.5rem 0;
    }
    .sub-card p:last-child {
        margin-bottom: 0;
    }

    .status-badge {
        padding: 0.2em 0.6em;
        border-radius: 10px;
        font-size: 0.8em;
        font-weight: bold;
        color: white;
    }
    .status-feito, .status-pago { background-color: var(--success-color); }
    .status-pendente { background-color: var(--warning-color); color: #333; }
    .status-finalizado { background-color: var(--info-color); }
    .status-nao_aplicavel { background-color: var(--secondary-color); }

    /* Toast Notification Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10001;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .toast {
        background-color: #fff;
        padding: 15px 20px;
        border-radius: 8px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        min-width: 300px;
        font-weight: 500;
    }
    .toast.show { opacity: 1; transform: translateX(0); }
    .toast.success { color: #155724; border-left: 6px solid #28a745; }
    .toast.error { color: #721c24; border-left: 6px solid #dc3545; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/image-map-resizer/1.0.10/js/imageMapResizer.min.js"></script>

<script>
    // Funções do Modal (Escopo Global)
     const modal = document.getElementById('modalTratamento');
     const modalTitle = document.getElementById('modal-title');
     const modalBody = document.getElementById('modal-body');
     const modalUpload = document.getElementById('modalUpload');
     const modalConfirm = document.getElementById('modalConfirm');

    <?php if ($paciente): ?>
    const procedimentosPorLocal = <?= json_encode($procedimentos_agrupados ?? []) ?>;
    procedimentosPorLocal['Todos'] = <?= json_encode($procedimentos_todos ?? []) ?>;
    const denteStatus = <?= json_encode($dente_status_color ?? []) ?>;
    <?php endif; ?>

    function formatStatus(status) {
        if (!status) return 'N/A';
        const formatted = status.replace(/_/g, ' ');
        return formatted.charAt(0).toUpperCase() + formatted.slice(1);
    }

    function abrirModal(numero, arcada) {
        modal.classList.add('show');
        modalBody.innerHTML = ''; 

        const titulo = (arcada === 'Geral') ? 'Tratamentos Gerais' : `${arcada} - Dente ${numero}`;
        modalTitle.innerText = titulo;

        if (typeof procedimentosPorLocal !== 'undefined') {
            const procedimentos = procedimentosPorLocal[numero] || [];

            if (procedimentos.length > 0) {
                procedimentos.forEach(proc => {
                    const data = new Date(proc.data_atendimento);
                    const dataFormatada = data.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' + data.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

                    const procHtml = `
                        <div class="sub-card">
                            <p><strong>Data:</strong> ${dataFormatada}</p>
                            <p><strong>Procedimento:</strong> ${proc.procedimento_nome || ''}</p>
                            <p><strong>Descrição:</strong> ${proc.descricao || 'N/A'}</p>
                            <p><strong>Status Execução:</strong> <span class="status-badge status-${proc.status_execucao.toLowerCase()}">${formatStatus(proc.status_execucao)}</span></p>
                            <p><strong>Status Pagamento:</strong> <span class="status-badge status-${proc.status_pagamento.toLowerCase()}">${formatStatus(proc.status_pagamento)}</span></p>
                        </div>
                    `;
                    modalBody.innerHTML += procHtml;
                });
            } else {
                modalBody.innerHTML = '<p>Nenhum procedimento registrado para este local.</p>';
            }
        } else {
             modalBody.innerHTML = '<p>Nenhum paciente selecionado.</p>';
        }
    }

     function fecharModal() { modal.classList.remove('show'); }

     function abrirModalUpload(atendimentoProcedimentoId) {
        if (modalUpload) {
            document.getElementById('upload_atendimento_procedimento_id').value = atendimentoProcedimentoId;
            modalUpload.classList.add('show');
        }
     }

     function fecharModalUpload() {
        if (modalUpload) { modalUpload.classList.remove('show'); }
     }

     function fecharModalConfirm() {
        if (modalConfirm) { modalConfirm.classList.remove('show'); }
     }

     // Sistema de Toast
     function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerText = message;
        
        container.appendChild(toast);
        
        // Força reflow para animação funcionar
        void toast.offsetWidth; 
        
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Sistema de Confirmação
    let confirmCallback = null;
    function showConfirm(title, message, callback) {
        document.getElementById('confirm-title').innerText = title;
        document.getElementById('confirm-message').innerText = message;
        confirmCallback = callback;
        modalConfirm.classList.add('show');
    }

    document.getElementById('btn-confirm-yes').addEventListener('click', function() {
        if (confirmCallback) confirmCallback();
        fecharModalConfirm();
    });

     window.onclick = function(event) {
         if (event.target == modal) fecharModal();
         if (event.target == modalUpload) fecharModalUpload();
         if (event.target == modalConfirm) fecharModalConfirm();
     }

    // --- FUNÇÕES DE INTERAÇÃO DO ODONTOGRAMA (RESPONSIVIDADE COM SVG) ---
    function initOdontograma() {
        const img = document.getElementById('odontograma-img');
        const svg = document.getElementById('odontograma-svg');
        const map = document.getElementById('image-map');
        
        if (!img || !svg || !map) return;

        const setup = () => {
            // Configura o SVG para usar o sistema de coordenadas da imagem ORIGINAL
            svg.setAttribute('viewBox', `0 0 ${img.naturalWidth} ${img.naturalHeight}`);
            
            // Salva as coordenadas originais ANTES do redimensionamento
            const areas = map.getElementsByTagName('area');
            for (let area of areas) {
                if (!area.dataset.originalCoords) {
                    area.dataset.originalCoords = area.getAttribute('coords');
                }
            }
            
            // Inicia a biblioteca que ajusta os cliques responsivamente
            imageMapResize();
            
            // Desenha as cores no SVG
            drawHighlights();
        };

        if (img.complete) { setup(); } else { img.onload = setup; }
    }

    function drawHighlights() {
        const svg = document.getElementById('odontograma-svg');
        const map = document.getElementById('image-map');
        const colors = {
            red: 'rgba(220, 53, 69, 0.5)',
            green: 'rgba(40, 167, 69, 0.5)',
            yellow: 'rgba(255, 193, 7, 0.5)'
        };

        svg.innerHTML = ''; // Limpa o SVG

        if (typeof denteStatus !== 'undefined') {
            for (const local in denteStatus) {
                const color = denteStatus[local];
                const areas = map.querySelectorAll(`area[data-dente="${local}"]`);

                areas.forEach(area => {
                    // Pega as coordenadas ORIGINAIS salvas no dataset
                    const rawCoords = area.dataset.originalCoords;
                    if (!rawCoords) return;

                    const coords = rawCoords.split(',').map(Number);
                    
                    // Desenha o retângulo
                    if (coords.length === 4) {
                        const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                        const x = Math.min(coords[0], coords[2]);
                        const y = Math.min(coords[1], coords[3]);
                        const width = Math.abs(coords[2] - coords[0]);
                        const height = Math.abs(coords[3] - coords[1]);

                        rect.setAttribute("x", x);
                        rect.setAttribute("y", y);
                        rect.setAttribute("width", width);
                        rect.setAttribute("height", height);
                        rect.setAttribute("fill", colors[color]);
                        svg.appendChild(rect);
                    }
                });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($paciente): ?>
        initOdontograma();
        <?php endif; ?>

        // Event listener delegado para os botões de remover anexo e procedimento
        document.body.addEventListener('click', function(event) {
            
            // Lógica para remover anexo de procedimento
            if (event.target.classList.contains('btn-remover-anexo')) {
                const button = event.target;
                const idProcedimento = button.dataset.idProcedimento;
                const container = button.closest('.arquivo-container');

                showConfirm('Remover Anexo', 'Você realmente deseja apagar esse arquivo?', function() {
                    fetch(window.__BASE_URL + '?rota=atendimentos.remAnexo', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'id_procedimento=' + encodeURIComponent(idProcedimento)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showToast(data.message, 'success');
                            container.innerHTML = `<button type="button" class="btn btn-primary btn-sm" style="padding: 5px 10px;" onclick="abrirModalUpload(${idProcedimento})">Anexar Arquivo</button>`;
                        } else {
                            showToast('Erro ao remover arquivo: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        showToast('Ocorreu um erro de comunicação com o servidor.', 'error');
                    });
                });
            }

            // Lógica para remover o procedimento inteiro
            if (event.target.classList.contains('btn-remover-procedimento')) {
                const button = event.target;
                const idProcedimento = button.dataset.idProcedimento;
                const statusExecucao = button.dataset.statusExecucao;
                const statusPagamento = button.dataset.statusPagamento;
                const card = button.closest('.sub-card');

                if (statusExecucao.toLowerCase() === 'pendente' && statusPagamento.toLowerCase() === 'nao_aplicavel') {
                    showConfirm('Remover Procedimento', 'Você realmente deseja remover este procedimento?', function() {
                        fetch(window.__BASE_URL + '?rota=atendimentos.remProc', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'id_procedimento=' + encodeURIComponent(idProcedimento)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                showToast(data.message, 'success');
                                card.remove(); // Remove o card do procedimento da UI
                            } else {
                                showToast('Erro: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Erro na requisição:', error);
                            showToast('Ocorreu um erro de comunicação com o servidor.', 'error');
                        });
                    });
                } else {
                    showToast('Você não tem autorização para apagar este procedimento.', 'error');
                }
            }
        });
    });
</script>

<?php  ?>