<div id="toast-notification" class="toast"></div>

<div class="card">
    <h2>Novo Lançamento de Atendimento</h2>
    <form id="form-atendimento" action="<?= BASE_URL ?>?rota=atendimentos.salvar" method="POST" enctype="multipart/form-data">
        
        <fieldset>
            <legend>Dados do Paciente</legend>
            <input type="hidden" name="paciente_id" id="paciente_id">
            <input type="hidden" name="paciente_nome" id="paciente_nome_hidden">

            <div class="form-group">
                <label for="paciente_busca">Buscar Paciente</label>
                <div style="position:relative;">
                    <div style="display:flex; gap:0.5rem;">
                        <input type="text" id="paciente_busca"
                               placeholder="Digite qualquer letra para buscar..."
                               autocomplete="off" style="flex-grow:1;"
                               oninput="buscarPacienteNativo(this.value)">
                        <button type="button" class="btn btn-danger" id="btn_limpar_paciente"
                                onclick="limparPaciente()" style="display:none;">✕ Limpar</button>
                    </div>
                    <ul id="lista_pacientes" style="
                        display:none; position:absolute; top:100%; left:0; right:0;
                        background:#fff; border:1px solid #ccc; border-radius:4px;
                        max-height:220px; overflow-y:auto; margin:2px 0 0; padding:0;
                        list-style:none; z-index:99999; box-shadow:0 4px 12px rgba(0,0,0,0.15);
                    "></ul>
                </div>
                <small id="paciente_status" style="color:#888; margin-top:4px; display:block;"></small>
            </div>
        </fieldset>

        <fieldset>
            <legend>Dados do Atendimento</legend>
            <div class="form-group">
                <label for="dentista">Dentista Responsável</label>
                <select name="id_dentista" id="dentista" required>
                    <option value="">Selecione...</option>
                    <?php foreach($dentistas as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="card">
                <h2>Odontograma do Paciente</h2>
                <p>Selecione um dente para registrar o tratamento:</p>

                <div class="canvas-container">
                    <img src="<?= BASE_URL ?>assets/img/odontograma.png" usemap="#image-map" class="img-odontograma" id="odontograma-img">
                    
                    <svg class="odontograma-overlay" id="odontograma-svg"></svg>
                    
                    <map name="image-map" id="image-map">
                        <area target="" onclick="marcarDente(this, 18, 'Arcada Superior')" alt="Molar 18" id="d18" title="3º Molar" coords="53,251,99,157" shape="rect">
                        <area target="" onclick="marcarDente(this, 17, 'Arcada Superior')" alt="Molar 17" id="d17" title="2º Molar" coords="147,156,103,249" shape="rect">
                        <area target="" onclick="marcarDente(this, 16, 'Arcada Superior')" alt="Molar 16" title="Molar 16" coords="207,155,151,246" shape="rect">
                        <area target="" onclick="marcarDente(this, 15, 'Arcada Superior')" alt="Premolar 15" title="Premolar 15" coords="241,152,209,242" shape="rect">
                        <area target="" onclick="marcarDente(this, 14, 'Arcada Superior')" alt="Premolar 14" title="Premolar 14" coords="274,149,246,241" shape="rect">
                        <area target="" onclick="marcarDente(this, 13, 'Arcada Superior')" alt="Canino 13" title="Canino 13" coords="314,148,277,238" shape="rect">
                        <area target="" onclick="marcarDente(this, 12, 'Arcada Superior')" alt="Inciso 12" title="Inciso 12" coords="352,152,317,243" shape="rect">
                        <area target="" onclick="marcarDente(this, 11, 'Arcada Superior')" alt="Inciso 11" title="Inciso 11" coords="397,153,355,246" shape="rect">
                        <area target="" onclick="marcarDente(this, 21, 'Arcada Superior')" alt="Inciso 21" title="Inciso 21" coords="442,154,403,244" shape="rect">
                        <area target="" onclick="marcarDente(this, 22, 'Arcada Superior')" alt="Inciso 22" title="Inciso 22" coords="479,153,446,243" shape="rect">
                        <area target="" onclick="marcarDente(this, 23, 'Arcada Superior')" alt="Canino 23" title="Canino 23" coords="521,142,481,243" shape="rect">
                        <area target="" onclick="marcarDente(this, 24, 'Arcada Superior')" alt="Premolar 24" title="Premolar 24" coords="561,146,525,239" shape="rect">
                        <area target="" onclick="marcarDente(this, 25, 'Arcada Superior')" alt="Premolar 25" title="Premolar 25" coords="590,146,564,237" shape="rect">
                        <area target="" onclick="marcarDente(this, 26, 'Arcada Superior')" alt="Molar 26" title="Molar 26" coords="648,148,593,238" shape="rect">
                        <area target="" onclick="marcarDente(this, 27, 'Arcada Superior')" alt="Molar 27" title="Molar 27" coords="703,151,653,239" shape="rect">
                        <area target="" onclick="marcarDente(this, 28, 'Arcada Superior')" alt="Molar 28" id="d28" title="3º Molar" coords="741,149,705,241" shape="rect">

                        <area target="" onclick="marcarDente(this, 48, 'Arcada Inferior')" alt="Molar 48" id="d48" title="Molar 48" coords="51,285,103,360" shape="rect">
                        <area target="" onclick="marcarDente(this, 47, 'Arcada Inferior')" alt="Molar 47" title="Molar 47" coords="109,284,160,363" shape="rect">
                        <area target="" onclick="marcarDente(this, 46, 'Arcada Inferior')" alt="Molar 46" title="Molar 46" coords="167,281,219,363" shape="rect">
                        <area target="" onclick="marcarDente(this, 45, 'Arcada Inferior')" alt="Premolar 45" title="Premolar 45" coords="221,278,258,378" shape="rect">
                        <area target="" onclick="marcarDente(this, 44, 'Arcada Inferior')" alt="Premolar 44" title="Premolar 44" coords="260,275,296,390" shape="rect">
                        <area target="" onclick="marcarDente(this, 43, 'Arcada Inferior')" alt="Canino 43" title="Canino 43" coords="298,276,336,384" shape="rect">
                        <area target="" onclick="marcarDente(this, 42, 'Arcada Inferior')" alt="Inciso 42" title="Inciso 42" coords="338,275,368,384" shape="rect">
                        <area target="" onclick="marcarDente(this, 41, 'Arcada Inferior')" alt="Inciso 41" title="Inciso 41" coords="370,276,395,383" shape="rect">
                        <area target="" onclick="marcarDente(this, 31, 'Arcada Inferior')" alt="Inciso 31" title="Inciso 31" coords="398,275,426,380" shape="rect">
                        <area target="" onclick="marcarDente(this, 32, 'Arcada Inferior')" alt="Inciso 32" title="Inciso 32" coords="428,275,454,382" shape="rect">
                        <area target="" onclick="marcarDente(this, 33, 'Arcada Inferior')" alt="Canino 33" title="Canino 33" coords="456,274,493,391" shape="rect">
                        <area target="" onclick="marcarDente(this, 34, 'Arcada Inferior')" alt="Premolar 34" title="Premolar 34" coords="496,274,531,383" shape="rect">
                        <area target="" onclick="marcarDente(this, 35, 'Arcada Inferior')" alt="Premolar 35" title="Premolar 35" coords="534,274,571,379" shape="rect">
                        <area target="" onclick="marcarDente(this, 36, 'Arcada Inferior')" alt="Molar 36" title="Molar 36" coords="575,274,636,384" shape="rect">
                        <area target="" onclick="marcarDente(this, 37, 'Arcada Inferior')" alt="Molar 37" title="Molar 37" coords="640,274,688,384" shape="rect">
                        <area target="" onclick="marcarDente(this, 38, 'Arcada Inferior')" alt="Molar 38" title="Molar 38" coords="694,272,742,375" shape="rect">

                        <area target="" onclick="marcarDente(this, 'Todos', 'Geral')" alt="Todos" title="Todos" coords="85,31,727,83" shape="rect">
                        <area target="" onclick="marcarDente(this, 'Todos', 'Geral')" alt="Todos" title="Todos" coords="72,449,727,498" shape="rect">
                    </map>
                </div>
            </div>

            <div id="procedimentos_pendentes_container" style="margin-top: 1rem;">
                </div>

            <div id="procedimentos_adicionados_container">
                <h3>Procedimentos Adicionados</h3>
                </div>

            <div id="total-procedimentos-container" style="text-align: right; margin-top: 10px; font-size: 1.5em;">
                <strong>Total a Pagar:</strong> <span id="total-procedimentos-valor">R$ 0,00</span>
            </div>

        <div id="procedimentos_a_deletar_container"></div>

        <button type="submit" class="btn btn-success" style="width: 100%;">Lançar Atendimento</button>
    </form>

<div id="modalTratamento" class="modal">
    <div class="modal-content">
        <h3><span id="modal-title"></span></h3>
        <form id="form-tratamento-modal">
            <input type="hidden" name="dente_id" id="inputDente">
            <input type="hidden" name="arcada" id="inputArcada">

            <div id="procedimentos-modal-container">
                </div>

            <button type="button" id="add-procedimento-modal" class="btn btn-info">Adicionar Procedimento</button>

            <div style="text-align: right; margin-top: 10px; font-size: 1.2em;">
                <strong>Total:</strong> <span id="modal-total-valor">R$ 0,00</span>
            </div>

            <div class="btn-group">
                <button type="button" id="salvar-tratamento-modal" class="btn-save">Salvar</button>
                <button type="button" onclick="fecharModal()" class="btn-cancel">Cancelar</button>
            </div>
        </form>
    </div>
</div>
</div>

<style>
    .grid-container { display: grid; grid-template-columns: repeat(12, 1fr); gap: 1rem; }
    .grid-col-2 { grid-column: span 2; }
    .grid-col-3 { grid-column: span 3; }
    .grid-col-4 { grid-column: span 4; }
    .grid-col-6 { grid-column: span 6; }
    fieldset { border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem; border-radius: 5px; }
    legend { font-weight: bold; padding: 0 0.5rem; }
    .procedimento-row, .pagamento-row { display: flex; align-items: center; margin-bottom: 10px; }
    .procedimento-row select, .procedimento-row input, .pagamento-row select, .pagamento-row input { margin-right: 10px; }

    #btn_limpar_paciente { width: 140px; margin-left: 10px; }
    .toast { position: fixed; top: 20px; right: 20px; padding: 15px 20px; border-radius: 5px; color: white; font-size: 16px; z-index: 9999; opacity: 0; visibility: hidden; transition: opacity 0.5s, visibility 0.5s, transform 0.5s; transform: translateX(100%); }
    .toast.show { opacity: 1; visibility: visible; transform: translateX(0); }
    .toast.error { background-color: #c0392b; }
    .toast.success { background-color: #27ae60; }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 10000; /* Increased z-index to be on top of everything */
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
        width: 500px; /* Increased width */
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    /* Regras gerais para todos os botões */
    .btn, button, input[type="submit"] {
        border-radius: var(--border-radius); /* 30px conforme o login.php */
        padding: 10px 25px;
        font-family: 'Open Sans', sans-serif;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    /* Botão Primário (Azul do padrão) */
    .btn-primary {
        background-color: var(--primary-color);
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary { 
        background-color: var(--secondary-color); 
        color: #ffffff; 
    }

    .btn-secondary:hover { 
        background-color: #28a745; 
    }


    /* Botão Sucesso (Verde - Corrigido para estar sempre visível) */
    .btn-success {
        background-color: #28a745; 
        color: #ffffff;
    }

    .btn-success:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    /* Botão Perigo/Erro (Vermelho - Corrigido para estar sempre visível) */
    .btn-danger {
        background-color: #dc3545;
        color: #ffffff;
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-2px);
    }

    /* Botão Alerta (Amarelo - Corrigido para estar sempre visível) */
    .btn-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        transform: translateY(-2px);
    }

    .btn-info { 
        background-color: var(--info-color); 
        color:  #ffffff; 
    }
    .btn-info:hover { 
        background-color: #138496; 
    }

    /* Definição do botão Cancelar */
    .btn-cancel {
        background-color: #17a2b8; /* Cor sólida (Equivalente ao Info) */
        color: #ffffff !important;
        border: none;
        
        /* Padrão do login.php */
        font-family: 'Open Sans', sans-serif;
        font-weight: 600;
        border-radius: var(--border-radius); /* Usa os 30px definidos no :root */
        padding: 10px 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    /* Estado quando passa o mouse */
    .btn-cancel:hover {
        background-color: #138496; /* Cor um pouco mais escura */
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        text-decoration: none;
    }

    /* ESTILOS DO ODONTOGRAMA RESPONSIVO (DO NOVO_ATENDIMENTO3) */
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
        pointer-events: none; /* Deixa o clique passar para o map */
    }

    area {
        cursor: pointer;
        outline: none;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/image-map-resizer/1.0.10/js/imageMapResizer.min.js"></script>

<script>
// --- FUNÇÕES DE INTERAÇÃO DO ODONTOGRAMA (RESPONSIVIDADE) ---
function initOdontograma() {
    const img = document.getElementById('odontograma-img');
    const svg = document.getElementById('odontograma-svg');
    const map = document.getElementById('image-map');
    const areas = map.getElementsByTagName('area');

    const setup = () => {
        // Configura o SVG para usar o sistema de coordenadas da imagem ORIGINAL
        svg.setAttribute('viewBox', `0 0 ${img.naturalWidth} ${img.naturalHeight}`);
        
        // Salva as coordenadas originais
        for (let area of areas) {
            area.dataset.originalCoords = area.coords;
        }
        
        // Inicia a biblioteca que ajusta os cliques responsivamente
        imageMapResize();
    };

    if (img.complete) { setup(); } else { img.onload = setup; }
}

function marcarDente(areaElement, dente, arcada) {
    const svg = document.getElementById('odontograma-svg');
    const rawCoords = areaElement.dataset.originalCoords;
    
    if (!rawCoords) {
        // Fallback caso não tenha carregado o dataset (erro raro)
        window.abrirModal(dente, arcada);
        return;
    }

    const coords = rawCoords.split(',').map(Number);
    const idMarca = `marca-${dente}`;
    const marcaExistente = document.getElementById(idMarca);
    
    // Remove marca anterior se existir (limpa antes de abrir modal ou alterna)
    if (marcaExistente) { 
        marcaExistente.remove(); 
    } else {
        const x = Math.min(coords[0], coords[2]);
        const y = Math.min(coords[1], coords[3]);
        const width = Math.abs(coords[2] - coords[0]);
        const height = Math.abs(coords[3] - coords[1]);

        const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
        rect.setAttribute("x", x);
        rect.setAttribute("y", y);
        rect.setAttribute("width", width);
        rect.setAttribute("height", height);
        rect.setAttribute("fill", "rgba(255, 0, 0, 0.3)"); 
        rect.setAttribute("stroke", "red");                
        rect.setAttribute("stroke-width", "2");
        rect.id = idMarca;
        svg.appendChild(rect);
    }

    // Chama a função original de abrir o modal
    window.abrirModal(dente, arcada);
}
// -----------------------------------------------------------

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast-notification');
    if (toast) {
        toast.textContent = message;
        toast.className = 'toast show ' + type; // Add type for styling
        setTimeout(() => {
            toast.className = toast.className.replace(' show', '');
        }, 5000); // Hide after 5 seconds
    }
}

$(document).ready(function() {
    // Inicializa o Odontograma Responsivo
    initOdontograma();

        // ── Busca de paciente nativa (sem jQuery UI) ──────────────────────────────
    let _buscaTimer = null;
    let _pacienteSelecionado = false;

    window.buscarPacienteNativo = function(term) {
        const lista  = document.getElementById('lista_pacientes');
        const status = document.getElementById('paciente_status');
        const hiddenId = document.getElementById('paciente_id');
        const btnLimpar = document.getElementById('btn_limpar_paciente');

        // Se paciente já foi selecionado, não pesquisar novamente
        if (_pacienteSelecionado) return;

        clearTimeout(_buscaTimer);

        if (term.length === 0) {
            lista.style.display = 'none';
            lista.innerHTML = '';
            status.textContent = '';
            return;
        }

        status.textContent = 'Buscando...';
        lista.style.display = 'none';

        _buscaTimer = setTimeout(function() {
            fetch(window.__BASE_URL + 'ajax/buscar_paciente.php?term=' + encodeURIComponent(term))
                .then(r => r.json())
                .then(data => {
                    lista.innerHTML = '';

                    if (data.length === 0) {
                        const hiddenNome = document.getElementById('paciente_nome_hidden');
                        hiddenId.value   = '';          // sem ID = novo paciente
                        hiddenNome.value = term;        // nome para criar
                        lista.style.display = 'none';
                        status.textContent = '⚠ Nenhum cadastrado com esse nome — será criado ao salvar.';
                        status.style.color = '#e67e22';
                        btnLimpar.style.display = 'inline-block';
                        return;
                    }

                    status.textContent = data.length + ' encontrado(s). Selecione:';
                    data.forEach(function(p) {
                        const li = document.createElement('li');
                        li.style.cssText = 'padding:10px 14px; cursor:pointer; border-bottom:1px solid #eee; font-size:14px;';
                        li.innerHTML = '<strong>' + escHTML(p.nome) + '</strong>'
                            + (p.cpf ? ' <span style="color:#888; font-size:12px;">CPF: ' + escHTML(p.cpf) + '</span>' : '')
                            + (p.telefone ? ' <span style="color:#888; font-size:12px;">· ' + escHTML(p.telefone) + '</span>' : '');

                        li.addEventListener('mouseover', function() { this.style.background = '#f0f4ff'; });
                        li.addEventListener('mouseout',  function() { this.style.background = '#fff'; });
                        li.addEventListener('mousedown', function(e) {
                            e.preventDefault(); // Evita blur antes do click
                            selecionarPaciente(p);
                        });
                        lista.appendChild(li);
                    });
                    lista.style.display = 'block';
                })
                .catch(function(err) { if(status) status.textContent = 'Sem conexão com o servidor. Verifique se está logado.'; console.error('Busca AJAX erro:', err); });
        }, 200); // debounce 200ms
    };

    function escHTML(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function selecionarPaciente(p) {
        const campo       = document.getElementById('paciente_busca');
        const hiddenId    = document.getElementById('paciente_id');
        const hiddenNome  = document.getElementById('paciente_nome_hidden');
        const lista       = document.getElementById('lista_pacientes');
        const status      = document.getElementById('paciente_status');
        const btnLimpar   = document.getElementById('btn_limpar_paciente');

        campo.value       = p.nome;
        hiddenId.value    = p.id;
        hiddenNome.value  = '';          // limpar nome — não criar novo
        lista.style.display = 'none';
        status.textContent  = '✓ ' + p.nome + ' selecionado';
        status.style.color  = 'green';
        campo.readOnly    = true;
        btnLimpar.style.display = 'inline-block';
        _pacienteSelecionado = true;
        carregarProcedimentosPendentes(p.id);
    }

    window.limparPaciente = function() {
        const campo      = document.getElementById('paciente_busca');
        const hiddenId   = document.getElementById('paciente_id');
        const hiddenNome = document.getElementById('paciente_nome_hidden');
        const lista      = document.getElementById('lista_pacientes');
        const status     = document.getElementById('paciente_status');
        const btnLimpar  = document.getElementById('btn_limpar_paciente');

        campo.value      = '';
        hiddenId.value   = '';
        hiddenNome.value = '';
        lista.style.display = 'none';
        lista.innerHTML = '';
        status.textContent  = '';
        status.style.color  = '#888';
        campo.readOnly  = false;
        btnLimpar.style.display = 'none';
        _pacienteSelecionado = false;
        campo.focus();
        $('#procedimentos_pendentes_container').empty();
        $('#procedimentos_adicionados_container').html('<h3>Procedimentos Adicionados</h3>');
        $('#odontograma-svg').empty();
    };

    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        const lista = document.getElementById('lista_pacientes');
        if (!document.getElementById('paciente_busca').contains(e.target)) {
            if (lista) lista.style.display = 'none';
        }
    });
    
    const procedimentos = <?= json_encode($procedimentos) ?>;

    function carregarProcedimentosPendentes(pacienteId) {
        const container = $('#procedimentos_pendentes_container');
        container.html('<h4>Carregando procedimentos pendentes...</h4>');

        $.ajax({
            url: window.__BASE_URL + "?rota=atendimentos.pendentes",
            dataType: "json",
            data: { paciente_id: pacienteId },
            success: function(data) {
                container.empty();
                if (data && data.length > 0) {
                    container.append('<h3>Procedimentos Pendentes Anteriores</h3>');
                    data.forEach(proc => {
                        const procHtml = `
                            <div class="procedimento-pendente-item" id="pendente-${proc.atendimento_procedimento_id}" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border: 1px solid #ddd; margin-bottom: 5px; border-radius: 5px;">
                                <div>
                                    <strong>Procedimento:</strong> ${proc.procedimento_nome} (${proc.categoria})<br>
                                    <strong>Local:</strong> ${proc.local} | <strong>Descrição:</strong> ${proc.descricao || 'N/A'}
                                </div>
                                <button type="button" class="btn btn-success btn-sm finalizar-pendente-btn" 
                                        data-proc-id="${proc.id_procedimento}"
                                        data-proc-nome="${proc.procedimento_nome} (${proc.categoria})"
                                        data-proc-valor="${proc.valor_procedimento / proc.quantidade}"
                                        data-proc-local="${proc.local}"
                                        data-proc-custo-auxiliar="${proc.custo_auxiliar || 0}"
                                        data-proc-descricao="${proc.descricao}"
                                        data-original-id="${proc.atendimento_procedimento_id}"
                                        data-proc-categoria="${proc.categoria}"
                                        data-proc-natureza="${proc.natureza || ''}">Finalizar Agora</button>
                            </div>
                        `;
                        container.append(procHtml);
                    });
                }
            },
            error: () => container.html('<p class="error">Erro ao carregar pendências.</p>')
        });
    }

    $(document).on('click', '.finalizar-pendente-btn', function() {
        const btn = $(this);
        const originalId = btn.data('original-id');

        // Adiciona o procedimento à lista principal como 'finalizado'
        criarLinhaProcedimentoPrincipal(
            btn.data('proc-id'), 
            btn.data('proc-nome'), 
            1, 
            btn.data('proc-valor'),
            btn.data('proc-local'), 
            btn.data('proc-descricao'), 
            'finalizado',
            btn.data('proc-custo-auxiliar'),
            originalId, // Passa o originalId
            btn.data('proc-natureza'),
            btn.data('proc-categoria')
        );
        
        // Adiciona o ID original a um campo hidden para ser deletado no backend
        $('#procedimentos_a_deletar_container').append(
            `<input type="hidden" name="procedimentos_a_deletar[]" value="${originalId}" id="delete-${originalId}">`
        );

        // Remove o item da lista de pendentes na UI
        $(`#pendente-${originalId}`).remove();
    });

    // Odontogram Modal
    const modal = document.getElementById('modalTratamento');
    const modalTitle = document.getElementById('modal-title');
    const inputDente = document.getElementById('inputDente');
    const inputArcada = document.getElementById('inputArcada');
    const procedimentosModalContainer = document.getElementById('procedimentos-modal-container');
    const salvarTratamentoModalButton = document.getElementById('salvar-tratamento-modal');

    // Abre o modal
    window.abrirModal = function(numero, arcada) {
        modal.classList.add('show');
        if (arcada === 'Geral') {
            modalTitle.innerText = 'Tratamento geral';
        } else {
            modalTitle.innerText = arcada + ' - Dente ' + numero;
        }
        inputDente.value = numero;
        inputArcada.value = arcada;
        procedimentosModalContainer.innerHTML = '';
        adicionarLinhaProcedimentoModal();
        updateModalTotal(); 
    }

    // Fecha o modal
    window.fecharModal = function() {
        modal.classList.remove('show');
    }

    // Fecha o modal ao clicar fora
    window.onclick = function(event) {
        if (event.target == modal) {
            fecharModal();
        }
    }

    function adicionarLinhaProcedimentoModal() {
        const row = document.createElement('div');
        row.classList.add('procedimento-row');
        row.style.marginBottom = '15px';

        const select = document.createElement('select');
        select.name = 'procedimentos_modal[id][]';
        select.required = true;
        let option = document.createElement('option');
        option.value = '';
        option.textContent = 'Selecione...';
        select.appendChild(option);
        procedimentos.forEach(p => {
            let opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = `${p.nome} (${p.categoria})`;
            opt.dataset.valor = p.valor_base;
            opt.dataset.categoria = p.categoria;
            select.appendChild(opt);
        });

        const valorSpan = document.createElement('span');
        valorSpan.classList.add('procedimento-valor-span');
        valorSpan.style.marginLeft = '10px';
        valorSpan.style.fontWeight = 'bold';

        const naturezaContainer = document.createElement('div');
        naturezaContainer.style.display = 'none';
        naturezaContainer.style.marginTop = '5px';
        const naturezaLabel = document.createElement('label');
        naturezaLabel.textContent = 'Tipo de Procedimento Especializado';
        naturezaLabel.style.display = 'block';
        naturezaLabel.style.fontSize = '0.9em';
        const naturezaSelect = document.createElement('select');
        naturezaSelect.name = 'procedimentos_modal[natureza][]';
        const naturezas = {'': 'Selecione...', 'orto': 'Orto', 'canal': 'Canal', 'protese': 'Prótese', 'cirurgia_especializada': 'Cirurgia Especializada'};
        for (const key in naturezas) {
            let opt = document.createElement('option');
            opt.value = key;
            opt.textContent = naturezas[key];
            naturezaSelect.appendChild(opt);
        }
        naturezaContainer.appendChild(naturezaLabel);
        naturezaContainer.appendChild(naturezaSelect);

        const custoAuxiliarInput = document.createElement('input');
        custoAuxiliarInput.type = 'number';
        custoAuxiliarInput.name = 'procedimentos_modal[custo_auxiliar][]';
        custoAuxiliarInput.step = '0.01';
        custoAuxiliarInput.value = '250.00'; // Inicializa com 0
        custoAuxiliarInput.placeholder = 'Custo Auxiliar (R$)';
        custoAuxiliarInput.style.width = '100%';
        custoAuxiliarInput.style.marginTop = '5px';
        custoAuxiliarInput.style.display = 'none';

        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const valor = selectedOption.dataset.valor || 0;
            valorSpan.textContent = `R$ ${parseFloat(valor).toFixed(2)}`;

            // Reset all conditional fields first
            naturezaContainer.style.display = 'none';
            naturezaSelect.required = false;
            naturezaSelect.value = '';
            custoAuxiliarInput.style.display = 'none';
            custoAuxiliarInput.required = false;
            custoAuxiliarInput.value = '';

            if (selectedOption && selectedOption.dataset.categoria === 'especializado') {
                naturezaContainer.style.display = 'block';
                naturezaSelect.required = true;
            } else if (selectedOption && selectedOption.dataset.categoria === 'protese') {
                custoAuxiliarInput.style.display = 'block';
                custoAuxiliarInput.required = true;
            }
            updateModalTotal();
        });

        naturezaSelect.addEventListener('change', function() {
            if (this.value === 'protese') {
                custoAuxiliarInput.style.display = 'block';
                custoAuxiliarInput.required = true;
            } else {
                custoAuxiliarInput.style.display = 'none';
                custoAuxiliarInput.required = false;
                custoAuxiliarInput.value = '';
            }
        });

        const descricao = document.createElement('textarea');
        descricao.name = 'procedimentos_modal[descricao][]';
        descricao.placeholder = 'Descrição';
        descricao.rows = 2;
        descricao.style.width = '100%';
        descricao.style.marginTop = '5px';

        const finalizadoLabel = document.createElement('label');
        finalizadoLabel.style.display = 'flex';
        finalizadoLabel.style.alignItems = 'center';
        finalizadoLabel.style.marginTop = '5px';
        const finalizadoCheckbox = document.createElement('input');
        finalizadoCheckbox.type = 'checkbox';
        finalizadoCheckbox.name = 'procedimentos_modal[finalizado][]';
        finalizadoCheckbox.value = '1';
        finalizadoCheckbox.checked = true;
        finalizadoCheckbox.addEventListener('change', updateModalTotal);
        finalizadoLabel.appendChild(finalizadoCheckbox);
        finalizadoLabel.append(' Finalizado');

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.textContent = 'Remover';
        removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
        removeButton.style.marginLeft = '10px';
        removeButton.onclick = () => {
            row.remove();
            updateModalTotal();
        };
        
        const controls = document.createElement('div');
        controls.style.display = 'flex';
        controls.style.alignItems = 'center';
        
        const mainContent = document.createElement('div');
        mainContent.style.flexGrow = '1';
        mainContent.appendChild(select);
        mainContent.appendChild(valorSpan);
        mainContent.appendChild(naturezaContainer);
        mainContent.appendChild(custoAuxiliarInput);
        mainContent.appendChild(descricao);
        mainContent.appendChild(finalizadoLabel);
        
        controls.appendChild(mainContent);
        controls.appendChild(removeButton);
        row.appendChild(controls);

        procedimentosModalContainer.appendChild(row);
        updateModalTotal();
    }

    function updateModalTotal() {
        let total = 0;
        const rows = procedimentosModalContainer.querySelectorAll('.procedimento-row');
        rows.forEach(row => {
            const finalizadoCheckbox = row.querySelector('input[type="checkbox"]');
            if (finalizadoCheckbox && finalizadoCheckbox.checked) {
                const select = row.querySelector('select');
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.dataset.valor) {
                    total += parseFloat(selectedOption.dataset.valor);
                }
            }
        });
        document.getElementById('modal-total-valor').textContent = `R$ ${total.toFixed(2)}`;
    }

    $('#add-procedimento-modal').on('click', adicionarLinhaProcedimentoModal);

    function criarLinhaProcedimentoPrincipal(procedimentoId, procedimentoNome, quantidade, valor, local, descricao, status_execucao, custoAuxiliar = 0, originalId = null, natureza = '', categoria = '') {
        const container = $('#procedimentos_adicionados_container');
        const uniqueId = 'proc-' + Date.now() + Math.random().toString(36).substr(2, 9);
        const canAlterarValor = <?= (is_admin() || is_dentista() || is_recepcionista()) ? 'true' : 'false' ?>;

        const isChecked = status_execucao === 'finalizado';
        const originalIdAttr = originalId ? `data-original-id="${originalId}"` : '';

        let alterarValorHtml = '';
        if (canAlterarValor) {
            alterarValorHtml = `
                <div class="valor-container" style="margin-top: 5px;">
                    <strong>Valor:</strong> 
                    <span class="valor-display">R$ ${parseFloat(valor).toFixed(2)}</span>
                    <button type="button" class="btn btn-info btn-sm alterar-valor-btn" style="margin-left: 10px; padding: 2px 10px; font-size: 12px;">Alterar Valor</button>
                    <div class="alterar-valor-form" style="display: none; margin-top: 5px;">
                        <input type="number" step="0.01" class="novo-valor-input" style="width: 100px;" value="${parseFloat(valor).toFixed(2)}">
                        <button type="button" class="btn btn-success btn-sm salvar-valor-btn" style="margin-left: 5px; padding: 2px 10px; font-size: 12px;">Salvar</button>
                    </div>
                </div>
            `;
        } else {
            alterarValorHtml = `<div class="valor-container" style="margin-top: 5px;"><strong>Valor:</strong> <span class="valor-display">R$ ${parseFloat(valor).toFixed(2)}</span></div>`;
        }

        const naturezaHtml = (categoria !== 'geral' && natureza) ? ` | <strong>Natureza:</strong> ${natureza.charAt(0).toUpperCase() + natureza.slice(1)}` : '';

        let alterarCustoHtml = '';
        if (canAlterarValor && custoAuxiliar > 0) {
            alterarCustoHtml = `
                <div class="custo-auxiliar-container" style="margin-top: 5px;">
                    <strong>Custo Auxiliar:</strong> 
                    <span class="custo-display">R$ ${parseFloat(custoAuxiliar).toFixed(2)}</span>
                    <button type="button" class="btn btn-warning btn-sm alterar-custo-btn" style="margin-left: 10px; padding: 2px 10px; font-size: 12px;">Alterar Custo</button>
                    <div class="alterar-custo-form" style="display: none; margin-top: 5px;">
                        <input type="number" step="0.01" class="novo-custo-input" style="width: 100px;" value="${parseFloat(custoAuxiliar).toFixed(2)}">
                        <button type="button" class="btn btn-success btn-sm salvar-custo-btn" style="margin-left: 5px; padding: 2px 10px; font-size: 12px;">Confirmar</button>
                    </div>
                </div>
            `;
        }

        const procHtml = `
            <div class="procedimento-row-principal" id="${uniqueId}" 
                 data-valor="${valor}" data-status_execucao="${status_execucao}"
                 data-proc-id="${procedimentoId}" data-proc-nome="${procedimentoNome}"
                 data-proc-local="${local}" data-proc-descricao="${descricao}"
                 data-custo-auxiliar="${custoAuxiliar}"
                 ${originalIdAttr}
                 data-natureza="${natureza}"
                 data-categoria="${categoria}"
                 style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border: 1px solid #ddd; margin-bottom: 5px; border-radius: 5px;">
                
                <input type="hidden" name="procedimentos[id][]" value="${procedimentoId}">
                <input type="hidden" name="procedimentos[quantidade][]" value="${quantidade}">
                <input type="hidden" class="valor-input" name="procedimentos[valor][]" value="${valor}">
                <input type="hidden" name="procedimentos[local][]" value="${local}">
                <input type="hidden" name="procedimentos[descricao][]" value="${descricao}">
                <input type="hidden" name="procedimentos[custo_auxiliar][]" value="${custoAuxiliar}">
                <input type="hidden" class="status-execucao-input" name="procedimentos[status_execucao][]" value="${status_execucao}">
                <input type="hidden" name="procedimentos[natureza][]" value="${natureza}">

                <div>
                    <strong>Procedimento:</strong> ${procedimentoNome}<br>
                    <strong>Local:</strong> ${local} | <strong>Status:</strong> <span class="status-text">${status_execucao}</span>${naturezaHtml} <br>
                    <strong>Descrição:</strong> ${descricao || 'N/A'}
                    ${alterarCustoHtml || (custoAuxiliar > 0 ? `<br><strong>Custo Auxiliar:</strong> R$ ${parseFloat(custoAuxiliar).toFixed(2)}` : '')}
                    ${alterarValorHtml}
                </div>
                <div style="text-align: center;">
                    <label style="display: block; margin-bottom: 5px;">
                        <input type="checkbox" class="finalizado-checkbox" ${isChecked ? 'checked' : ''}>
                        Finalizado
                    </label>
                    <button type="button" class="btn btn-danger btn-sm remover-proc-principal">Remover</button>
                </div>
            </div>
        `;
        const procElement = $(procHtml);

        procElement.find('.remover-proc-principal').on('click', function() {
            removerProcedimento(uniqueId);
        });

        procElement.find('.finalizado-checkbox').on('change', function() {
            const isChecked = $(this).is(':checked');
            const newStatus = isChecked ? 'finalizado' : 'pendente';
            const parentRow = $(this).closest('.procedimento-row-principal');
            
            parentRow.attr('data-status_execucao', newStatus);
            parentRow.find('.status-execucao-input').val(newStatus);
            parentRow.find('.status-text').text(newStatus);

            updateTotalAPagar();
        });

        // Lógica para alterar valor
        procElement.find('.alterar-valor-btn').on('click', function() {
            $(this).hide();
            procElement.find('.valor-display').hide();
            procElement.find('.alterar-valor-form').show();
            procElement.find('.novo-valor-input').val(procElement.attr('data-valor')).focus();
        });

        procElement.find('.salvar-valor-btn').on('click', function() {
            const novoValor = procElement.find('.novo-valor-input').val();
            if (novoValor && !isNaN(novoValor) && parseFloat(novoValor) >= 0) {
                const novoValorFloat = parseFloat(novoValor);
                procElement.attr('data-valor', novoValorFloat);
                procElement.find('.valor-input').val(novoValorFloat);
                procElement.find('.valor-display').text(`R$ ${novoValorFloat.toFixed(2)}`);
                updateTotalAPagar();
            }
            procElement.find('.alterar-valor-form').hide();
            procElement.find('.alterar-valor-btn').show();
            procElement.find('.valor-display').show();
        });

        // Lógica para alterar custo auxiliar
        procElement.find('.alterar-custo-btn').on('click', function() {
            $(this).hide();
            procElement.find('.custo-display').hide();
            procElement.find('.alterar-custo-form').show();
            procElement.find('.novo-custo-input').focus();
        });

        procElement.find('.salvar-custo-btn').on('click', function() {
            const novoCusto = procElement.find('.novo-custo-input').val();
            if (novoCusto && !isNaN(novoCusto) && parseFloat(novoCusto) >= 0) {
                const novoCustoFloat = parseFloat(novoCusto);
                procElement.attr('data-custo-auxiliar', novoCustoFloat);
                procElement.find('input[name="procedimentos[custo_auxiliar][]"]').val(novoCustoFloat);
                procElement.find('.custo-display').text(`R$ ${novoCustoFloat.toFixed(2)}`);
            }
            procElement.find('.alterar-custo-form').hide();
            procElement.find('.alterar-custo-btn').show();
            procElement.find('.custo-display').show();
        });

        container.append(procElement);
        updateTotalAPagar();
    }

    function updateTotalAPagar() {
        let total = 0;
        $('.procedimento-row-principal').each(function() {
            const row = $(this);
            if (row.attr('data-status_execucao') === 'finalizado') {
                total += parseFloat(row.attr('data-valor'));
            }
        });
        $('#total-procedimentos-valor').text(`R$ ${total.toFixed(2)}`);
    }

    function removerProcedimento(elementId) {
        const procElement = $('#' + elementId);
        if (!procElement.length) return;

        const originalId = procElement.data('original-id');

        // Se tem ID original, significa que veio da lista de pendentes
        if (originalId) {
            const container = $('#procedimentos_pendentes_container');
            if (container.find(`#pendente-${originalId}`).length === 0) {
                // Remonta o HTML do item pendente
                 const procHtml = `
                    <div class="procedimento-pendente-item" id="pendente-${originalId}" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border: 1px solid #ddd; margin-bottom: 5px; border-radius: 5px;">
                        <div>
                            <strong>Procedimento:</strong> ${procElement.data('proc-nome')}<br>
                            <strong>Local:</strong> ${procElement.data('proc-local')} | <strong>Descrição:</strong> ${procElement.data('proc-descricao') || 'N/A'}
                        </div>
                        <button type="button" class="btn btn-success btn-sm finalizar-pendente-btn" 
                                data-proc-id="${procElement.data('proc-id')}"
                                data-proc-nome="${procElement.data('proc-nome')}"
                                data-proc-valor="${procElement.data('valor')}"
                                data-proc-local="${procElement.data('proc-local')}"
                                data-proc-descricao="${procElement.data('proc-descricao')}"
                                data-proc-custo-auxiliar="${procElement.data('custo-auxiliar') || 0}"
                                data-original-id="${originalId}"
                                data-proc-categoria="${procElement.data('categoria')}"
                                data-proc-natureza="${procElement.data('natureza')}">Finalizar Agora</button>
                    </div>
                `;
                container.append(procHtml);
            }
            // Remove o input que marcava para deletar no backend
            $(`#delete-${originalId}`).remove();
        }

        procElement.remove();
        updateTotalAPagar();
    }

    salvarTratamentoModalButton.addEventListener('click', function() {
        const dente = inputDente.value;
        const arcada = inputArcada.value;
        const local = (arcada === 'Geral') ? 'Todos' : dente;

        let formValido = true;
        const rowsParaValidar = procedimentosModalContainer.querySelectorAll('.procedimento-row');
        rowsParaValidar.forEach(row => {
            const select = row.querySelector('select[name="procedimentos_modal[id][]"]');
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption || !selectedOption.value) return;

            const categoria = selectedOption.dataset.categoria;
            const naturezaSelect = row.querySelector('select[name="procedimentos_modal[natureza][]"]');
            const custoAuxiliarInput = row.querySelector('input[name="procedimentos_modal[custo_auxiliar][]"]');
            
            // Reseta a borda para o padrão
            if (naturezaSelect) naturezaSelect.style.border = '1px solid #ccc';
            if (custoAuxiliarInput) custoAuxiliarInput.style.border = '1px solid #ccc';

            if (categoria === 'especializado' && (!naturezaSelect || !naturezaSelect.value)) {
                formValido = false;
                naturezaSelect.style.border = '2px solid red';
            }

            // Validação para custo auxiliar de prótese
            const isProtese = (categoria === 'protese') || (categoria === 'especializado' && naturezaSelect && naturezaSelect.value === 'protese');
            if (isProtese && (!custoAuxiliarInput || !custoAuxiliarInput.value || parseFloat(custoAuxiliarInput.value) <= 0)) {
                formValido = false;
                if (custoAuxiliarInput) {
                    custoAuxiliarInput.style.border = '2px solid red';
                }
            }
        });

        if (!formValido) {
            showToast('Verifique os campos obrigatórios (Natureza para especializados, Custo para próteses).', 'error');
            return;
        }

        const rows = procedimentosModalContainer.querySelectorAll('.procedimento-row');
        rows.forEach(row => {
            const select = row.querySelector('select');
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption.value) return;

            const descricao = row.querySelector('textarea').value;
            const naturezaSelect = row.querySelector('select[name="procedimentos_modal[natureza][]"]');
            const natureza = naturezaSelect ? naturezaSelect.value : '';
            let custoAuxiliar = row.querySelector('input[name="procedimentos_modal[custo_auxiliar][]"]').value || 0;
            const finalizado = row.querySelector('input[type="checkbox"]').checked;
            const status_execucao = finalizado ? 'finalizado' : 'pendente';
            const procedimentoId = selectedOption.value;
            const procedimentoNome = selectedOption.textContent;
            const valor = selectedOption.dataset.valor;
            const categoria = selectedOption.dataset.categoria;
            if (categoria === 'especializado' && (natureza === 'canal' || natureza === 'cirurgia_especializada')) {
                custoAuxiliar = parseFloat(valor) * 0.50;
            }

            criarLinhaProcedimentoPrincipal(procedimentoId, procedimentoNome, 1, valor, local, descricao, status_execucao, custoAuxiliar, null, natureza, categoria);
        });

        fecharModal();
        updateTotalAPagar();
    });

    const form = document.getElementById('form-atendimento');
    form.addEventListener('submit', async function(event) {
        event.preventDefault(); 
        
        const submitButton = form.querySelector('button[type="submit"]');
        const pacienteId   = document.getElementById('paciente_id').value;
        const pacienteNome = document.getElementById('paciente_busca').value;

        if (!pacienteId && !pacienteNome) {
            showToast('É necessário selecionar ou cadastrar um paciente.', 'error');
            return;
        }

        // 1. Verificar pagamento pendente
        if (pacienteId) {
            try {
                const response = await fetch(`${window.__BASE_URL}?rota=atendimentos.verPagPend&paciente_id=${pacienteId}`);
                const data = await response.json();

                if (data.pendente) {
                    alert(`O(A) paciente ${pacienteNome} tem um pagamento pendente de outro procedimento. Necessário finalizar o anterior para Lançar Novos.`);
                    return; // Bloqueia o envio
                }
            } catch (error) {
                console.error('Erro ao verificar pagamento pendente:', error);
                showToast('Não foi possível verificar pagamentos pendentes. Verifique o console.', 'error');
                return; // Bloqueia por segurança
            }
        }
       
        // 2. Prosseguir com o envio do formulário
        submitButton.disabled = true;
        submitButton.textContent = 'Salvando...';

        const formData = new FormData(form);

        try {
            const response = await fetch(window.__BASE_URL + '?rota=atendimentos.salvar', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.sucesso) {
                showToast(result.mensagem, 'success');
                setTimeout(() => {
                    window.location.href = result.redirectUrl || window.__BASE_URL + '?rota=painel';
                }, 1500);
            } else {
                showToast(result.erro || 'Ocorreu um erro desconhecido.', 'error');
                submitButton.disabled = false;
                submitButton.textContent = 'Lançar Atendimento';
            }
        } catch (error) {
            console.error('Fetch Error:', error);
            showToast('Ocorreu um erro de comunicação. Verifique o console para detalhes.', 'error');
            submitButton.disabled = false;
            submitButton.textContent = 'Lançar Atendimento';
        }
    });
});
</script>
