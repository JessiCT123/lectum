CREATE DATABASE IF NOT EXISTS bd_lectum
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE bd_lectum;

# Para borrar y volver a crear las tablas desde el principio,
# descomentar las líneas del drop
DROP TABLE IF EXISTS remember_tokens;
DROP TABLE IF EXISTS usuario_libros;
DROP TABLE IF EXISTS resenias;
DROP TABLE IF EXISTS precios;
DROP TABLE IF EXISTS libros;
DROP TABLE IF EXISTS generos;
DROP TABLE IF EXISTS tiendas;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE IF NOT EXISTS generos
(
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS libros
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    titulo      VARCHAR(255) NOT NULL,
    autor       VARCHAR(255) NOT NULL,
    genero_id   INT          NOT NULL,
    valoracion  DECIMAL(2, 1) CHECK (valoracion BETWEEN 0 AND 5), #valoraciones de 0 a 5 con un decimal
    lecturas    INT,
    anio        INT          NOT NULL,
    paginas     INT          NOT NULL,
    portada     VARCHAR(100),
    descripcion TEXT,
    FOREIGN KEY (genero_id) REFERENCES generos (id) ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tiendas
(
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(50) NOT NULL UNIQUE,
    icono     VARCHAR(100),
    estrellas DECIMAL(2, 1) CHECK (estrellas BETWEEN 0 AND 5), #Estrellas de 0 a 5 con un decimal
    envio     VARCHAR(50)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS precios
(
    id        INT AUTO_INCREMENT PRIMARY KEY,
    libro_id  INT           NOT NULL,
    tienda_id INT           NOT NULL,
    precio    DECIMAL(6, 2) NOT NULL,
    url       VARCHAR(2000)  NOT NULL,
    CONSTRAINT uq_libro_tienda UNIQUE (libro_id, tienda_id),
    FOREIGN KEY (libro_id) REFERENCES libros (id) ON DELETE CASCADE,
    FOREIGN KEY (tienda_id) REFERENCES tiendas (id) ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS usuarios
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(50)  NOT NULL,
    usuario        VARCHAR(50)  NOT NULL UNIQUE,
    email          VARCHAR(100) NOT NULL UNIQUE,
    password       VARCHAR(255) NOT NULL,
    foto           VARCHAR(255)          DEFAULT NULL,
    fecha_registro DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS resenias
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    libro_id   INT NOT NULL,
    usuario_id INT NOT NULL,
    valoracion INT CHECK (valoracion BETWEEN 0 AND 5), #Valoración de 0 a 5 sin decimales
    texto      TEXT,
    fecha      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (libro_id) REFERENCES libros (id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS usuario_libros
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT                                        NOT NULL,
    libro_id   INT                                        NOT NULL,
    estado     ENUM ('pendiente', 'leyendo', 'terminado') NOT NULL DEFAULT 'pendiente',
    fecha      DATETIME                                            DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_usuario_libro UNIQUE (usuario_id, libro_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libros (id) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS remember_tokens
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT      NOT NULL,
    token_hash  CHAR(64) NOT NULL UNIQUE,
    f_caducidad DATETIME NOT NULL,
    f_creacion  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
