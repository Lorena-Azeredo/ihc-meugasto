<?php
    session_start();
    require_once "includes/verificar_login.php";
    require_once "classes/Receita.php";
    require_once "classes/Despesa.php";
    require_once "classes/Conexao.php";

    $usuario_id = $_SESSION['usuario_id'];
    $mes_selecionado = $_GET['mes'] ?? date('Y-m'); 

    $conn = Conexao::conectar();
    $r = new Receita();
    $d = new Despesa();

    //consulta dados para historico de movimentacao
    $receitas = $r->buscarPorMes($usuario_id, $mes_selecionado);
    $despesas = $d->buscarPorMes($usuario_id, $mes_selecionado);

    $totalR = array_sum(array_column($receitas, 'valor'));
    $totalD = array_sum(array_column($despesas, 'valor'));
    $saldoMes = $totalR - $totalD;

    $movimentacoes = array_merge($receitas, $despesas);
    usort($movimentacoes, function($a, $b) {
        return strtotime($b['data']) - strtotime($a['data']);
    });

    //consulta despesas para o grafico
    $sql_g = "SELECT categoria, SUM(valor) as total FROM despesa 
            WHERE id_usuario = ? AND data LIKE ? 
            GROUP BY categoria";
    $stmt_g = $conn->prepare($sql_g);
    $stmt_g->execute([$usuario_id, $mes_selecionado . "%"]);
    $dados_categoria = $stmt_g->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    $valores = [];

    foreach($dados_categoria as $dado) {
        $labels[] = $dado['categoria'] ?: 'Outros';
        $valores[] = (float)$dado['total'];
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Mensal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/menu.php"; ?>

    <main class="area-principal">
        <header style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
            <div>
                <h1>Relatório Mensal</h1>
                <p style="color: var(--texto-secundario);">Período: <?= date('m/Y', strtotime($mes_selecionado)) ?></p>
            </div>
            
            <form method="GET">
                <input type="month" name="mes" value="<?= $mes_selecionado ?>" onchange="this.form.submit()" style="border: 2px solid #e2e8f0; padding: 10px; border-radius: 10px; font-family: inherit;">
            </form>
        </header>

        <div class="grade-resumo">
            <div class="card-colorido bg-verde">
                    <span>Entradas</span>
                    <h2>R$ <?= number_format($totalR, 2, ',', '.') ?></h2>
            </div>

            <div class="card-colorido bg-vermelho">
                <span">Saídas</span>
                <h2>R$ <?= number_format($totalD, 2, ',', '.') ?></h2>
            </div>

            <div class="card-colorido bg-azul">
                <span>Saldo do Mês</span>
                <h2>R$ <?= number_format($saldoMes, 2, ',', '.') ?></h2>
            </div>
        </div>

        <div class="painel-branco" style="padding: 25px; border-radius: 20px; margin-bottom: 30px;">
            <h2 style="margin-bottom: 20px;">Histórico de Movimentações</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; color: #64748b; font-size: 0.85rem; border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 10px;">DATA</th>
                        <th>DESCRIÇÃO</th>
                        <th>CATEGORIA</th>
                        <th style="text-align: right;">VALOR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($movimentacoes)): ?>
                        <tr><td colspan="4" style="padding: 30px; text-align: center; color: #94a3b8;">Nenhum registro em <?= date('m/Y', strtotime($mes_selecionado)) ?></td></tr>
                    <?php else: ?>
                        <?php foreach($movimentacoes as $m): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 15px 10px;"><?= date('d/m/Y', strtotime($m['data'])) ?></td>
                            <td style="font-weight: 600; color: var(--sidebar);">
                                <span class="material-symbols-rounded" style="vertical-align: middle; font-size: 1.2rem; color: <?= $m['tipo'] == 'receita' ? '#10b981' : '#ef4444' ?>;">
                                    <?= $m['tipo'] == 'receita' ? 'expand_less' : 'expand_more' ?>
                                </span>
                                <?= htmlspecialchars($m['descricao']) ?>
                            </td>
                            <td><span style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; color: #475569; font-weight: 600;"><?= htmlspecialchars($m['categoria']) ?></span></td>
                            <td style="text-align: right; font-weight: 700; color: <?= $m['tipo'] == 'receita' ? '#10b981' : '#ef4444' ?>;">
                                <?= $m['tipo'] == 'receita' ? '+' : '-' ?> R$ <?= number_format($m['valor'], 2, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="painel-branco" style="padding: 25px; border-radius: 20px;">
            <h3 style="margin-bottom: 20px;">Gastos por Categoria</h3>
            <?php if(empty($valores)): ?>
                <p style="color: #94a3b8; text-align: center; padding: 20px;">Sem gastos para gerar o gráfico.</p>
            <?php else: ?>
                <div style="height: 300px;">
                    <canvas id="graficoDespesasCategoria"></canvas>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('graficoDespesasCategoria');
        if(ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: 'Total Gasto (R$)',
                        data: <?= json_encode($valores) ?>,
                        backgroundColor: '#3b82f6',
                        borderRadius: 8,
                        barThickness: 35
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    </script>
</body>
</html>