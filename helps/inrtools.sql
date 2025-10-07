-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 17-02-2025 a las 23:45:51
-- Versión del servidor: 10.11.8-MariaDB-log
-- Versión de PHP: 8.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inrtools`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estructuras`
--

CREATE TABLE `estructuras` (
  `id` int(11) NOT NULL,
  `solicitante` varchar(50) NOT NULL,
  `direccion_solicitante` varchar(100) NOT NULL,
  `ruc` varchar(100) NOT NULL,
  `estructura` varchar(100) NOT NULL,
  `fechaCorte` date NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `estado` varchar(20) NOT NULL,
  `analista_ejecutante` varchar(50) DEFAULT NULL,
  `estRegistro` tinyint(4) DEFAULT 1,
  `UsrCreacion` varchar(50) NOT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estructuras`
--

INSERT INTO `estructuras` (`id`, `solicitante`, `direccion_solicitante`, `ruc`, `estructura`, `fechaCorte`, `fecha_solicitud`, `estado`, `analista_ejecutante`, `estRegistro`, `UsrCreacion`, `deletedAt`, `createdAt`, `updatedAt`) VALUES
(1, 'GLENDA BENAVIDES', 'DNSES', '1891726712001', 'CARTERA C01-C02', '2024-12-31', '2025-01-06', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(2, 'GLENDA BENAVIDES', 'DNSES', '0690075636001', 'CARTERA C01-C02', '2024-11-30', '2025-01-06', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(3, 'GLENDA BENAVIDES', 'DNSES', '0690075636001', 'CARTERA C01-C02', '2024-10-31', '2025-01-07', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(4, 'GLENDA BENAVIDES', 'DNSES', '1790567699001', 'Balance Diario B13', '2024-12-31', '2025-01-09', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(5, 'GLENDA BENAVIDES', 'DNSES', '190158977001', 'CARTERA C01-C02', '2024-11-30', '2025-01-13', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(6, 'GLENDA BENAVIDES', 'DNSES', '0790024656001', 'CARTERA C01-C02', '2024-11-30', '2025-01-13', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(7, 'CARLA CORRALES', 'DNSES', '1891710328001', 'CARTERA C01-C02', '2024-12-31', '2025-01-14', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(8, 'CARLA CORRALES', 'DNSES', '1890012015001', 'CARTERA C01-C02', '2024-12-31', '2025-01-14', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(9, 'ESTEBAN VACA', 'DNSES', '1792283426001', 'CARTERA C01-C02', '2024-12-31', '2025-01-14', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(10, 'DIANA NARANJO', 'DNSES', '0791704499001', 'CARTERA C01-C02', '2024-11-30', '2025-01-16', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(11, 'FRANCISCO HIDALGO', 'DNS', '0691730573001', 'Depositos D01', '2023-07-31', '2025-01-16', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(12, 'FRANCISCO HIDALGO', 'DNS', '0691730573001', 'Depositos D01', '2024-10-31', '2025-01-16', 'ENVIADO', 'ILOPEZ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inrdireccion`
--

CREATE TABLE `inrdireccion` (
  `id` int(11) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `dirNombre` varchar(100) NOT NULL,
  `estRegistro` tinyint(4) DEFAULT 1,
  `UsrCreacion` varchar(50) NOT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `inrdireccion`
--

INSERT INTO `inrdireccion` (`id`, `direccion`, `dirNombre`, `estRegistro`, `UsrCreacion`, `deletedAt`, `createdAt`, `updatedAt`) VALUES
(1, 'INR', 'Intendencia Nacional de Riesgos', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(2, 'DNR', 'Dirección Nacional de Riesgos', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(3, 'DNSES', 'Dirección Nacional de Supervisión Extra Situ', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(4, 'DNS', 'Dirección Nacional de Supervisión', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(5, 'DNPLA', 'Dirección Nacional de Prevención de Lavado de Activos', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `identificacion` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `inrdireccion_id` int(11) DEFAULT NULL,
  `estRegistro` tinyint(4) DEFAULT 1,
  `UsrCreacion` varchar(50) NOT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `identificacion`, `nombre`, `email`, `inrdireccion_id`, `estRegistro`, `UsrCreacion`, `deletedAt`, `createdAt`, `updatedAt`) VALUES
(1, '1803223922', 'ISRAEL LOPEZ', 'israel.lopez@seps.gob.ec', 2, 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(2, '1802522678', 'MARIA TIRADO', 'isa61882@hotmail.com', 3, 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(3, '1850662261', 'VALERIA LOPEZ', 'valeria.loteztirado@gmail.com', 4, 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(4, '1803223923', 'juan perez', 'oso_es7@hotmail.com', 5, 1, 'ILOPEZ', NULL, '2025-02-18 00:59:49', '2025-02-18 00:59:49'),
(16, '1803223924', 'JUAN PEREZ', 'oso_es71@hotmail.com', 5, 1, 'ILOPEZ', NULL, '2025-02-18 01:19:34', '2025-02-18 01:19:34'),
(26, '1803223925', 'juan perez', 'oso_es78@hotmail.com', 5, 1, 'ILOPEZ', NULL, '2025-02-18 02:01:43', '2025-02-18 02:01:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estRegistro` tinyint(4) DEFAULT 1,
  `UsrCreacion` varchar(50) NOT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `estRegistro`, `UsrCreacion`, `deletedAt`, `createdAt`, `updatedAt`) VALUES
(1, 'SUPERUSER', 'Acceso completo a todas las funcionalidades. FULL', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(2, 'ANALISTA', 'Acceso limitado a funciones específicas de análisis.', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(3, 'ADMINISTRADOR', 'Acceso limitado a la mayoria de funciones y configuraciónn', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(4, 'CONSULTA', 'solamente a los reportes específicos', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `estRegistro` tinyint(4) DEFAULT 1,
  `UsrCreacion` varchar(50) NOT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `persona_id`, `nickname`, `password`, `estRegistro`, `UsrCreacion`, `deletedAt`, `createdAt`, `updatedAt`) VALUES
(1, 1, 'ILOPEZ', '$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(2, 1, 'ILOPEZ_ANALISTA', '$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(3, 2, 'MTIRADO', '$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15'),
(4, 3, 'VLOPEZ', '$2y$10$OnMqBJBOo0Om8MIVfHWVPOD3Y17VwbJgaMY2LJo65qO4Fvi98ADqu', 1, 'ILOPEZ', NULL, '2025-02-17 23:40:15', '2025-02-17 23:40:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `rol_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 2, 3),
(5, 3, 2),
(4, 3, 4),
(6, 4, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estructuras`
--
ALTER TABLE `estructuras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inrdireccion`
--
ALTER TABLE `inrdireccion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion` (`identificacion`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `inrdireccion_id` (`inrdireccion_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD KEY `persona_id` (`persona_id`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`rol_id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estructuras`
--
ALTER TABLE `estructuras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `inrdireccion`
--
ALTER TABLE `inrdireccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`inrdireccion_id`) REFERENCES `inrdireccion` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
