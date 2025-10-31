-- Cria o banco de dados
CREATE DATABASE IF NOT EXISTS `sistema_cadastroTim`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `sistema_cadastroTim`;

-- Tabela de departamentos
CREATE TABLE IF NOT EXISTS `departamentos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL UNIQUE,
  `descricao` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de funcionários
-- Obs.: compatível com funcionarios_action.php (usa departamento_id e data_admissao)
CREATE TABLE IF NOT EXISTS `funcionarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `cpf` VARCHAR(14) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL,
  `telefone` VARCHAR(15) NOT NULL,
  `cargo` VARCHAR(50) NOT NULL,
  `departamento_id` INT NULL,
  `salario` DECIMAL(10,2) NOT NULL,
  `data_admissao` DATE NOT NULL,
  CONSTRAINT `fk_funcionarios_departamento`
    FOREIGN KEY (`departamento_id`)
    REFERENCES `departamentos`(`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

