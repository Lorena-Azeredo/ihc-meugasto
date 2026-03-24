<?php
require_once "Conexao.php";

class Usuario{
    //verifica se existe usuario com esse user e senha
    public function login($user,$senha){

    $conn = Conexao::conectar();

    $sql = "SELECT * FROM usuario WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if($usuario && password_verify($senha,$usuario['senha'])){
        return $usuario;
    }

    return false;
}

    //cadastra novo usuario no banco de dados
    public function cadastrar($nome, $user, $senha){
        $conn = Conexao::conectar();
        $sql = $conn->prepare("SELECT id FROM usuario WHERE user = ?");
        $sql->execute([$user]);

        if ($sql->fetch()) {
            return false;
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = $conn->prepare("
            INSERT INTO usuario (nome, user, senha)
            VALUES (?, ?, ?)
        ");

        $sql->execute([
            $nome,
            $user,
            $senhaHash
        ]);

        return true;
    }

    public function atualizarPerfil($id, $nome, $user, $senha, $meta_g, $meta_p){
        $conn = Conexao::conectar();
        if (!empty($senha)) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            
            $sql = $conn->prepare("
                UPDATE usuario
                SET nome=?, user=?, senha=?, meta_gastos=?, meta_poupanca=?
                WHERE id=?
            ");

            $sql->execute([
                $nome,
                $user,
                $senhaHash,
                $meta_g,
                $meta_p,
                $id
            ]);

        } 
        else {
            $sql = $conn->prepare("
                UPDATE usuario
                SET nome=?, user=?, meta_gastos=?, meta_poupanca=?
                WHERE id=?
            ");

            $sql->execute([
                $nome,
                $user,
                $meta_g,
                $meta_p,
                $id
            ]);
        }

        $_SESSION['usuario_nome'] = $nome;
    }
}
?>