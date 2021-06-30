-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-06-2021 a las 23:31:18
-- Versión del servidor: 5.7.31
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db-symfony`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210224102826', '2021-02-25 21:28:32', 315),
('DoctrineMigrations\\Version20210614222858', '2021-06-14 22:29:58', 439),
('DoctrineMigrations\\Version20210614224001', '2021-06-14 22:40:09', 112),
('DoctrineMigrations\\Version20210614224439', '2021-06-14 22:44:49', 119),
('DoctrineMigrations\\Version20210614231202', '2021-06-14 23:12:15', 516),
('DoctrineMigrations\\Version20210614231641', '2021-06-14 23:16:50', 471),
('DoctrineMigrations\\Version20210614231812', '2021-06-14 23:18:19', 70),
('DoctrineMigrations\\Version20210615223613', '2021-06-15 22:36:32', 804),
('DoctrineMigrations\\Version20210626142353', '2021-06-26 14:24:15', 308),
('DoctrineMigrations\\Version20210626142753', '2021-06-26 14:28:06', 178),
('DoctrineMigrations\\Version20210626143149', '2021-06-26 14:31:56', 244),
('DoctrineMigrations\\Version20210626143716', '2021-06-26 14:37:22', 132);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea_pedido`
--

DROP TABLE IF EXISTS `linea_pedido`;
CREATE TABLE IF NOT EXISTS `linea_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_183C31657645698E` (`producto_id`),
  KEY `IDX_183C31654854653A` (`pedido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `precio` double NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `linea_pedido_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6716CCAADB38439E` (`usuario_id`),
  KEY `IDX_6716CCAAC16FB7D6` (`linea_pedido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` int(11) NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `categoria`, `descripcion`, `precio`, `imagen`, `added_on`) VALUES
(3, 'Champu Schwarkopft2', 'Champu', 'Es un champu para el pelo', 24, '5724d2b270ae.jpg', '2021-06-10 00:00:00'),
(4, 'Champu Schwarkopft', 'Champu', 'Es un champu para el pelo', 24, 'a876237a315c.jpg', '2021-06-08 18:07:06'),
(9, 'Plancha Pelo', 'Accesorio', 'Plancha para el pelo GHD', 235, '64ff5f3f8678.jpg', '2021-06-25 18:07:14'),
(10, 'Ampollas Loreal', 'Tratamientos', 'Ampollas anticaida de pelo', 16, '01df6f2a56b2.jpg', '2021-07-01 00:00:00'),
(11, 'Salerm Natural', 'Champu', 'Champu para pelos lisos', 18, '880aab88e43d.jpg', '2021-06-21 00:00:00'),
(13, 'Icon 2', 'Champu', 'Champu profesional', 25, '1bf0733ad1fc.jpg', '2021-07-08 00:00:00'),
(14, 'Peine Rizador', 'Accesorio', 'Peine rizador GHD para pelo', 235, '91cd59783eb7.jpg', '2020-07-15 00:00:00'),
(15, 'Secador GHD', 'Accesorio', 'Secador de pelo GHD', 220, '082af2fb4f06.jpg', '2021-05-16 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registra`
--

DROP TABLE IF EXISTS `registra`;
CREATE TABLE IF NOT EXISTS `registra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `USUARIO_id` int(11) NOT NULL,
  `servicio_id_id` int(11) NOT NULL,
  `hora` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DEC4855C3477C7E0` (`servicio_id_id`),
  KEY `IDX_DEC4855CDB38439E` (`USUARIO_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `registra`
--

INSERT INTO `registra` (`id`, `USUARIO_id`, `servicio_id_id`, `hora`, `fecha`, `active`) VALUES
(1, 1, 1, '11:00:00', '2021-02-27 00:00:00', 0),
(2, 3, 2, '18:00:00', '2021-01-09 00:00:00', 0),
(3, 4, 5, '19:00:00', '2021-01-15 00:00:00', 1),
(12, 1, 1, '09:30:00', '2016-01-01 00:00:00', 1),
(13, 1, 1, '16:30:00', '2022-04-20 00:00:00', 1),
(14, 1, 2, '16:30:00', '2021-07-16 00:00:00', 1),
(16, 1, 1, '09:30:00', '2016-01-01 00:00:00', 1),
(17, 1, 1, '09:30:00', '2016-01-01 00:00:00', 1),
(18, 1, 1, '09:30:00', '2016-01-01 00:00:00', 1),
(19, 1, 2, '09:30:00', '2021-08-21 00:00:00', 1),
(20, 7, 4, '19:00:00', '2023-11-20 00:00:00', 1),
(21, 7, 4, '19:00:00', '2023-11-20 00:00:00', 1),
(22, 11, 3, '18:00:00', '2021-07-22 00:00:00', 0),
(23, 1, 2, '16:30:00', '2021-07-20 00:00:00', NULL),
(24, 1, 3, '16:30:00', '2021-08-13 00:00:00', 0),
(25, 1, 1, '09:30:00', '2016-01-01 00:00:00', NULL),
(26, 1, 4, '19:00:00', '2022-05-19 00:00:00', 1),
(27, 20, 4, '12:00:00', '2021-09-13 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

DROP TABLE IF EXISTS `servicio`;
CREATE TABLE IF NOT EXISTS `servicio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id`, `nombre`) VALUES
(1, 'Corte Mujer'),
(2, 'Corte Caballero'),
(3, 'Color'),
(4, 'Lavar y secar'),
(5, 'Recogido'),
(6, 'Secado y peinado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellidos`, `telefono`, `email`, `username`, `password`, `avatar`, `role`, `updated_at`) VALUES
(1, 'Adrian', 'Garcia', 652874956, 'adri_denia_123@hotmail.com', 'admin', 'admin', '83721bae2bd4.png', 'ROLE_ADMIN', NULL),
(3, 'Juan', 'Gongora', 698777413, 'gongora@gmail.com', 'gongora', '1234', '8e438ca47233.jpg', 'ROLE_USER', NULL),
(4, 'Juan', 'Gongora', 698777413, 'gongora@gmail.com', 'gongora2', '1234', 'fc2fbb2bdcdb.jpg', 'ROLE_USER', NULL),
(7, 'Carlos', 'Pascual', 652312984, 'carlosps@hotmail.com', 'Carlos', '1234', '40c788f290c3.jpg', 'ROLE_USER', '2021-06-26'),
(8, 'Carlos', 'Pascual', 652312984, 'carlosp@hotmail.com', 'Carlos2', '1234', '3409b0bf1bbf.jpg', 'ROLE_USER', NULL),
(9, 'Carlos', 'Pascual', 652312984, 'carlosp@hotmail.com', 'Carlos3', '1234', '55513e10d7ea.jpg', 'ROLE_USER', NULL),
(10, 'Luis', 'Garci', 688541221, 'luiis@gmail.com', 'luis', '1234', '586bc3e2a4f1.png', 'ROLE_USER', NULL),
(11, 'Paco', 'Jones', 612254474, 'pacojones@hotmail.com', 'pacojones', '1234', 'c0f36321d18f.png', 'ROLE_ADMIN', NULL),
(14, 'Luis', 'Amen', 622114458, 'luisalb@hotmail.com', 'Luismi', '1234', '809dc47315e8.png', 'ROLE_USER', NULL),
(15, 'Luis', 'Amen', 622114458, 'luisalb@hotmail.com', 'Luismi', '1234', '8fc782444fe4.png', 'ROLE_USER', NULL),
(16, 'Andre', 'Bisquert', 656544114, 'andrea@hotmail.com', 'AndreaB', '1234', '6359570c8f7c.jpg', 'ROLE_USER', NULL),
(17, 'Adrian', 'Garcia', 652874956, 'adri_denia_123@hotmail.com', 'nuevo', '1234', '89346e4d48b2.jpg', 'ROLE_USER', NULL),
(18, 'Sergio', 'Perez', 699887741, 'sergiop@gmail.com', 'sergiop', '1234', 'ebbdbb59fff9.jpg', 'ROLE_USER', '2021-06-18 00:00:00'),
(20, 'VaporDev', 'Vapor', 699226886, 'vapordev@gmail.com', 'vapordev', '1234', 'd3c3c3b29b7d.jpg', 'ROLE_ADMIN', '2021-06-30'),
(21, 'Adrian', 'Garcia', 652874956, 'adri@gmail.com', 'adri', '$argon2id$v=19$m=65536,t=4,p=1$azVBeEwxcmlYQjFGVmFmRA$1V36ervNTY92wUXLAOUb6rvt6x31CIF2aAhgkfl+JkY', 'ee9545594f7e.jpg', 'ROLE_USER', '2021-06-18 18:59:47');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `linea_pedido`
--
ALTER TABLE `linea_pedido`
  ADD CONSTRAINT `FK_183C31654854653A` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `FK_183C31657645698E` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `FK_6716CCAAC16FB7D6` FOREIGN KEY (`linea_pedido_id`) REFERENCES `linea_pedido` (`id`),
  ADD CONSTRAINT `FK_6716CCAADB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `registra`
--
ALTER TABLE `registra`
  ADD CONSTRAINT `FK_DEC4855C3477C7E0` FOREIGN KEY (`servicio_id_id`) REFERENCES `servicio` (`id`),
  ADD CONSTRAINT `FK_DEC4855CDB38439E` FOREIGN KEY (`USUARIO_id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
