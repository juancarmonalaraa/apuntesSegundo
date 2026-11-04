CREATE DATABASE IF NOT EXISTS ud2_tienda
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE ud2_tienda;

CREATE TABLE IF NOT EXISTS producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    CONSTRAINT chk_precio CHECK (precio >= 0),
    CONSTRAINT chk_stock CHECK (stock >= 0)
);
