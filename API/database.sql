CREATE DATABASE crud_api_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE crud_api_php;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    edad INT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);