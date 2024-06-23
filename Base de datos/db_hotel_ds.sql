-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-03-2024 a las 03:17:01
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_hotel_ds`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_config`
--

CREATE TABLE `tb_config` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `horario` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cambio` double NOT NULL,
  `ruta_logo` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tb_config`
--

INSERT INTO `tb_config` (`id`, `nombre`, `horario`, `tipo_cambio`, `ruta_logo`) VALUES
(1, 'Hotel Transilvania', 'lunes a viernes', 503.92, 'storage/uploads/hotelds-1710466735.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_habitaciones`
--

CREATE TABLE `tb_habitaciones` (
  `id` int(11) NOT NULL,
  `idHotel` varchar(50) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `costo_dolar` double NOT NULL,
  `costo_colon` double NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `tipo` tinyint(4) NOT NULL,
  `activo` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tb_habitaciones`
--

INSERT INTO `tb_habitaciones` (`id`, `idHotel`, `capacidad`, `costo_dolar`, `costo_colon`, `estado`, `tipo`, `activo`) VALUES
(18, '1', 2, 10, 5039.2, 0, 2, 1),
(19, '2', 5, 100, 50392, 1, 1, 1),
(20, '3', 6, 250, 125980, 0, 2, 0),
(21, '3', 4, 25, 12598, 1, 1, 1),
(22, '4', 2, 10, 5039.2, 0, 1, 1),
(23, '5', 10, 1, 503.92, 0, 2, 1),
(24, '6', 3, 10, 5039.2, 0, 1, 0),
(25, '6', 2, 10, 5039.2, 0, 2, 0),
(26, '6', 1, 10, 5039.2, 0, 1, 1),
(27, '7', 11, 1, 503.92, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_horario`
--

CREATE TABLE `tb_horario` (
  `id` int(11) NOT NULL,
  `dia_inicio` varchar(100) NOT NULL,
  `dia_fin` varchar(100) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tb_horario`
--

INSERT INTO `tb_horario` (`id`, `dia_inicio`, `dia_fin`, `hora_inicio`, `hora_fin`) VALUES
(1, 'martes', 'sábado', '08:00:00', '22:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_reservaciones`
--

CREATE TABLE `tb_reservaciones` (
  `id` int(11) NOT NULL,
  `habitacion` int(11) NOT NULL,
  `nombre_cliente` varchar(250) NOT NULL,
  `cantidad_personas` int(11) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `fecha_salida` datetime NOT NULL,
  `dias_pagados` int(11) NOT NULL,
  `tipo_pago` tinyint(4) NOT NULL,
  `cancelado` double NOT NULL,
  `pendiente` double NOT NULL,
  `activo` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tb_reservaciones`
--

INSERT INTO `tb_reservaciones` (`id`, `habitacion`, `nombre_cliente`, `cantidad_personas`, `fecha_ingreso`, `fecha_salida`, `dias_pagados`, `tipo_pago`, `cancelado`, `pendiente`, `activo`) VALUES
(1, 18, 'Natalia Ortiz', 2, '2024-03-13 15:33:09', '2024-03-15 00:00:00', 2, 1, 20, 0, 0),
(2, 18, 'Natalia Ortiz', 2, '2024-03-13 15:36:10', '2024-03-16 00:00:00', 2, 1, 20, 10, 0),
(3, 18, 'Jeffry Brenes', 1, '2024-03-13 17:46:15', '2024-03-14 00:00:00', 1, 1, 10, 0, 0),
(4, 18, 'Jeffry Brenes', 1, '2024-03-13 17:48:21', '2024-03-16 00:00:00', 2, 1, 20, 10, 0),
(5, 19, 'Filomeno Filipino', 2, '2024-03-14 15:36:42', '2024-03-22 00:00:00', 5, 2, 252050, 151230, 0),
(6, 21, 'Lenin Gregorio', 2, '2024-03-14 19:47:46', '2024-03-24 00:00:00', 5, 2, 62990, 62990, 1),
(7, 22, 'Natalia Ortiz', 2, '2024-03-15 00:14:28', '2024-03-21 00:00:00', 2, 1, 20, 50, 0),
(8, 18, 'Pedro', 2, '2024-03-15 01:30:54', '2024-03-21 00:00:00', 2, 1, 20, 50, 0),
(9, 18, 'Jose', 1, '2024-03-15 01:31:33', '2024-03-29 00:00:00', 2, 2, 10078.4, 65509.6, 0),
(10, 22, 'Natalia Loca', 2, '2024-03-15 01:32:40', '2024-03-21 00:00:00', 3, 1, 30, 40, 0),
(11, 19, 'Jeffry Brenes', 2, '2024-03-15 01:37:48', '2024-03-22 00:00:00', 2, 1, 200, 600, 1),
(12, 18, 'Filomeno Filipino', 1, '2024-03-15 01:38:16', '2024-03-23 00:00:00', 2, 1, 20, 70, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_tipo_habitacion`
--

CREATE TABLE `tb_tipo_habitacion` (
  `id` int(11) NOT NULL,
  `tipo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tb_config`
--
ALTER TABLE `tb_config`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tb_habitaciones`
--
ALTER TABLE `tb_habitaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tb_horario`
--
ALTER TABLE `tb_horario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tb_reservaciones`
--
ALTER TABLE `tb_reservaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_hab` (`habitacion`);

--
-- Indices de la tabla `tb_tipo_habitacion`
--
ALTER TABLE `tb_tipo_habitacion`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tb_config`
--
ALTER TABLE `tb_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_habitaciones`
--
ALTER TABLE `tb_habitaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `tb_horario`
--
ALTER TABLE `tb_horario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_reservaciones`
--
ALTER TABLE `tb_reservaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tb_tipo_habitacion`
--
ALTER TABLE `tb_tipo_habitacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_reservaciones`
--
ALTER TABLE `tb_reservaciones`
  ADD CONSTRAINT `tb_reservaciones_ibfk_1` FOREIGN KEY (`habitacion`) REFERENCES `tb_habitaciones` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
