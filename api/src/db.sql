CREATE DATABASE IF NOT EXISTS scheduling
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE scheduling;

CREATE TABLE users (
    id        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role      ENUM('client','admin') NOT NULL DEFAULT 'client',
    name      VARCHAR(150) NULL,
    email     VARCHAR(255) NOT NULL UNIQUE,
    phone     VARCHAR(20) NOT NULL,
    password  VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE services (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name             VARCHAR(150) NOT NULL,
    duration_minutes SMALLINT UNSIGNED NOT NULL,
    price            DECIMAL(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB;

CREATE TABLE appointments (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id   BIGINT UNSIGNED NOT NULL,
    service_id  BIGINT UNSIGNED NOT NULL,
    start_at    DATETIME NOT NULL,
    end_at      DATETIME NOT NULL,
    status      ENUM('scheduled','confirmed','cancelled','completed') NOT NULL DEFAULT 'scheduled',

    CONSTRAINT fk_appointments_client
        FOREIGN KEY (client_id) REFERENCES users(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,

    CONSTRAINT fk_appointments_service
        FOREIGN KEY (service_id) REFERENCES services(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
) ENGINE=InnoDB;
