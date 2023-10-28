-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 26-03-2023 a las 23:43:17
-- Versión del servidor: 8.0.31-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Base de datos: `oca`

-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL,
  `usuario` varchar(33) COLLATE utf8mb3_spanish_ci NOT NULL UNIQUE,
  `nombre` varchar(33) COLLATE utf8mb3_spanish_ci NOT NULL,
  `clave` varchar(64) COLLATE utf8mb3_spanish_ci NOT NULL,
  `pregunta` varchar(33) COLLATE utf8mb3_spanish_ci NOT NULL,
  `respuesta` varchar(33) COLLATE utf8mb3_spanish_ci NOT NULL,
  `email` VARCHAR(255) COLLATE utf8mb3_spanish_ci NOT NULL UNIQUE,
  `tipo` TINYINT NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Crear variables para contraseña y respuesta cifradas
SET @password = 'admin';
SET @respuestaSecreta = 'verde';

SET @contrasenaCifrada = SHA2(@password, 256);
SET @respuestaCifrada = SHA2(@respuestaSecreta, 256);

-- Insertar el usuario 'admin' con la contraseña y respuesta cifradas
INSERT INTO `usuarios` (`usuario`, `nombre`, `clave`, `pregunta`, `respuesta`, `email`, `tipo`)
VALUES (
    'admin',
    'administrador',
    @contrasenaCifrada,
    'color favorito?',
    @respuestaCifrada,
    'test@gmail.com',
    1
);

-- Índices para tablas volcadas
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

-- AUTO_INCREMENT de las tablas volcadas
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;