<?php
    session_start();
    require_once "classes/Usuario.php";

    $msg = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $user  = $_POST['user'];
        $senha = $_POST['senha'];

        $u     = new Usuario();
        $dados = $u->login($user, $senha);

        if ($dados) {
            $_SESSION['usuario_id']   = $dados['id'];
            $_SESSION['usuario_nome'] = $dados['nome'];
            header("Location: dashboard.php");
            exit;
        } else {
            $msg = "Usuário ou senha inválidos";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MeuGasto</title>
    <link rel="stylesheet" href="css/style.css?v=1.4">
</head>
<body class="pagina-login">

    <div class="card-login">
        <h1 class="texto-centro">MeuGasto</h1>

        <?php if ($msg) : ?>
            <div class="alerta-erro">
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label for="user">Usuário</label>
            <input type="text" id="user" name="user" required class="input-padrao" placeholder="Seu usuário">

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required class="input-padrao" minlength="4" placeholder="••••••••">

            <button type="submit" class="botao-primario">
                Entrar
            </button>
        </form>

        <p class="texto-centro">
            Não possui conta? 
            <a href="cadastro.php">Criar conta</a>
        </p>
    </div>

</body>
</html>