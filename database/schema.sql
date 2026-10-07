CREATE DATABASE IF NOT EXISTS loja_bebidas
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE loja_bebidas;
DROP TABLE IF EXISTS itens_encomenda;
DROP TABLE IF EXISTS encomendas;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS categorias;
CREATE TABLE categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE produtos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT UNSIGNED NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(8,2) NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    emoji VARCHAR(10) NOT NULL DEFAULT '🥤',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
) ENGINE=InnoDB;

CREATE TABLE encomendas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    morada VARCHAR(255) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendente', 'enviada', 'concluida', 'cancelada') NOT NULL DEFAULT 'pendente',
    criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE itens_encomenda (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    encomenda_id INT UNSIGNED NOT NULL,
    produto_id INT UNSIGNED NOT NULL,
    quantidade INT UNSIGNED NOT NULL,
    preco_unitario DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (encomenda_id) REFERENCES encomendas(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB;

INSERT INTO categorias (nome, slug) VALUES
    ('Refrigerantes', 'refrigerantes'),
    ('Sumos', 'sumos'),
    ('Águas', 'aguas'),
    ('Cervejas', 'cervejas'),
    ('Vinhos', 'vinhos');

INSERT INTO produtos (categoria_id, nome, descricao, preco, stock, emoji) VALUES
    (1, 'Coca-Cola 33cl', 'Refrigerante de cola em lata de 33cl.', 1.20, 120, '🥤'),
    (2, 'Sumo de Laranja 1L', 'Sumo de laranja 100% natural, pacote de 1 litro.', 2.50, 60, '🍊'),
    (3, 'Água Mineral 1,5L', 'Água mineral natural, garrafa de 1,5 litros.', 0.70, 200, '💧'),
    (4, 'Cerveja Lager 33cl', 'Cerveja lager portuguesa, garrafa de 33cl.', 1.10, 150, '🍺'),
    (5, 'Vinho Tinto Alentejo', 'Vinho tinto encorpado do Alentejo, garrafa de 75cl.', 6.90, 40, '🍷'),
    (1, 'Pepsi 33cl', 'Refrigerante de cola em lata de 33cl.', 1.10, 90, '🥤'),
    (2, 'Sumo de Maçã 1L', 'Sumo de maçã, pacote de 1 litro.', 2.30, 50, '🍎'),
    (3, 'Água com Gás 50cl', 'Água com gás natural, garrafa de 50cl.', 0.90, 80, '🫧'),
    (4, 'Cerveja Preta 33cl', 'Cerveja preta de sabor torrado, garrafa de 33cl.', 1.40, 70, '🍺'),
    (5, 'Vinho Verde Branco', 'Vinho verde branco fresco, garrafa de 75cl.', 5.50, 45, '🍷');

INSERT INTO encomendas (nome_cliente, email, telefone, morada, total, estado) VALUES
    ('Maria Silva', 'maria@exemplo.pt', '912345678', 'Rua das Flores 10, Lisboa', 5.70, 'pendente');

INSERT INTO itens_encomenda (encomenda_id, produto_id, quantidade, preco_unitario) VALUES
    (1, 1, 2, 1.20),
    (1, 4, 3, 1.10);

    SELECT * FROM produtos;

    SELECT p.nome, p.preco, c.nome AS categoria
FROM produtos p
JOIN categorias c ON p.categoria_id = c.id
ORDER BY c.nome, p.nome;
SELECT nome, preco FROM produtos
WHERE ativo = 1 AND nome LIKE '%cola%';
SELECT e.id, e.nome_cliente, p.nome, i.quantidade, i.preco_unitario,
       (i.quantidade * i.preco_unitario) AS subtotal
FROM encomendas e
JOIN itens_encomenda i ON i.encomenda_id = e.id
JOIN produtos p ON i.produto_id = p.id;