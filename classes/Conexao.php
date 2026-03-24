<?php
class Conexao{
    public static function conectar(){
        return new PDO(
            "mysql:host=localhost;port=3307;dbname=meugasto;charset=utf8",
            "root",
            ""
        );
    }
}
?>
