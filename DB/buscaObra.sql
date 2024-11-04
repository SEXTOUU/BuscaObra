CREATE DATABASE IF NOT EXISTS buscaobra;
USE buscaobra;

DROP TABLE IF EXISTS profissionais;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS cliente;
DROP TABLE IF EXISTS usertipo;

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

-- Tabela Funcionario com foreign key para Cliente
CREATE TABLE profissionais (
    pro_id INT PRIMARY KEY AUTO_INCREMENT,
    pro_nome VARCHAR(255),
    pro_email VARCHAR(255) UNIQUE,
    pro_profissao VARCHAR(50) NOT NULL,
    pro_telefone CHAR(14),
    FOREIGN KEY (pro_id) REFERENCES cliente(cli_id)
);

-- Tabela Admins com foreign key para Cliente
CREATE TABLE admins (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    admin_departamento VARCHAR(50) NOT NULL,
    admin_cargo VARCHAR(50) NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES cliente(cli_id)
);

-- INSERTS
INSERT INTO usertipo (usertipo_nome) VALUES ('usuario');
INSERT INTO usertipo (usertipo_nome) VALUES ('profissional');
INSERT INTO usertipo (usertipo_nome) VALUES ('moderador');
INSERT INTO usertipo (usertipo_nome) VALUES ('admin');
