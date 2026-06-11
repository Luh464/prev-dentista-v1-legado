    </main><!-- Fim .container -->

    <footer style="text-align: center; padding: 2rem 0; margin-top: 2rem; border-top: 1px solid var(--border-color); color: var(--white-color); background-color: var(--primary-color); margin-top: 10px;">
        <p>&copy; <?= date('Y') ?> Clínica Prev Dentistas. Todos os direitos reservados.</p>
        <div style="margin-top: 10px; font-size: 0.8rem; opacity: 0.7;">
            Responsável Técnico: Dra. Luciana Farias
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectPagamento = document.getElementById('forma_pagamento');
            const divParcelas     = document.getElementById('div_parcelas');

            if (selectPagamento) {
                selectPagamento.addEventListener('change', function () {
                    divParcelas.style.display = (this.value === 'credito') ? 'block' : 'none';
                });
                divParcelas.style.display = (selectPagamento.value === 'credito') ? 'block' : 'none';
            }
        });
    </script>
</body>
</html>
