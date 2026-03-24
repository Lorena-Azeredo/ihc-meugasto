<?php
    session_start();
    require_once "includes/verificar_login.php";
    require_once "classes/Receita.php";

    $msg = "";
    //para cadastrar nova despesa
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $r = new Receita();
        
        $r->adicionar(
            $_POST['descricao'], 
            $_POST['categoria'], 
            $_POST['valor'], 
            $_POST['data'], 
            $_SESSION['usuario_id']
        );
        $msg = "Receita salva com sucesso!";
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MeuGasto - Nova Receita</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/menu.php"; ?>

    <main class="area-principal">
        <header style="margin-bottom: 40px;">
            <h1 style="font-weight: 800;">Nova Receita</h1>
            <p style="color: var(--texto-secundario);">Registe as suas entradas de capital.</p>
        </header>

        <div class="painel-branco" style="max-width: 550px; margin: 0 auto;">
            
            <?php if($msg != ""): ?>
                <div style="background: var(--sucesso); color: white; padding: 15px; border-radius: 12px; margin-bottom: 25px; text-align: center; font-weight: 600;">
                    <?= $msg ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label>Descrição</label>
                <input type="text" name="descricao" class="campo" placeholder="Ex: Salário Mensal" required>

                <label>Categoria</label>
                <select name="categoria" class="campo" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <option value="Salário">Salário</option>
                    <option value="Freelance">Freelance</option>
                    <option value="Investimentos">Investimentos</option>
                    <option value="Presente">Presente</option>
                    <option value="Venda">Venda</option>
                    <option value="Outros">Outros</option>
                </select>
                
                <label>Data</label>
                <input type="date" name="data" class="campo" value="<?= date('Y-m-d') ?>" required>

                <label>Valor (R$)</label>
                <input type="number" step="0.01" name="valor" class="campo" placeholder="0,00" required>

                <button type="submit" class="botao-primario" style="background-color: var(--sucesso);">
                    SALVAR RECEITA
                </button>
            </form>
        </div>
    </main>
</body>
</html>