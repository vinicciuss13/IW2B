CREATE DATABASE cadastro;
USE cadastro;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    paginas INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    ativo BIT(1) DEFAULT b'1'
);