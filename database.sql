CREATE DATABASE `base1`;

USE `base1`;

CREATE TABLE `articulos` (
    `codigo` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `descripcion` varchar(50) NOT NULL,
    `precio` float NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1;