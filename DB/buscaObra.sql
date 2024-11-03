CREATE DATABASE IF NOT EXISTS buscaobra;
USE buscaobra;

DROP TABLE IF EXISTS usuario;
DROP TABLE IF EXISTS funcionario;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS cliente;
DROP TABLE IF EXISTS UserTipo;

-- Tabela Usertipo
CREATE TABLE usertipo (
    usertipo_id INT PRIMARY KEY AUTO_INCREMENT,
    usertipo_nome VARCHAR(255) NOT NULL
);

-- Tabela Cliente
CREATE TABLE cliente (
    cli_id INT PRIMARY KEY AUTO_INCREMENT,
    cli_nome VARCHAR(255) NOT NULL,
    cli_email VARCHAR(255) UNIQUE NOT NULL,
    cli_senha VARCHAR(100) NOT NULL,
    cli_data_nascimento DATE,
    cli_bairro VARCHAR(50),
    cli_rua VARCHAR(155),
    cli_numero_rua VARCHAR(20),
    cli_cep CHAR(8),
    cli_tipo INT, 
    FOREIGN KEY (cli_tipo) REFERENCES usertipo(usertipo_id)
);

-- Tabela Usuario com foreign key para Cliente
CREATE TABLE usuario (
    usu_id INT PRIMARY KEY AUTO_INCREMENT,
    usu_data_naci DATE,
    usu_telefone CHAR(14),
    FOREIGN KEY (usu_id) REFERENCES cliente(cli_id)
);

-- Tabela Funcionario com foreign key para Cliente
CREATE TABLE funcionario (
    fun_id INT PRIMARY KEY AUTO_INCREMENT,
    fun_profissao VARCHAR(50) NOT NULL,
    fun_telefone CHAR(14),
    FOREIGN KEY (fun_id) REFERENCES cliente(cli_id)
);

-- Tabela Admins com foreign key para Cliente
CREATE TABLE admins (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    admin_departamento VARCHAR(50) NOT NULL,
    admin_cargo VARCHAR(50) NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES cliente(cli_id)
);

/* Temporario: Tabela para formas de pagamento
CREATE TABLE forma_pagamento ( 
    fp_id INT PRIMARY KEY AUTO_INCREMENT,
    fp_nome VARCHAR(100) NOT NULL
);
*/

-- INSERTS
INSERT INTO usertipo (usertipo_nome) VALUES ('usuario');
INSERT INTO usertipo (usertipo_nome) VALUES ('funcionario');
INSERT INTO usertipo (usertipo_nome) VALUES ('admin');
