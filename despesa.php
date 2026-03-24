<?php
    session_start();
    require_once "includes/verificar_login.php";
    require_once "classes/Despesa.php";

    $msg = "";
    //para cadastrar nova despesa
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $d = new Despesa();
        
        $descricao = $_POST['descricao'];
        $categoria = $_POST['categoria'];
        $valor     = $_POST['valor'];
        $data      = $_POST['data'];
        $usuario   = $_SESSION['usuario_id'];

        $d->adicionar($descricao, $categoria, $valor, $data, $usuario);
        $msg = "Despesa salva com sucesso!";
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MeuGasto - Nova Despesa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/menu.php"; ?>

    <main class="area-principal">
        <header style="margin-bottom: 40px;">
            <h1 style="font-weight: 800;">Nova Despesa</h1>
            <p style="color: var(--texto-secundario);">Preencha os detalhes do seu gasto.</p>
        </header>

        <div class="painel-branco" style="max-width: 550px; margin: 40px auto;">
            
            <?php if($msg != ""): ?>
                <div style="background: var(--sucesso); color: white; padding: 15px; border-radius: 12px; margin-bottom: 25px; text-align: center; font-weight: 600;">
                    <?= $msg ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label>Descrição</label>
                <input type="text" name="descricao" class="campo" placeholder="Ex: Supermercado mensal" required>

                <label>Categoria</label>
                <select name="categoria" class="campo" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <option value="Alimentação">Alimentação</option>
                    <option value="Moradia">Moradia</option>
                    <option value="Lazer">Lazer</option>
                    <option value="Transporte">Transporte</option>
                    <option value="Saúde">Saúde</option>
                    <option value="Educação">Educação</option>
                    <option value="Outros">Outros</option>
                </select>
                
                <label>Data</label>
                <input type="date" name="data" class="campo" value="<?= date('Y-m-d') ?>" required>

                <label>Valor (R$)</label>
                <input type="number" step="0.01" name="valor" class="campo" placeholder="0,00" required>

                <button type="submit" class="botao-primario" style="background-color: var(--perigo);">
                    SALVAR DESPESA
                </button>
            </form>
        </div>
    </main>
</body>
</html>