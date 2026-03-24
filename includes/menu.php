<!DOCTYPE html>
<html lang="pt-br">
<body>
    <nav class="menu-lateral">
        <div class="logo-sistema">
            <img src="img/icone_carteira.png"> MeuGasto
        </div>
        <div class="navegacao">
            <?php $p = basename($_SERVER['PHP_SELF']); ?>
            
            <a href="dashboard.php" class="<?= $p == 'dashboard.php' ? 'ativo' : '' ?>">
                <img src="img/icone_dashboard.png"> Dashboard
            </a>

            <a href="despesa.php" class="<?= $p == 'despesa.php' ? 'ativo' : '' ?>">
                <img src="img/icone_despesa.png"> Nova Despesa
            </a>
            
            <a href="receita.php" class="<?= $p == 'receita.php' ? 'ativo' : '' ?>">
                <img src="img/icone_receita.png"> Nova Receita
            </a>
            
            <a href="relatorio.php" class="<?= $p == 'relatorio.php' ? 'ativo' : '' ?>">
                <img src="img/icone_relatorio.png"> Relatório
            </a>

            <a href="ajuste.php" class="<?= $p == 'ajuste.php' ? 'ativo' : '' ?>">
                <img src="img/icone_ajuste.png"> Ajuste
            </a>
            
            <a href="logout.php" style="color: #ef4444;">
                <img src="img/icone_sair.png"> Sair
            </a>
        </div>
    </nav>
</body>
</html>
