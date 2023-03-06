/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/
DROP TABLE IF EXISTS `RolesUsuario`;
DROP TABLE IF EXISTS `Roles`;
DROP TABLE IF EXISTS `Usuarios`;

CREATE TABLE IF NOT EXISTS `Roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `RolesUsuario` (
  `usuario` int(11) NOT NULL,
  `rol` int(11) NOT NULL,
  PRIMARY KEY (`usuario`,`rol`),
  KEY `rol` (`rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `Usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreUsuario` varchar(30) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  `password` varchar(70) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
