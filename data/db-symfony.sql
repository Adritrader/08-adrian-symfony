-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 13-06-2021 a las 13:45:17
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
('DoctrineMigrations\\Version20210224102826', '2021-02-25 21:28:32', 315);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movie`
--

DROP TABLE IF EXISTS `movie`;
CREATE TABLE IF NOT EXISTS `movie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `string` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `categoria`, `descripcion`, `precio`, `imagen`) VALUES
(2, 'Ampollas Loreal', 'Tratamientos', 'Es un champu para el pelo', 11, '124e45d10d9e.png'),
(3, 'Champu Schwarkopft', 'Champu', 'Es un champu para el pelo', 24, '5e8f7eecf72b.jpg'),
(4, 'Champu Schwarkopft', 'Champu', 'Es un champu para el pelo', 24, 'a876237a315c.jpg'),
(5, 'Champu Schwarkopft', 'Champu', 'Es un champu para el pelo', 24, 'serum-kerastase.jpg'),
(8, 'Plancha Pelo', 'Accesorio', 'Plancha GHD para el pelo', 249, 'b0199a565f08.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registra`
--

DROP TABLE IF EXISTS `registra`;
CREATE TABLE IF NOT EXISTS `registra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `USUARIO_id` int(11) NOT NULL,
  `SERVICIO_id` int(11) NOT NULL,
  `hora` varchar(25) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id`,`USUARIO_id`,`SERVICIO_id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_USUARIO_has_SERVICIO_SERVICIO1_idx` (`SERVICIO_id`),
  KEY `fk_USUARIO_has_SERVICIO_USUARIO_idx` (`USUARIO_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `registra`
--

INSERT INTO `registra` (`id`, `USUARIO_id`, `SERVICIO_id`, `hora`, `fecha`) VALUES
(1, 1, 1, '17:00:00', '2021-02-27'),
(2, 3, 2, '18:00:00', '2021-01-09'),
(3, 4, 5, '19:00:00', '2021-01-15');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellidos`, `telefono`, `email`, `username`, `password`, `avatar`, `role`) VALUES
(1, 'Adrian', 'Garcia', 652874956, 'adri_denia_123@hotmail.com', 'admin', 'admin', '83721bae2bd4.png', 'ROLE_ADMIN'),
(3, 'Juan', 'Gongora', 698777413, 'gongora@gmail.com', 'gongora', '1234', '8e438ca47233.jpg', 'ROLE_USER'),
(4, 'Juan', 'Gongora', 698777413, 'gongora@gmail.com', 'gongora2', '1234', 'fc2fbb2bdcdb.jpg', 'ROLE_USER'),
(5, 'Juan', 'Gongora', 698777413, 'gongora@gmail.com', 'gongora3', '1234', '67617c8992f5.png', 'ROLE_USER'),
(6, 'Andrea', 'Bisquert', 699874123, 'andrea@hotmail.com', 'AndreaB', '1234', '88591b20b338.jpg', 'ROLE_ADMIN'),
(7, 'Carlos', 'Pascual', 652312984, 'carlosp@hotmail.com', 'Carlos', '1234', 'd5191b86d00b.jpg', 'ROLE_USER'),
(8, 'Carlos', 'Pascual', 652312984, 'carlosp@hotmail.com', 'Carlos2', '1234', '3409b0bf1bbf.jpg', 'ROLE_USER'),
(9, 'Carlos', 'Pascual', 652312984, 'carlosp@hotmail.com', 'Carlos3', '1234', '55513e10d7ea.jpg', 'ROLE_USER'),
(10, 'Luis', 'Garci', 688541221, 'luiis@gmail.com', 'luis', '1234', '586bc3e2a4f1.png', 'ROLE_USER'),
(11, 'Paco', 'Jones', 612254474, 'pacojones@hotmail.com', 'pacojones', '1234', 'c0f36321d18f.png', 'ROLE_ADMIN'),
(13, 'Joan', 'Lopez', 612212136, 'joanlopez23@gmail.com', 'joan', '1234', 'c9b83522a9e2.jpg', 'ROLE_ADMIN');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
