<?php
require_once "Conexao.php";

class Despesa{
    //adiciona despesa ao banco de dados
    public function adicionar($descricao, $categoria, $valor, $data, $id_usuario) {
        $conn = Conexao::conectar();

        $sql = $conn->prepare("INSERT INTO despesa (descricao, categoria, valor, data, id_usuario) VALUES (?, ?, ?, ?, ?)");    
        $sql->execute([
            $descricao, 
            $categoria,
            $valor,
            $data,
            $id_usuario
        ]);
    }

    //consulta o total de despesa do usuario
    public function total($id){
        $conn = Conexao::conectar();
        $sql = $conn->prepare("select sum(valor) total from despesa where id_usuario=?");
        $sql->execute([$id]);
        $r = $sql->fetch();
        return $r['total'] ?? 0;

    }

    //consulta as ultimas despesas do usuario
    public function ultimas($id_usuario) {
        $conn = Conexao::conectar();
        $sql = $conn->prepare("SELECT descricao, valor, data FROM despesa WHERE id_usuario = ? ORDER BY data DESC LIMIT 5");
        $sql->execute([$id_usuario]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //consulta no banco de dados despesas em determinado mes
    public function buscarPorMes($id_usuario, $mes_ano) {
        $conn = Conexao::conectar();
        $sql = $conn->prepare("SELECT *, 'despesa' as tipo FROM despesa WHERE id_usuario = ? AND data LIKE ? ORDER BY data DESC");
        $sql->execute([$id_usuario, $mes_ano . "%"]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
