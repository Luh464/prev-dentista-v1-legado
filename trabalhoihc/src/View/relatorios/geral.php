
<div class="card">
    <h2>Relatório Financeiro</h2>

    <form method="GET" action="<?= BASE_URL ?>index.php" class="card" style="margin-top: 1rem;">
        <input type="hidden" name="rota" value="relatorios">
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div class="form-group">
                <label for="inicio">Data Início</label>
                <input type="date" name="inicio" id="inicio" value="<?= $data_inicio ?>">
            </div>
            <div class="form-group">
                <label for="fim">Data Fim</label>
                <input type="date" name="fim" id="fim" value="<?= $data_fim ?>">
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <div class="dashboard-grid" style="margin-top: 2rem;">
        <div class="stat-card">
            <h3>Faturamento Bruto</h3>
            <div class="stat-value">R$ <?= number_format($financas['bruto'] ?? 0, 2, ',', '.') ?></div>
        </div>
        <div class="stat-card" style="border-left-color: var(--danger-color);">
            <h3>Total Despesas</h3>
            <div class="stat-value">R$ <?= number_format($despesas ?? 0, 2, ',', '.') ?></div>
        </div>
        <div class="stat-card" style="border-left-color: var(--success-color);">
            <h3>Lucro Líquido</h3>
            <div class="stat-value">R$ <?= number_format(($financas['liquido'] ?? 0) - ($despesas ?? 0), 2, ',', '.') ?></div>
        </div>
    </div>

    <div class="chart-buttons" style="margin-top: 2rem; text-align: center; margin-bottom: 1rem; display: flex; justify-content: center; gap: 10px;">
        <button id="btnEvolucao" class="btn btn-primary">Ver Evolução Financeira</button>
        <button id="btnPagamentos" class="btn btn-secondary">Ver Distribuição de Pagamentos</button>
    </div>

    <div id="chart-evolucao-container" style="margin-top: 1rem;">
        <h3>Evolução Financeira</h3>
        <canvas id="evolucaoFinanceiraChart" style="max-height: 400px;"></canvas>
    </div>

    <div id="chart-pagamentos-container" style="margin-top: 1rem; display: none;">
        <h3>Distribuição de Pagamentos</h3>
        <canvas id="pagamentosChart" style="max-height: 400px;"></canvas>
    </div>

    <div style="margin-top: 3rem;">
        <h3>Detalhes de Atendimentos</h3>
        <table class="mobile-card-table" style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Paciente</th>
                    <th>Procedimento</th>
                    <th>Valor Bruto</th>
                    <th>Valor Líquido</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($atendimentos as $at): ?>
                <tr>
                    <td data-label = "Data"><?= date('d/m/Y', strtotime($at['data_atendimento'])) ?></td>
                    <td data-label = "Paciente"><?= htmlspecialchars($at['paciente_nome']) ?></td>
                    <td data-label = "Procedimento"><?= htmlspecialchars($at['procedimento'] ?? '') ?></td>
                    <td data-label = "Valor Bruto">R$ <?= number_format($at['valor_bruto'], 2, ',', '.') ?></td>
                    <td data-label = "Lucro Líquido">R$ <?= number_format($at['valor_liquido_clinica'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginação Atendimentos -->
        <?php if ($paginasAt > 1): ?>
        <div style="display: flex; justify-content: flex-end; margin-top: 1rem; gap: 0.5rem;">
            <?php for ($i = 1; $i <= $paginasAt; $i++): ?>
                <?php 
                    $active = $i === $pagina_at ? 'background-color: var(--primary-color); color: white;' : 'background-color: #eee; color: #333;';
                    $queryParams = $_GET;
                    $queryParams['pagina_at'] = $i;
                    $url = '?' . http_build_query($queryParams);
                ?>
                <a href="<?= $url ?>" style="padding: 5px 10px; text-decoration: none; border-radius: 4px; <?= $active ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>

    <div style="margin-top: 3rem;">
        <h3>Detalhes de Despesas</h3>
        <table class="mobile-card-table" style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaDespesas as $dp): ?>
                <tr>
                    <td data-label = "Data"><?= date('d/m/Y', strtotime($dp['data_despesa'])) ?></td>
                    <td data-label = "Descrição"><?= htmlspecialchars($dp['descricao']) ?></td>
                    <td data-label = "Tipo"><?= ucfirst($dp['tipo']) ?></td>
                    <td data-label = "Valor">R$ <?= number_format($dp['valor'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginação Despesas -->
        <?php if ($paginasDe > 1): ?>
        <div style="display: flex; justify-content: flex-end; margin-top: 1rem; gap: 0.5rem;">
            <?php for ($i = 1; $i <= $paginasDe; $i++): ?>
                <?php 
                    $active = $i === $pagina_de ? 'background-color: var(--primary-color); color: white;' : 'background-color: #eee; color: #333;';
                    $queryParams = $_GET;
                    $queryParams['pagina_de'] = $i;
                    $url = '?' . http_build_query($queryParams);
                ?>
                <a href="<?= $url ?>" style="padding: 5px 10px; text-decoration: none; border-radius: 4px; <?= $active ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnEvolucao = document.getElementById('btnEvolucao');
    const btnPagamentos = document.getElementById('btnPagamentos');
    const evolucaoContainer = document.getElementById('chart-evolucao-container');
    const pagamentosContainer = document.getElementById('chart-pagamentos-container');

    // Botão para mostrar o gráfico de evolução
    btnEvolucao.addEventListener('click', () => {
        evolucaoContainer.style.display = 'block';
        pagamentosContainer.style.display = 'none';
        
        btnEvolucao.classList.add('btn-primary');
        btnEvolucao.classList.remove('btn-secondary');
        
        btnPagamentos.classList.add('btn-secondary');
        btnPagamentos.classList.remove('btn-primary');
    });

    // Botão para mostrar o gráfico de pagamentos
    btnPagamentos.addEventListener('click', () => {
        evolucaoContainer.style.display = 'none';
        pagamentosContainer.style.display = 'block';

        btnPagamentos.classList.add('btn-primary');
        btnPagamentos.classList.remove('btn-secondary');

        btnEvolucao.classList.add('btn-secondary');
        btnEvolucao.classList.remove('btn-primary');
    });
    const ctx = document.getElementById('evolucaoFinanceiraChart').getContext('2d');
    const evolucaoChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [
                {
                    label: 'Faturamento Bruto',
                    data: <?= json_encode($faturamentoData) ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.1
                },
                {
                    label: 'Despesas',
                    data: <?= json_encode($despesaData) ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                    tension: 0.1
                },
                {
                    label: 'Lucro Líquido',
                    data: <?= json_encode($lucroLiquidoData) ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });

    <?php if (!empty($pagamentoData)): ?>
    const ctxPagamentos = document.getElementById('pagamentosChart').getContext('2d');
    const pagamentosChart = new Chart(ctxPagamentos, {
        type: 'pie',
        data: {
            labels: <?= json_encode($pagamentoLabels) ?>,
            datasets: [{
                label: 'Total R$',
                data: <?= json_encode($pagamentoData) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',  // Vermelho para Dinheiro/Pix
                    'rgba(54, 162, 235, 0.7)', // Azul para Débito
                    'rgba(255, 206, 86, 0.7)', // Amarelo para Crédito
                    'rgba(75, 192, 192, 0.7)', // Verde para outros
                    'rgba(153, 102, 255, 0.7)',// Roxo
                    'rgba(255, 159, 64, 0.7)'  // Laranja
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: false,
                    text: 'Formas de Pagamento no Período'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed);
                            }
                            return label;
                        },
                        footer: function(tooltipItems) {
                            let sum = tooltipItems[0].chart.getDatasetMeta(0).total;
                            let percentage = (tooltipItems[0].parsed * 100 / sum).toFixed(2) + '%';
                            return 'Porcentagem: ' + percentage;
                        }
                    }
                }
            }
        }
    });
    <?php endif; ?>
});
</script>

<?php  ?>
