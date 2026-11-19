CREATE DATABASE IF NOT EXISTS scheduling
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE scheduling;

CREATE TABLE users (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role        ENUM('client','admin') NOT NULL DEFAULT 'client',
    name        VARCHAR(150) NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    phone       VARCHAR(20) NULL,
    password    VARCHAR(255) NOT NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE appointments (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id   BIGINT UNSIGNED NOT NULL,
    schedule    DATETIME UNIQUE NOT NULL,
    status      ENUM('Pendente','Confirmado','Cancelado','Concluído') NOT NULL DEFAULT 'Pendente',
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  DATETIME NULL,

    CONSTRAINT fk_appointments_client
        FOREIGN KEY (client_id) REFERENCES users(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
) ENGINE=InnoDB;
