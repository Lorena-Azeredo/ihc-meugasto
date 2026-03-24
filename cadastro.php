<?php
    require_once "classes/Usuario.php";

    $msg="";

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $nome = $_POST['nome'];
    $user = $_POST['user'];
    $senha = $_POST['senha'];

    if(strlen($senha) < 4){
        $msg = "A senha deve ter no mínimo 4 caracteres.";
    } else {
        $usuario = new Usuario();

        $login_valido = $usuario->cadastrar($nome,$user,$senha);

        if ($login_valido) {
            $msg = "Conta criada com sucesso!";
            header("Location: dashboard.php");
            exit;
        } else {
            $msg = "Erro ao cadastrar.";
        }

    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - MeuGasto</title>
    <link rel="stylesheet" href="css/style.css?v=1.4">
</head>
<body class="pagina-login">
    <div class="card-login">
        <h2 class="texto-centro">Criar conta</h2>

        <form method="post">
            <label for="nome">Nome</label>
            <input type="text" name="nome" class="input-padrao" placeholder="Digite seu nome">

            <label for="user">Usuário</label>
            <input type="text" name="user" class="input-padrao" placeholder="Digite seu user">

            <label for="user">Senha</label>
            <input type="password" name="senha" class="input-padrao" minlength="4" placeholder="Digite sua senha">

            <button type="summit" class="botao-primario">Cadastrar</button>
        </form>

        <p class="texto-centro"><?php echo $msg ?></p>
    </div>
</body>
</html>
