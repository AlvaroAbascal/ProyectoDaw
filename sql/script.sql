/*Borrado BBDD si existe*/
DROP DATABASE IF EXISTS control;

/*Creación BBDD*/
CREATE DATABASE control;

/*Acceso a BBDD*/
USE control;

/*Crear BBDD si no esta creado*/
CREATE USER IF NOT EXISTS 'gestor'@'localhost' IDENTIFIED BY 'secreto';
GRANT ALL PRIVILEGES ON control.* TO 'gestor'@'localhost';
FLUSH PRIVILEGES;

/*Creación de tablas*/
CREATE TABLE usuario (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,    /*Deberia cambiar email por, ej:numMovimiento?*/
    pass VARCHAR(80) NOT NULL,
    nombre VARCHAR(25) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    fechaAlta DATE NOT NULL,
    idNivel INT NOT NULL,
    idCategoria INT NOT NULL,
    foto VARCHAR(50) UNIQUE,
    hash VARCHAR(256),
    PRIMARY KEY (id)
);

CREATE TABLE nivel (
    nivelId INT NOT NULL,
    descripcion VARCHAR(25) NOT NULL,
    PRIMARY KEY (nivelId)
);

CREATE TABLE categoria (
    id INT NOT NULL,
    sueldoH INT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE movimientos (
    id INT NOT NULL AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    inicio DATETIME NOT NULL,
    fin DATETIME NOT NULL,
    PRIMARY KEY (id)
);

ALTER TABLE movimientos MODIFY fin DATETIME;

CREATE TABLE eventos (
    id INT NOT NULL AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    fechaModificacion DATETIME NOT NULL,
    PRIMARY KEY (id)
);


/*Asignación de claves foráneas*/
ALTER TABLE usuario ADD CONSTRAINT FK_nivel FOREIGN KEY (idNivel) REFERENCES nivel (nivelId);
ALTER TABLE usuario ADD CONSTRAINT FK_categoria FOREIGN KEY (idCategoria) REFERENCES categoria (id);
ALTER TABLE movimientos ADD CONSTRAINT FK_movimientos FOREIGN KEY (idUsuario) REFERENCES usuario (id);

/*Modificación tabla usuario*/
ALTER TABLE usuario ADD estado BOOLEAN DEFAULT 1;


/*Cada vez que se actualiza la tabla usuario se hace un registro en la tabla eventos*/
DROP TRIGGER IF EXISTS actualizarUsuarios;
DELIMITER $$

CREATE TRIGGER actualizarUsuarios
AFTER UPDATE ON usuario
FOR EACH ROW
BEGIN
    DECLARE texto VARCHAR(255) DEFAULT '';

    IF (NEW.pass <> OLD.pass) THEN
        SET texto = CONCAT(texto, 'Ha cambiado la contraseña. ');
    END IF;

    IF (NEW.nombre <> OLD.nombre) THEN
        SET texto = CONCAT(texto, 'Ha cambiado el nombre de ', OLD.nombre, ' a ', NEW.nombre, '. ');
    END IF;

    IF (NEW.apellidos <> OLD.apellidos) THEN
        SET texto = CONCAT(texto, 'Ha cambiado el apellido de ', OLD.apellidos, ' a ', NEW.apellidos, '. ');
    END IF;

    IF (NEW.idNivel <> OLD.idNivel) THEN
        SET texto = CONCAT(texto, 'Ha cambiado el nivel de ', OLD.idNivel, ' a ', NEW.idNivel, '. ');
    END IF;

    IF (NEW.idCategoria <> OLD.idCategoria) THEN
        SET texto = CONCAT(texto, 'Ha cambiado la categoría de ', OLD.idCategoria, ' a ', NEW.idCategoria, '. ');
    END IF;

    INSERT INTO eventos (idUsuario, descripcion, fechaModificacion) VALUES (OLD.id, texto, NOW());
END$$

DELIMITER ;


/*Datos de prueba*/
INSERT INTO categoria (id, sueldoH) VALUES (1, 10);
INSERT INTO categoria (id, sueldoH) VALUES (2, 20);
INSERT INTO categoria (id, sueldoH) VALUES (3, 30);

INSERT INTO nivel (nivelId, descripcion) VALUES (1, 'Administrador');
INSERT INTO nivel (nivelId, descripcion) VALUES (2, 'Supervisor');
INSERT INTO nivel (nivelId, descripcion) VALUES (3, 'Usuario');

INSERT INTO usuario (email, pass, nombre, apellidos, fechaAlta, idNivel, idCategoria) VALUES ('alvaro@alvaro.com', 'alvaro', 'alvaro', 'abascal', '2024-03-03', 1, 1);
INSERT INTO usuario (email, pass, nombre, apellidos, fechaAlta, idNivel, idCategoria) VALUES ('alex@alex.com', 'alex', 'alex', 'diaz', '2024-02-25', 2, 2);
INSERT INTO usuario (email, pass, nombre, apellidos, fechaAlta, idNivel, idCategoria) VALUES ('alba@alba.com', 'alba', 'alba', 'perez', '2024-04-12', 3, 3);
INSERT INTO usuario (email, pass, nombre, apellidos, fechaAlta, idNivel, idCategoria) VALUES ('alfredo@alfredo.com', 'alfredo', 'alfredo', 'ramirez', '2024-03-31', 3, 3);
INSERT INTO usuario (email, pass, nombre, apellidos, fechaAlta, idNivel, idCategoria) VALUES ('patricia@patricia.com', 'patricia', 'patricia', 'teran', '2024-05-03', 3, 2);
INSERT INTO usuario (email, pass, nombre, apellidos, fechaAlta, idNivel, idCategoria) VALUES ('vicente@vicente.com', 'vicente', 'vicente', 'bueno', '2024-04-10', 3, 3);