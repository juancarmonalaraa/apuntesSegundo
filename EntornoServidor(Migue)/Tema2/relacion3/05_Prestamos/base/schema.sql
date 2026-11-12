CREATE DATABASE IF NOT EXISTS ud2_prestamos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ud2_prestamos;
CREATE TABLE IF NOT EXISTS equipo (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100) NOT NULL UNIQUE,
 unidades INT NOT NULL,
 CONSTRAINT chk_unidades CHECK (unidades >= 0)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS prestamo (
 id INT AUTO_INCREMENT PRIMARY KEY,
 equipo_id INT NOT NULL,
 persona VARCHAR(80) NOT NULL,
 fecha_prestamo DATE NOT NULL,
 fecha_devolucion DATE NULL,
 FOREIGN KEY (equipo_id) REFERENCES equipo(id)
) ENGINE=InnoDB;
INSERT IGNORE INTO equipo (nombre, unidades) VALUES
 ('Proyector portátil',1), ('Tablet gráfica',2), ('Micrófono USB',3);
