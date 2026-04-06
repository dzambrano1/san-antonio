-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-04-2026 a las 19:47:54
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
-- Base de datos: `san_antonio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'douglasezambrano@gmail.com', '$2y$10$QiGOivA0.eUsxDr.2oWMhu3dbHP1ti3ZNphlsBn.FYOr8nO5aIOZe', '2026-03-28 01:01:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objetos`
--

CREATE TABLE `objetos` (
  `id` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `ubicacion` varchar(155) NOT NULL,
  `contacto_hallador` varchar(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('prohibido','vencido','publicado','investigando') DEFAULT 'publicado',
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objetos`
--

INSERT INTO `objetos` (`id`, `imagen`, `nombre`, `descripcion`, `ubicacion`, `contacto_hallador`, `fecha_registro`, `estado`, `fecha_vencimiento`, `fecha_creacion`) VALUES
(1, '1774557172_Xiaomi-redmi-13.png', 'Celular', 'Redmi Note 14C', 'Playa Panty', '+584143332662', '2026-03-26 20:32:52', 'publicado', NULL, '2026-03-28 01:01:48'),
(2, '1774656268_llaves.jpg', 'LLaves', 'Son 20 llaves con su llavero', 'Aeropuerto', '+584143332662', '2026-03-28 00:04:28', 'publicado', NULL, '2026-03-28 01:01:48'),
(3, '1774715258_IMG_20220222_214414.jpg', 'Cedula de Identidad', 'Me lo encontre en la  calle', 'Calle San Rafael', '+584143332662', '2026-03-28 16:27:38', 'publicado', '2026-04-27', '2026-03-28 16:27:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `objetos`
--
ALTER TABLE `objetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
