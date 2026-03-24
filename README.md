# Meugasto

Sistema web para **controle de gastos pessoais**, permitindo registrar receitas, despesas e acompanhar a vida financeira de forma simples e visual.


## 📌 Sobre o projeto

O **MeuGasto** foi desenvolvido como projeto da disciplina de Interação Humano-Computador (IHC) do 4º período do curso de Sistemas de Informação, com o objetivo de ajudar no controle financeiro diário, oferecendo uma interface intuitiva e funcionalidades essenciais para organização de gastos.



## ⚙️ Como o sistema funciona

O sistema é dividido em algumas partes principais:

### 🔐 Autenticação

O usuário pode criar uma conta e fazer login.
As senhas são armazenadas de forma segura utilizando criptografia.



### 💸 Registro de despesas

O usuário pode cadastrar gastos informando:

* descrição
* categoria
* data
* valor

Essas informações são salvas no banco de dados e utilizadas no dashboard.



### 💰 Registro de receitas

Permite registrar entradas financeiras, como:

* salário
* freelances
* vendas



### 📊 Dashboard

A tela principal mostra:

* saldo atual
* total de receitas
* total de despesas
* últimos gastos cadastrados

Também exibe um gráfico de distribuição por categoria.



### 📈 Gráficos

Os gráficos são gerados dinamicamente com JavaScript utilizando **Chart.js**, mostrando como os gastos estão distribuídos.



### 📄 Relatório

O sistema permite visualizar todas as movimentações de um mês específico, organizadas por data.



### 🎯 Metas financeiras

O usuário pode definir:

* limite de gastos
* meta de economia

O sistema mostra o progresso através de barras visuais.



## 🎯 Objetivo

Facilitar o controle financeiro pessoal de forma prática, ajudando o usuário a:

* entender seus gastos
* organizar suas finanças
* tomar melhores decisões
