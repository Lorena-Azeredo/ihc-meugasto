<?php
    session_start();
    require_once "includes/verificar_login.php";
    require_once "classes/Conexao.php";
    require_once "classes/Usuario.php";

    $id = $_SESSION['usuario_id'];
    $conn = Conexao::conectar();

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE id = ?");
    $stmt->execute([$id]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    $msg = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usu = new Usuario();
        $usu->atualizarPerfil($id, $_POST['nome'], $_POST['user'], $_POST['senha'], $_POST['meta_g'], $_POST['meta_p']);
        $msg = "Ajustes salvos com sucesso!";
        header("Refresh:1");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ajustes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/menu.php"; ?>
    <main class="area-principal">
        <div class="painel-branco" style="max-width: 600px; margin: 40px auto; padding: 30px; border-radius: 20px;">
            <h1 class="texto-centro">Ajustes</h1><br>
            <?php if($msg) echo "<p style='color:green'>$msg</p>"; ?>
            
            <form method="POST">
                <label>Nome</label>
                <input type="text" name="nome" class="campo" value="<?= htmlspecialchars($u['nome'] ?? '') ?>" required>
                
                <label>Usuário (Login)</label>
                <input type="text" name="user" class="campo" value="<?= htmlspecialchars($u['user'] ?? '') ?>" required>
                
                <label>Senha</label>
                <input type="password" name="senha" class="campo">

                <label>Meta Gasto</label>
                <input type="number" step="0.01" name="meta_g" class="campo" value="<?= $u['meta_gastos'] ?? 0 ?>">

                <label>Meta Poupança</label>
                <input type="number" step="0.01" name="meta_p" class="campo" value="<?= $u['meta_poupanca'] ?? 0 ?>">
                
                <button type="submit" class="botao-primario" style="width:100%; height: 50px; border-radius: 12px;">SALVAR</button>
            </form>
        </div>
    </main>
</body>
</html>