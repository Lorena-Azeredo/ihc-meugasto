
create database meugasto;

use teste1;

create table usuario(
id int auto_increment primary key,
nome varchar(100),
user varchar(50) unique,
senha varchar(255)
);

create table receita(
id int auto_increment primary key,
descricao varchar(100),
valor decimal(10,2),
data date,
id_usuario int
);

create table despesa(
id int auto_increment primary key,
descricao varchar(100),
valor decimal(10,2),
data date,
id_usuario int
);
