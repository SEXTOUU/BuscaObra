CREATE DATABASE IF NOT EXISTS buscaobra;
USE buscaobra;

-- Apagando as tabelas se já existirem (ordem ajustada para respeitar as dependências)
DROP TABLE IF EXISTS avaliacoes;
DROP TABLE IF EXISTS profissionais;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS cliente;
DROP TABLE IF EXISTS usertipo;

-- Tabela Usertipo (define tipos de usuário)
CREATE TABLE usertipo (
    usertipo_id INT PRIMARY KEY AUTO_INCREMENT,
    usertipo_nome VARCHAR(50) NOT NULL UNIQUE
);

-- Tabela Cliente (dados gerais de clientes)
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

-- Tabela Profissionais (extensão de cliente para dados específicos de profissionais)
CREATE TABLE profissionais (
    pro_id INT PRIMARY KEY AUTO_INCREMENT,
    cli_id INT UNIQUE,  -- Relaciona com 'cliente' de forma 1:1
    pro_profissao VARCHAR(50) NOT NULL,
    pro_telefone CHAR(14),
    pro_descricao TEXT,
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id) ON DELETE CASCADE
);

-- Tabela Admins (extensão de cliente para dados específicos de admins)
CREATE TABLE admins (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    cli_id INT UNIQUE,  -- Relaciona com 'cliente' de forma 1:1
    admin_departamento VARCHAR(50) NOT NULL,
    admin_cargo VARCHAR(50) NOT NULL,
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id) ON DELETE CASCADE
);

-- Tabela Avaliacoes (armazena avaliações e comentários dos profissionais)
CREATE TABLE avaliacoes (
    avaliacao_id INT PRIMARY KEY AUTO_INCREMENT,
    profissional_id INT NOT NULL,
    cliente_id INT NOT NULL,
    nota INT CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (profissional_id) REFERENCES profissionais(pro_id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES cliente(cli_id) ON DELETE CASCADE
);

-- Inserindo tipos de usuários
INSERT INTO usertipo (usertipo_nome) VALUES ('usuario');
INSERT INTO usertipo (usertipo_nome) VALUES ('profissional');
INSERT INTO usertipo (usertipo_nome) VALUES ('moderador');
INSERT INTO usertipo (usertipo_nome) VALUES ('admin');
