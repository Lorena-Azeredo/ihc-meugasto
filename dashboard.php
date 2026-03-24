<?php
    session_start();
    require_once "includes/verificar_login.php";
    require_once "classes/Receita.php";
    require_once "classes/Despesa.php";
    require_once "classes/Conexao.php";

    $usuario_id = $_SESSION['usuario_id'];
    $nome_user = $_SESSION['usuario_nome'] ?? 'Usuário';

    $conn = Conexao::conectar();

    //Busca metas do usuário
    $stmt = $conn->prepare("SELECT meta_gastos, meta_poupanca FROM usuario WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $metas = $stmt->fetch(PDO::FETCH_ASSOC);

    $meta_gastos = (float)($metas['meta_gastos'] ?? 0);
    $meta_poupanca = (float)($metas['meta_poupanca'] ?? 0);

    //Busca o total de receita e despesa
    $r = new Receita();
    $d = new Despesa();
    $totalReceitas = (float)$r->total($usuario_id);
    $totalDespesas = (float)$d->total($usuario_id);
    $saldoAtual = $totalReceitas - $totalDespesas;

    $ultimasDespesas = $d->ultimas($usuario_id);

    //obtem dados para o grafico
    $stmt_g = $conn->prepare("SELECT categoria, SUM(valor) as total FROM despesa WHERE id_usuario = ? GROUP BY categoria");
    $stmt_g->execute([$usuario_id]);
    $dados_grafico = $stmt_g->fetchAll(PDO::FETCH_ASSOC);

    $cat_nomes = [];
    $cat_valores = [];
    foreach($dados_grafico as $item) {
        $cat_nomes[] = $item['categoria'] ?: 'Geral';
        $cat_valores[] = (float)$item['total'];
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MeuGasto - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/menu.php"; ?>

    <main class="area-principal">
        <header style="margin-bottom: 30px;">
            <h1>Dashboard</h1>
            <p style="color: var(--texto-primario);">Olá, <?= htmlspecialchars($nome_user) ?>!</p>
        </header>

        <div class="grade-resumo">
            <div class="card-colorido bg-azul"><span>Saldo Geral</span><h2>R$ <?= number_format($saldoAtual, 2, ',', '.') ?></h2></div>
            <div class="card-colorido bg-verde"><span>Receitas</span><h2>R$ <?= number_format($totalReceitas, 2, ',', '.') ?></h2></div>
            <div class="card-colorido bg-vermelho"><span>Despesas</span><h2>R$ <?= number_format($totalDespesas, 2, ',', '.') ?></h2></div>
        </div>

        <div class="layout-duas-colunas">
            
            <div class="coluna-esquerda">
                <div class="painel-branco">
                    <h2>Distribuição por Categoria</h2>
                    <div style="height: 250px; margin-top: 20px;">
                        <canvas id="graficoDespesas"></canvas>
                    </div>
                </div>

                <div class="painel-branco">
                    <h2>Gastos Recentes</h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        <?php foreach($ultimasDespesas as $g): ?>
                        <tr>
                            <td style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                                <strong><?= htmlspecialchars($g['descricao']) ?></strong><br>
                                <small style="color: #64748b;"><?= isset($g['data']) ? date('d/m/Y', strtotime($g['data'])) : '--/--/----' ?></small>
                            </td>
                            <td style="text-align: right; color: var(--perigo); font-weight: 700;">
                                - R$ <?= number_format($g['valor'], 2, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>

            <div class="coluna-direita">
                <div class="painel-branco">
                    <h2>Suas Metas</h2>

                    <div style="margin-top: 25px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                            <span>Meta Poupança</span>
                            <strong>R$ <?= number_format($meta_poupanca, 2, ',', '.') ?></strong>
                        </div>
                        <div style="height: 10px; background: #f1f5f9; border-radius: 10px; margin: 10px 0; overflow: hidden;">
                            <?php $p = ($meta_poupanca > 0) ? min(100, ($saldoAtual / $meta_poupanca) * 100) : 0; ?>
                            <div style="width: <?= $p ?>%; height: 100%; background: #10b981;"></div>
                        </div>
                    </div>

                    <div style="margin-top: 30px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                            <span>Limite de Gastos</span>
                            <strong>R$ <?= number_format($meta_gastos, 2, ',', '.') ?></strong>
                        </div>
                        <div style="height: 10px; background: #f1f5f9; border-radius: 10px; margin: 10px 0; overflow: hidden;">
                            <?php 
                                $pg = ($meta_gastos > 0) ? ($totalDespesas / $meta_gastos) * 100 : 0; 
                                $cor = ($totalDespesas > $meta_gastos) ? '#ef4444' : '#3b82f6';
                            ?>
                            <div style="width: <?= min(100, $pg) ?>%; height: 100%; background: <?= $cor ?>;"></div>
                        </div>
                        <small style="color: <?= ($totalDespesas > $meta_gastos) ? '#ef4444' : '#64748b' ?>;">
                            <?= ($totalDespesas > $meta_gastos) ? "Limite excedido!" : round($pg)."% usado" ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('graficoDespesas'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($cat_nomes) ?>,
                datasets: [{
                    data: <?= json_encode($cat_valores) ?>,
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    </script>
</body>
</html>