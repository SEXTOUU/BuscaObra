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
DROP TABLE IF EXISTS logs;
DROP TABLE IF EXISTS notificacoes;
DROP TABLE IF EXISTS assinaturas;
DROP TABLE IF EXISTS contato;
DROP TABLE IF EXISTS redefinicao_senha;

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

-- Tabela redefinicao_senha (relaciona cliente com token para recuperação de senha)
CREATE TABLE redefinicao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cli_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_expiracao TIMESTAMP NULL, 
    CONSTRAINT fk_cliente FOREIGN KEY (cli_id) REFERENCES cliente(cli_id) ON DELETE CASCADE
);

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
    valor DECIMAL(10, 2) NOT NULL,
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
CREATE TABLE logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT, -- Referência ao ID do usuário que realizou a ação
    tabela_alterada VARCHAR(100),
    tipo_operacao VARCHAR(50), -- Ex.: INSERT, UPDATE, DELETE
    descricao TEXT, -- Detalhes da operação
    data_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_usuario VARCHAR(45) -- IP do usuário, se aplicável
);

-- Tabela notificacoes
CREATE TABLE notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL, 
    mensagem TEXT NOT NULL,
    tipo ENUM('info', 'alert', 'success') DEFAULT 'info',
    lida BOOLEAN DEFAULT FALSE,
    visivel BOOLEAN DEFAULT TRUE,  -- Determina se a notificação é visível
    descartada BOOLEAN DEFAULT FALSE,  -- Indica se a notificação foi descartada
    origem_id INT,  -- ID de onde a notificação veio (ex: de um profissional)
    origem_tipo ENUM('profissional', 'avaliacao', 'sistema') DEFAULT 'sistema',  -- Tipo da origem
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES cliente(cli_id)
);

-- Tabela Assinaturas (associa clientes aos planos)
CREATE TABLE assinaturas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,           -- ID do usuário (cliente)
    plano_id INT NOT NULL,          -- ID do plano (Premium, Gratuito)
    status VARCHAR(50) DEFAULT 'ativo', -- Status da assinatura (ativo, cancelado, etc.)
    data_assinatura TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data da assinatura
    FOREIGN KEY (user_id) REFERENCES cliente(cli_id),
    FOREIGN KEY (plano_id) REFERENCES planos(plano_id)
);

-- Tabela Contato (formulário de contato)
CREATE TABLE contato (
    cod_id INT PRIMARY KEY AUTO_INCREMENT,
    cod_nome VARCHAR(255),
    cod_email VARCHAR(255),
    cod_mensagem TEXT,
    cod_assunto VARCHAR(255),
    cod_data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'pendente',
    FOREIGN KEY (cod_email) REFERENCES cliente(cli_email)
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
INSERT INTO planos (nome, valor, prioridade) VALUES 
    ('Gratuito', 0.00, 1),
    ('Premium', 25.00, 2),
    ('VIP', 59.99, 3);

-- Procedimento para atualizar a média de avaliação dos profissionais
DELIMITER $$

CREATE TRIGGER atualiza_avaliacao_media_insert
AFTER INSERT ON avaliacoes
FOR EACH ROW
BEGIN
    UPDATE profissionais
    SET avaliacao_media = (
        SELECT AVG(a.nota)
        FROM avaliacoes a
        WHERE a.profissional_id = NEW.profissional_id
    )
    WHERE pro_id = NEW.profissional_id;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER atualiza_avaliacao_media_update
AFTER UPDATE ON avaliacoes
FOR EACH ROW
BEGIN
    UPDATE profissionais
    SET avaliacao_media = (
        SELECT AVG(a.nota)
        FROM avaliacoes a
        WHERE a.profissional_id = NEW.profissional_id
    )
    WHERE pro_id = NEW.profissional_id;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER atualiza_avaliacao_media_delete
AFTER DELETE ON avaliacoes
FOR EACH ROW
BEGIN
    UPDATE profissionais
    SET avaliacao_media = (
        SELECT AVG(a.nota)
        FROM avaliacoes a
        WHERE a.profissional_id = OLD.profissional_id
    )
    WHERE pro_id = OLD.profissional_id;
END $$

DELIMITER ;

-- User Admin
INSERT INTO cliente (
    cli_nome, 
    cli_email, 
    cli_senha, 
    cli_tipo
) VALUES (
    'admin',                 -- Nome do cliente
    'superadmin@dominio.com',       -- E-mail do cliente
    '$2y$10$vouxOEzuPvGzt1mKU2o5sOMvwa8ZnS/7psZkEw8JMSi9KQJRaR.DW',  -- Hash da senha "123456"
    4                              -- Tipo de usuário 'admin' (4 no caso, se 'admin' for o tipo correto)
);

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
