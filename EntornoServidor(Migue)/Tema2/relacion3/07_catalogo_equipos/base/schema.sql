CREATE DATABASE IF NOT EXISTS ud2_catalogo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ud2_catalogo;

CREATE TABLE IF NOT EXISTS equipo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE,
    unidades INT NOT NULL
);

INSERT INTO equipo (nombre, unidades) VALUES
    ('Proyector portátil', 1),
    ('Tablet gráfica', 2),
    ('Micrófono USB', 3);
