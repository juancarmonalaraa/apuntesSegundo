CREATE DATABASE IF NOT EXISTS ud2_talleres CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ud2_talleres;
CREATE TABLE IF NOT EXISTS taller (
 id INT AUTO_INCREMENT PRIMARY KEY,
 titulo VARCHAR(100) NOT NULL UNIQUE,
 plazas INT NOT NULL,
 CONSTRAINT chk_plazas CHECK (plazas > 0)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS inscripcion (
 id INT AUTO_INCREMENT PRIMARY KEY,
 taller_id INT NOT NULL,
 nombre VARCHAR(80) NOT NULL,
 email VARCHAR(160) NOT NULL,
 estado ENUM('confirmada','espera') NOT NULL,
 creado DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (taller_id) REFERENCES taller(id),
 UNIQUE KEY uq_taller_email (taller_id, email)
) ENGINE=InnoDB;
INSERT IGNORE INTO taller (titulo, plazas) VALUES
 ('Introducción a la fotografía',2), ('Edición de sonido',3);
