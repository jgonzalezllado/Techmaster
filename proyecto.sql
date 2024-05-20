-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-05-2024 a las 16:10:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` varchar(10) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `num_tickets` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `dni`, `email`, `num_tickets`) VALUES
(3, 'Pedro', 'López', '45678901C', 'pedro@example.com', 0),
(4, 'Ana', 'Martínez', '89012345D', 'ana@example.com', 0),
(5, 'Luis', 'Sánchez', '23456789E', 'luis@example.com', 0),
(6, 'Laura', 'Díaz', '67890123F', 'laura@example.com', 0),
(7, 'Carlos', 'Fernández', '34567890G', 'carlos@example.com', 0),
(8, 'Sara', 'García', '78901234H', 'sara@example.com', 0),
(9, 'Javier', 'Rodríguez', '90123456I', 'javier@example.com', 0),
(10, 'Elena', 'López', '56789012J', 'elena@example.com', 0),
(11, 'David', 'Pérez', '34567890K', 'david@example.com', 0),
(12, 'Paula', 'Gómez', '98765432L', 'paula@example.com', 0),
(13, 'Miguel', 'Martín', '23456789M', 'miguel@example.com', 0),
(14, 'Carmen', 'Sánchez', '65432109N', 'carmen@example.com', 0),
(15, 'Sergio', 'Jiménez', '89012345O', 'sergio@example.com', 0),
(16, 'pepe', 'de los palotes', '432344168x', 'lalal@GMAIL.COM', 0),
(17, 'test', 'f', '343432x', 'estebangllado@gmail.com', 0),
(18, 'test', 'f', '343432x', 'estebangllado@gmail.com', 0),
(19, 'test', 'f', '343432x', 'estebangllado@gmail.com', 0),
(20, 'Julia', 'mlkdf', 'kjdsf', 'estebangllado@gmail.com', 0),
(21, 'Julia', 'mlkdf', 'kjdsf', 'estebangllado@gmail.com', 0),
(22, 'Lala', 'alexmhjhj', 'kjdsf', 'sina.ahrends@web.de', 0),
(23, 'Julia', 'mlkdf', '43234168x', 'magicesteban@gmail.com', 1),
(24, 'Julia', 'mlkdf', '43234168x', 'magicesteban@gmail.com', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `estado` enum('Recibido','Asignado a técnico','Finalizado','') DEFAULT NULL,
  `modelo_reparar` varchar(100) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `trabajador_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id`, `fecha`, `comentarios`, `estado`, `modelo_reparar`, `cliente_id`, `trabajador_id`) VALUES
(15, '2024-05-12', 'esto es una prueba', 'Recibido', 's-g520', 23, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(90) NOT NULL,
  `apellido` varchar(90) NOT NULL,
  `email` varchar(100) NOT NULL,
  `trabajador_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajadores`
--

INSERT INTO `trabajadores` (`dni`, `nombre`, `apellido`, `email`, `trabajador_id`) VALUES
('4323413X', 'julia', 'gonzalez', 'juliagonzalez177@gmail.com', 0),
('', 'Pedro', 'González', '', 1),
('', 'María', 'López', '', 2),
('', 'Juan', 'Martínez', '', 3),
('', 'Laura', 'Pérez', '', 4),
('', 'Carlos', 'Rodríguez', '', 5),
('43231123', 'mabel', 'holi', 'mabel@ghmail.coim', 8);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_ibfk_1` (`cliente_id`),
  ADD KEY `tickets_ibfk_2` (`trabajador_id`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`trabajador_id`),
  ADD KEY `trabajador_id` (`trabajador_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajadores` (`trabajador_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
