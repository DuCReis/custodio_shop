CREATE TABLE user(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(256) NOT NULL,
    perm ENUM('admin','user'));


CREATE TABLE categoria(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100));

CREATE TABLE artigo( 
 ref INT NOT NULL,
 stock INT DEFAULT 0,
 descricao VARCHAR(100),
 preco INT NOT NULL,
 categoria_id INT, PRIMARY KEY (ref), 
 FOREIGN KEY (categoria_id) REFERENCES categoria(id));

CREATE TABLE artigo_encomenda( 
 artigo_ref INT, 
 encomenda_id INT,
 quantidade INT,
 FOREIGN KEY (artigo_ref) REFERENCES artigo(ref));

CREATE TABLE encomenda(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    data_hora DATETIME NOT NULL,
    estado ENUM('preparar','enviar','finalizado'),
    total INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES user(id)
);

INSERT INTO artigo(ref, stock, descricao, preco, categoria_id) VALUES
(24, 10, 'POLO Preto', 19, 4);


INSERT INTO categoria(nome) VALUES ('T-SHIRT');
INSERT INTO categoria(nome) VALUES ('Calções');
INSERT INTO categoria(nome) VALUES ('SWEATSHIRTS');
INSERT INTO categoria(nome) VALUES ('POLOS');

INSERT INTO user(nome, email, password, perm) VALUES
('duarte', 'duartelucas@outlook.pt', '1234', 'admin'),
('joao', 'joao@gmail.com', '1234', 'user');