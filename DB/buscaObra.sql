CREATE DATABASE IF NOT EXISTS buscaobra;
USE buscaobra;

-- Apagando as tabelas se já existirem (ordem ajustada para respeitar as dependências)
DROP TABLE IF EXISTS avaliacoes;
DROP TABLE IF EXISTS profissionais;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS cliente;
DROP TABLE IF EXISTS usertipo;
DROP TABLE IF EXISTS profissoes;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS planos;

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
    imagem VARCHAR(255),
    cli_tipo INT NOT NULL,
    FOREIGN KEY (cli_tipo) REFERENCES usertipo(usertipo_id)
);

alter table cliente add column cli_telefone char(14);

-- Tabela Categorias (categorias de profissões, ex: Construção, Reparos, etc.)
CREATE TABLE categorias (
    categoria_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE
);

-- Tabela Profissoes (tipos de profissão que serão usadas nos filtros)
CREATE TABLE profissoes (
    profissao_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE,
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(categoria_id) ON DELETE SET NULL
);

-- Tabela Planos (define os planos de assinatura para profissionais)
CREATE TABLE planos (
    plano_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE,
    prioridade INT DEFAULT 0 CHECK (prioridade >= 0)  -- Define a prioridade do plano
);

-- Tabela Profissionais (extensão de cliente para dados específicos de profissionais)
CREATE TABLE profissionais (
    pro_id INT PRIMARY KEY AUTO_INCREMENT,
    cli_id INT UNIQUE NOT NULL,  -- Relaciona com 'cliente' de forma 1:1
    profissao_id INT NOT NULL,   -- Relaciona com a tabela 'profissoes'
    pro_nome varchar(255),
    pro_telefone CHAR(14),
    pro_email varchar(255),
    pro_profissao varchar(255),
    imagem VARCHAR(255),
    pro_descricao TEXT,
    pro_foto VARCHAR(255),
    plano_id INT,  -- Plano do profissional
    avaliacao_media DECIMAL(3,2) DEFAULT 0.00,  -- Média de avaliação dos clientes
    localidade VARCHAR(100),  -- Local onde o profissional atua
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id) ON DELETE CASCADE,
    FOREIGN KEY (profissao_id) REFERENCES profissoes(profissao_id),
    FOREIGN KEY (plano_id) REFERENCES planos(plano_id)

);

-- Tabela Admins (extensão de cliente para dados específicos de admins)
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    cli_id INT NOT NULL,
    admin_departamento VARCHAR(100),
    admin_cargo VARCHAR(100),
    admin_senha VARCHAR(255),
    nivel_acesso TINYINT DEFAULT 1,
    status TINYINT DEFAULT 1,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_modificacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cli_id) REFERENCES cliente(cli_id)
);


-- Tabela Avaliacoes (armazena avaliações e comentários dos profissionais)
CREATE TABLE avaliacoes (
    avaliacao_id INT PRIMARY KEY AUTO_INCREMENT,
    profissional_id INT NOT NULL,
    cliente_id INT NOT NULL,
    nota INT NOT NULL CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (profissional_id) REFERENCES profissionais(pro_id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES cliente(cli_id) ON DELETE CASCADE
);

-- Tabela Log
CREATE TABLE log (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    log_usuario VARCHAR(255),
    log_data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    log_tipo VARCHAR(50),
    log_descricao TEXT,
    log_ip VARCHAR(255)
);

-- Inserindo tipos de usuários
INSERT INTO usertipo (usertipo_nome) VALUES ('usuario'), ('profissional'), ('moderador'), ('admin');

-- Inserindo dados de exemplo para categorias
INSERT INTO categorias (nome) VALUES ('Construção'), ('Reparos'), ('Manutenção');

-- Inserindo dados de exemplo para profissões
INSERT INTO profissoes (nome, categoria_id) VALUES 
    ('Pedreiro', 1),
    ('Encanador', 2),
    ('Eletricista', 3),
    ('Pintor', 2);

-- Inserindo dados de exemplo para planos
INSERT INTO planos (nome, prioridade) VALUES 
    ('Gratuito', 1),
    ('Premium', 2),
    ('VIP', 3);

-- Procedimento para atualizar a média de avaliação dos profissionais

DELIMITER $$

Drop PROCEDURE IF EXISTS atualizar_avaliacao_media;

PROCEDURE atualizar_avaliacao_media(profissional_id INT)
BEGIN
    UPDATE profissionais
    SET avaliacao_media = (
        SELECT AVG(nota)
        FROM avaliacoes
        WHERE profissional_id = profissional_id
    )
    WHERE pro_id = profissional_id;
END

DELIMITER ;

-- Procedimento para selecionar profissionais

DELIMITER $$

Drop PROCEDURE IF EXISTS selecionar_profissionais;

PROCEDURE selecionar_profissionais()
BEGIN
    SELECT pro_id, pro_nome, pro_email, pro_profissao, profissao_nome, pro_telefone, pro_descricao, pro_foto, plano_id, avaliacao_media, localidade
    FROM profissionais
    INNER JOIN profissoes ON profissao_id = profissao_id
    INNER JOIN planos ON plano_id = plano_id;
END

DELIMITER ;

-- Procedimento para selecionar clientes

DELIMITER $$

Drop PROCEDURE IF EXISTS selecionar_clientes;

PROCEDURE selecionar_clientes()
BEGIN
    SELECT cli_id, cli_nome, cli_email, cli_telefone, cli_bairro, cli_rua, cli_numero_rua, cli_cep
    FROM cliente;
END

DELIMITER ;

-- Procedimento para selecionar admins

DELIMITER $$

Drop PROCEDURE IF EXISTS selecionar_admins;

PROCEDURE selecionar_admins()
BEGIN
    SELECT admin_id, cli_id, admin_departamento, admin_cargo, admin_senha, nivel_acesso, status
    FROM admins;
END

DELIMITER ;

-- Deletar profissionais

DELIMITER $$

Drop PROCEDURE IF EXISTS deletar_profissionais;

PROCEDURE deletar_profissionais()
BEGIN
    DELETE FROM profissionais;
END

DELIMITER ;

-- Deletar clientes

DELIMITER $$

Drop PROCEDURE IF EXISTS deletar_clientes;

PROCEDURE deletar_clientes()
BEGIN
    DELETE FROM cliente;
END

DELIMITER ;



-- SuperAdmin
INSERT INTO admins (cli_id, admin_departamento, admin_cargo, admin_senha, nivel_acesso, status)
VALUES (
    1,                             -- ID do cliente associado ao super administrador
    'Diretoria',                   -- Departamento
    'Super Admin',                 -- Cargo
    '$2y$10$vouxOEzuPvGzt1mKU2o5sOMvwa8ZnS/7psZkEw8JMSi9KQJRaR.DW',  -- Hash da senha "123456"
    3,                             -- Nível de acesso para super administrador
    1                              -- Status ativo
);
