<?php
require_once "Conexao.php";

class Receita{
    //adiciona nova receita no banco de dados
    public function adicionar($descricao, $categoria, $valor, $data, $id_usuario) {
        $conn = Conexao::conectar();
        $sql = $conn->prepare("INSERT INTO receita (descricao, categoria, valor, data, id_usuario) VALUES (?, ?, ?, ?, ?)");
        $sql->execute([$descricao, $categoria, $valor, $data, $id_usuario]);
    }

    //consuta o total de receita do usuario
    public function total($id){
        $conn = Conexao::conectar();

        $sql = $conn->prepare("select sum(valor) total from receita where id_usuario=?");
        $sql->execute([$id]);

        $r = $sql->fetch();

        return $r['total'] ?? 0;
    }

    //consulta no banco de dados receitas em determinado mes
    public function buscarPorMes($id_usuario, $mes_ano) {
        $conn = Conexao::conectar();
        // $mes_ano vem no formato "2026-03"
        $sql = $conn->prepare("SELECT *, 'receita' as tipo FROM receita WHERE id_usuario = ? AND data LIKE ? ORDER BY data DESC");
        $sql->execute([$id_usuario, $mes_ano . "%"]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
