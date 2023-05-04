-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-03-2023 a las 10:56:29
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `practica2`
--

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `nombre`) VALUES
(1, 'action'),
(2, 'adventure'),
(3, 'animation'),
(4, 'comedy'),
(5, 'drama'),
(6, 'fantasy'),
(7, 'historical'),
(8, 'horror'),
(9, 'musical'),
(10, 'noir'),
(11, 'romance'),
(12, 'science fiction'),
(13, 'thriller'),
(14, 'western');

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'provider'),
(3, 'moderator'),
(4, 'user');


--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombreUsuario`, `password`, `nombre`) VALUES
(1, 'admin', '$2y$10$O3c1kBFa2yDK5F47IUqusOJmIANjHP6EiPyke5dD18ldJEow.e0eS', 'Administrador'),
(2, 'user', '$2y$10$uM6NtF.f6e.1Ffu2rMWYV.j.X8lhWq9l8PwJcs9/ioVKTGqink6DG', 'Usuario');


--
-- Volcado de datos para la tabla `rolesusuario`
--

INSERT INTO `rolesusuario` (`usuario`, `rol`) VALUES
(1, 1),
(1, 4),
(2, 2),
(2, 4);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
