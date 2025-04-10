-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-04-2025 a las 00:30:38
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
-- Base de datos: `cinedb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asientos`
--

CREATE TABLE `asientos` (
  `id_asiento` bigint(20) NOT NULL,
  `id_sala` bigint(20) NOT NULL,
  `fila` varchar(1) NOT NULL,
  `numero` int(11) NOT NULL,
  `tipo_asiento` varchar(50) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asientos`
--

INSERT INTO `asientos` (`id_asiento`, `id_sala`, `fila`, `numero`, `tipo_asiento`, `estado`) VALUES
(147, 6, '', 1, '', 'disponible'),
(148, 6, '', 2, '', 'disponible'),
(149, 6, '', 3, '', 'disponible'),
(150, 6, '', 4, '', 'disponible'),
(151, 6, '', 5, '', 'disponible'),
(152, 6, '', 6, '', 'disponible'),
(153, 6, '', 7, '', 'disponible'),
(154, 6, '', 8, '', 'disponible'),
(155, 6, '', 9, '', 'disponible'),
(156, 6, '', 10, '', 'disponible'),
(157, 6, '', 11, '', 'disponible'),
(158, 6, '', 12, '', 'disponible'),
(159, 6, '', 13, '', 'disponible'),
(160, 6, '', 14, '', 'disponible'),
(161, 6, '', 15, '', 'disponible'),
(162, 6, '', 16, '', 'disponible'),
(163, 6, '', 17, '', 'disponible'),
(164, 6, '', 18, '', 'disponible'),
(165, 6, '', 19, '', 'disponible'),
(166, 6, '', 20, '', 'disponible'),
(167, 6, '', 21, '', 'disponible'),
(168, 6, '', 22, '', 'disponible'),
(169, 6, '', 23, '', 'disponible'),
(170, 6, '', 24, '', 'disponible'),
(171, 6, '', 25, '', 'disponible'),
(172, 6, '', 26, '', 'disponible'),
(173, 6, '', 27, '', 'disponible'),
(174, 6, '', 28, '', 'disponible'),
(175, 6, '', 29, '', 'disponible'),
(176, 6, '', 30, '', 'disponible'),
(177, 6, '', 31, '', 'disponible'),
(178, 6, '', 32, '', 'disponible'),
(179, 6, '', 33, '', 'disponible'),
(180, 6, '', 34, '', 'disponible'),
(181, 6, '', 35, '', 'disponible'),
(182, 6, '', 36, '', 'disponible'),
(183, 6, '', 37, '', 'disponible'),
(184, 6, '', 38, '', 'disponible'),
(185, 6, '', 39, '', 'disponible'),
(186, 6, '', 40, '', 'disponible'),
(187, 6, '', 41, '', 'disponible'),
(188, 6, '', 42, '', 'disponible'),
(189, 6, '', 43, '', 'disponible'),
(190, 6, '', 44, '', 'disponible'),
(191, 6, '', 45, '', 'disponible'),
(192, 6, '', 46, '', 'disponible'),
(193, 6, '', 47, '', 'disponible'),
(194, 6, '', 48, '', 'disponible'),
(195, 6, '', 49, '', 'disponible'),
(196, 6, '', 50, '', 'disponible'),
(197, 6, '', 51, '', 'disponible'),
(198, 6, '', 52, '', 'disponible'),
(199, 6, '', 53, '', 'disponible'),
(200, 6, '', 54, '', 'disponible'),
(201, 6, '', 55, '', 'disponible'),
(202, 6, '', 56, '', 'disponible'),
(203, 6, '', 57, '', 'disponible'),
(204, 6, '', 58, '', 'disponible'),
(205, 6, '', 59, '', 'disponible'),
(206, 6, '', 60, '', 'disponible'),
(207, 6, '', 61, '', 'disponible'),
(208, 6, '', 62, '', 'disponible'),
(209, 6, '', 63, '', 'disponible'),
(210, 6, '', 64, '', 'disponible'),
(211, 6, '', 65, '', 'disponible'),
(212, 6, '', 66, '', 'disponible'),
(213, 6, '', 67, '', 'disponible'),
(214, 6, '', 68, '', 'disponible'),
(215, 6, '', 69, '', 'disponible'),
(216, 6, '', 70, '', 'disponible'),
(217, 6, '', 71, '', 'disponible'),
(218, 6, '', 72, '', 'disponible'),
(219, 6, '', 73, '', 'disponible'),
(220, 6, '', 74, '', 'disponible'),
(221, 6, '', 75, '', 'ocupado'),
(222, 6, '', 76, '', 'ocupado'),
(223, 6, '', 77, '', 'ocupado'),
(224, 6, '', 78, '', 'disponible'),
(225, 6, '', 79, '', 'disponible'),
(226, 6, '', 80, '', 'disponible'),
(227, 7, '', 1, '', 'disponible'),
(228, 7, '', 2, '', 'disponible'),
(229, 7, '', 3, '', 'disponible'),
(230, 7, '', 4, '', 'disponible'),
(231, 7, '', 5, '', 'disponible'),
(232, 7, '', 6, '', 'ocupado'),
(233, 7, '', 7, '', 'disponible'),
(234, 7, '', 8, '', 'disponible'),
(235, 7, '', 9, '', 'disponible'),
(236, 7, '', 17, '', 'ocupado'),
(237, 7, '', 11, '', 'disponible'),
(238, 7, '', 12, '', 'disponible'),
(239, 7, '', 13, '', 'disponible'),
(240, 7, '', 14, '', 'disponible'),
(241, 7, '', 15, '', 'disponible'),
(242, 7, '', 16, '', 'disponible'),
(243, 7, '', 17, '', 'disponible'),
(244, 7, '', 18, '', 'disponible'),
(245, 7, '', 19, '', 'disponible'),
(246, 7, '', 20, '', 'disponible'),
(247, 7, '', 21, '', 'disponible'),
(248, 7, '', 22, '', 'disponible'),
(249, 7, '', 23, '', 'disponible'),
(250, 7, '', 24, '', 'disponible'),
(251, 7, '', 25, '', 'disponible'),
(252, 7, '', 26, '', 'disponible'),
(253, 7, '', 27, '', 'disponible'),
(254, 7, '', 28, '', 'disponible'),
(255, 7, '', 29, '', 'disponible'),
(256, 7, '', 30, '', 'disponible'),
(277, 9, '', 1, '', 'disponible'),
(278, 9, '', 2, '', 'disponible'),
(279, 9, '', 3, '', 'disponible'),
(280, 9, '', 4, '', 'ocupado'),
(281, 9, '', 5, '', 'ocupado'),
(282, 9, '', 6, '', 'ocupado'),
(283, 9, '', 7, '', 'disponible'),
(284, 9, '', 8, '', 'disponible'),
(285, 9, '', 9, '', 'disponible'),
(286, 9, '', 10, '', 'disponible'),
(287, 9, '', 11, '', 'disponible'),
(288, 9, '', 12, '', 'disponible'),
(289, 9, '', 13, '', 'disponible'),
(290, 9, '', 14, '', 'disponible'),
(291, 9, '', 15, '', 'disponible'),
(292, 7, '', 10, '', 'disponible'),
(383, 8, '', 1, '', 'disponible'),
(384, 8, '', 2, '', 'disponible'),
(385, 8, '', 3, '', 'disponible'),
(386, 8, '', 4, '', 'disponible'),
(387, 8, '', 5, '', 'disponible'),
(388, 8, '', 6, '', 'disponible'),
(389, 8, '', 7, '', 'disponible'),
(390, 8, '', 8, '', 'disponible'),
(391, 8, '', 9, '', 'disponible'),
(392, 8, '', 10, '', 'disponible'),
(393, 8, '', 11, '', 'disponible'),
(394, 8, '', 12, '', 'disponible'),
(395, 8, '', 13, '', 'disponible'),
(396, 8, '', 14, '', 'disponible'),
(397, 8, '', 15, '', 'disponible'),
(398, 8, '', 16, '', 'disponible'),
(399, 8, '', 17, '', 'disponible'),
(400, 8, '', 18, '', 'disponible'),
(401, 8, '', 19, '', 'disponible'),
(402, 8, '', 20, '', 'disponible'),
(403, 8, '', 21, '', 'disponible'),
(404, 8, '', 22, '', 'disponible'),
(405, 8, '', 23, '', 'ocupado'),
(406, 8, '', 24, '', 'ocupado'),
(407, 8, '', 25, '', 'ocupado'),
(408, 8, '', 26, '', 'disponible'),
(409, 8, '', 27, '', 'disponible'),
(410, 8, '', 28, '', 'disponible'),
(411, 8, '', 29, '', 'disponible'),
(412, 8, '', 30, '', 'disponible'),
(413, 8, '', 31, '', 'disponible'),
(414, 8, '', 32, '', 'disponible'),
(415, 8, '', 33, '', 'disponible'),
(416, 8, '', 34, '', 'disponible'),
(417, 8, '', 35, '', 'disponible'),
(418, 8, '', 36, '', 'disponible'),
(419, 8, '', 37, '', 'disponible'),
(420, 8, '', 38, '', 'disponible'),
(421, 8, '', 39, '', 'disponible'),
(422, 8, '', 40, '', 'disponible'),
(423, 8, '', 41, '', 'disponible'),
(424, 8, '', 42, '', 'disponible'),
(425, 8, '', 43, '', 'disponible'),
(426, 8, '', 44, '', 'disponible'),
(427, 8, '', 45, '', 'disponible'),
(428, 8, '', 46, '', 'disponible'),
(429, 8, '', 47, '', 'ocupado'),
(430, 8, '', 48, '', 'disponible'),
(431, 8, '', 49, '', 'disponible'),
(432, 8, '', 50, '', 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrusel`
--

CREATE TABLE `carrusel` (
  `id` int(11) NOT NULL,
  `imagen_ruta` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrusel`
--

INSERT INTO `carrusel` (`id`, `imagen_ruta`, `titulo`) VALUES
(1, '/cineapp/imagenes/carrusel/pronto 1.jpg', 'Lilo & Stitch'),
(2, '/cineapp/imagenes/carrusel/kayara.png', 'Kayara'),
(3, '/cineapp/imagenes/carrusel/avatar.png', 'Avatar 3'),
(4, '/cineapp/imagenes/carrusel/pronto 2.jpg', 'El abismo secreto'),
(5, '/cineapp/imagenes/carrusel/pronto3.jpg', 'Plankton, la película');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combos`
--

CREATE TABLE `combos` (
  `id_combo` bigint(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen_nombre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combos`
--

INSERT INTO `combos` (`id_combo`, `nombre`, `descripcion`, `precio`, `imagen_nombre`) VALUES
(3, 'Combo de HotDogs con Gaseosa', 'Perro caliente tradicional con todos los ingredientes, papas fritas y una gaseosa mediana. Incluye salsas al gusto.', 20000.00, '67f5eec3c316f.avif'),
(5, 'Combo de Palomitas con Gaseosa', 'Deliciosas palomitas recién hechas acompañadas de una gaseosa grande de tu elección. Perfecto para disfrutar durante la película.', 15000.00, '67f5ef6feb7b0.webp'),
(6, 'Combo de Hamburguesa con Papas fritas y gaseosa', 'Hamburguesa jugosa con queso, lechuga, tomate y salsa especial. Acompañada de papas fritas crujientes y gaseosa grande.', 30000.00, '67f5efab9dbe2.jpg'),
(8, 'Combo de Nachos con queso y Gaseosa', 'Nachos crocantes bañados en queso fundido, acompañados de salsa picante y una gaseosa personal de 400ml.', 28000.00, '67f5f0534b604.jpg'),
(9, 'Combo de Sándwich con Papas', 'Sándwich fresco con jamón, queso y vegetales, acompañado de papas fritas y aderezo. Opción vegetariana disponible.', 30000.00, '67f5f09c1f348.avif'),
(10, 'Combo de Pizza con adicionales y gaseosa', 'Porción grande de pizza con palitos de ajo, salsa extra y gaseosa de 500ml. Variedad de sabores disponible.', 28000.00, '67f5f0d8beca8.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funciones`
--

CREATE TABLE `funciones` (
  `id_funcion` bigint(20) NOT NULL,
  `id_pelicula` bigint(20) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `precio` decimal(12,0) NOT NULL DEFAULT 0,
  `id_sala` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funciones`
--

INSERT INTO `funciones` (`id_funcion`, `id_pelicula`, `fecha_hora`, `precio`, `id_sala`) VALUES
(9, 18, '2025-04-15 19:00:00', 15000, 8),
(11, 18, '2025-04-08 23:00:00', 13000, 6),
(12, 18, '2025-03-28 14:00:00', 12000, 7),
(13, 20, '2025-04-05 16:00:00', 15000, 8),
(14, 20, '2025-04-05 22:00:00', 18000, 8),
(15, 20, '2025-04-02 23:00:00', 20000, 9),
(16, 19, '2025-04-02 23:00:00', 20000, 9),
(17, 21, '2025-04-02 23:00:00', 20000, 9),
(18, 22, '2025-04-02 23:00:00', 20000, 9),
(19, 23, '2025-04-02 23:00:00', 20000, 9),
(20, 19, '2025-03-27 14:00:00', 12000, 8),
(21, 19, '2025-03-31 19:30:00', 15000, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_compra`
--

CREATE TABLE `ordenes_compra` (
  `id_orden` bigint(20) NOT NULL,
  `id_usuario` bigint(20) NOT NULL,
  `id_reservacion` bigint(20) NOT NULL,
  `id_combo` bigint(20) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado_pago` varchar(20) NOT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes_compra`
--

INSERT INTO `ordenes_compra` (`id_orden`, `id_usuario`, `id_reservacion`, `id_combo`, `total`, `estado_pago`, `fecha_pago`) VALUES
(1, 11, 1, NULL, 312000.00, 'completado', '2025-04-09 14:02:59'),
(2, 11, 1, 10, 252000.00, 'completado', '2025-04-09 14:02:59'),
(3, 2, 5, NULL, 155000.00, 'completado', '2025-04-09 21:43:31'),
(4, 2, 5, 9, 60000.00, 'completado', '2025-04-09 21:43:31'),
(5, 2, 5, 10, 56000.00, 'completado', '2025-04-09 21:43:31'),
(6, 2, 8, NULL, 136000.00, 'completado', '2025-04-09 22:15:22'),
(7, 2, 8, 3, 20000.00, 'completado', '2025-04-09 22:15:22'),
(8, 2, 8, 8, 56000.00, 'completado', '2025-04-09 22:15:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` bigint(20) NOT NULL,
  `id_orden` bigint(20) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `codigo_qr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `id_orden`, `monto`, `metodo_pago`, `codigo_qr`) VALUES
(1, 1, 312000.00, 'tarjeta', 'qr_generado_1744207379'),
(2, 3, 155000.00, 'tarjeta', 'qr_generado_1744235011'),
(3, 6, 136000.00, 'tarjeta', 'qr_generado_1744236922');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `id_pelicula` bigint(20) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracion` int(11) NOT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `clasificacion` varchar(20) DEFAULT NULL,
  `imagen_nombre` varchar(255) DEFAULT NULL,
  `titulo_original` varchar(100) DEFAULT NULL,
  `edad_recomendada` varchar(20) DEFAULT NULL,
  `sinopsis` text DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL,
  `fecha_estreno` date DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`id_pelicula`, `titulo`, `descripcion`, `duracion`, `genero`, `clasificacion`, `imagen_nombre`, `titulo_original`, `edad_recomendada`, `sinopsis`, `trailer_url`, `fecha_estreno`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(18, 'Una Película de Minecraft', 'Una película de Minecraft de aventuras basada en el juego del mismo nombre.', 101, 'Aventura', 'Mayores de 7 años', '67f678702be40.jpg', 'A Minecraft Movie', 'Mayores de 7 años', 'Bienvenido al mundo de Minecraft, donde la creatividad no sólo ayuda a crear, sino que es esencial para la supervivencia. Cuatro inadaptados se encuentran luchando con problemas ordinarios cuando de repente se ven arrastrados a través de un misterioso portal al Mundo Exterior: un extraño país de las maravillas cúbico que se nutre de la imaginación. Para volver a casa, tendrán que dominar este mundo mientras se embarcan en una búsqueda mágica en compañía de Steve, un experto artesano.', 'https://youtu.be/wJO_vIDZn-I?si=M4bwl9t54eWZvJf7', '2025-04-03', '2025-04-03 22:22:24', '2025-04-09 13:38:56'),
(19, 'Blanca Nieves', 'Una princesa perseguida por su madrastra, encuentra refugio con siete enanitos.', 109, 'Fantasía', 'Todo público', 'blancanieves.png', 'Snow White', 'Todo Público', 'Una adaptación en vivo del clásico cuento de hadas sobre una hermosa joven princesa que, mientras es acosada por una reina celosa, busca refugio en la casa de siete enanos en la campiña alemana.', 'https://youtu.be/BE0BwFSYXOQ?si=G_6JIWD6o3eyEobY', '2025-03-20', '2025-04-03 22:22:24', '2025-04-09 03:19:15'),
(20, 'Flow', 'El mundo parece estar llegando a su fin, repleto de vestigios de presencia humana. ', 83, 'Animación', 'Para todo público', 'flow.png', 'Flow', 'Todo Público', 'El mundo parece estar llegando a su fin, repleto de vestigios de presencia humana. Gato es un animal solitario, pero como su hogar es arrasado por una gran inundación, encuentra refugio en un barco poblado por varias especies y tendrá que hacer equipo con ellas a pesar de sus diferencias. En el solitario barco que navega a través de místicos paisajes desbordantes, navegan por los desafíos y peligros de adaptarse a este nuevo mundo.', 'https://youtu.be/izIuFUnZkjA?si=M7oZDFE8cXwgXmcr', '2025-02-20', '2025-04-03 22:22:24', '2025-04-09 03:21:13'),
(21, 'Unidos por la Música', 'Un renombrado director de orquesta, ha conquistado escenarios alrededor del mundo.', 103, 'Comedia', 'Mayores de 7 años', 'unidos por la musica.jpg', 'En Fanfare', 'Mayores de 7 años', 'Thibaut, un renombrado director de orquesta, ha conquistado escenarios alrededor del mundo. Pero cuando descubre que fue adoptado, su vida da un giro inesperado: tiene un hermano menor, Jimmy, quien trabaja en la cafetería de una escuela y toca el trombón en una modesta banda de marcha. Aparentemente, todo los separa, excepto su pasión por la música. Al reconocer el talento innato de Jimmy, Thibaut decide cambiar su destino.', 'https://youtu.be/VcC7TaFsZVU?si=cwjD5ShoRsVuETND', '2025-04-03', '2025-04-03 22:22:24', '2025-04-09 03:25:16'),
(22, 'Capirán América: Un Nuevo Mundo', 'Tras reunirse con el presidente, se encuentra en medio de un incidente internacional.', 118, 'Ficción', 'Mayores de 12 años', 'capitan america.png', 'Captain America: Brave New World', 'Mayores de 9 años', 'Tras reunirse con el recién elegido presidente de Estados Unidos, Thaddeus Ross, Sam se encuentra en medio de un incidente internacional. Debe descubrir la razón de un nefasto complot mundial antes de que el verdadero cerebro de la operación haga que el mundo entero se ponga rojo.', 'https://youtu.be/RXoqRPP-y5c?si=tYx6U256EyMLDflZ', '2025-02-13', '2025-04-03 22:22:24', '2025-04-09 03:31:00'),
(23, 'Código Negro', 'Es un drama de espionaje sobre el legendario agente de inteligencia y su amada esposa. ', 93, 'Drama', 'Mayores de 12 años', 'codigo negro.jpg', 'Black Bag', 'Mayores de 12 años', 'Del director Steven Soderbergh, Código negro es un apasionante drama de espionaje sobre el legendario agente de inteligencia George Woodhouse y su amada esposa Kathryn. Cuando ella es sospechosa de traicionar a la nación, George se enfrenta a la prueba definitiva: la lealtad a su matrimonio o a su país.', 'https://youtu.be/AVFM-uAbPtA?si=tJrNhtZZSUEa3ep9', '2025-03-20', '2025-04-03 22:22:24', '2025-04-09 03:33:14'),
(24, 'Mulholland Drive', 'Una joven aspirante a actriz, llega a Los Ángeles para convertirse en estrella de cine.', 147, 'Misterio', 'Mayores de 15 años', 'Mulholland Drive.png', 'Mulholland Drive', 'Mayores de 15 años', 'Una joven aspirante a actriz, llega a Los Ángeles para convertirse en estrella de cine y se aloja en el apartamento de su tía. Allí conoce a Rita, una mujer que padece amnesia, y juntas deciden investigar quién es Rita y cómo llegó hasta allí.', 'https://youtu.be/jbZJ487oJlY?si=cPPGUVVBf-F8HWch', '2025-04-10', '2025-04-03 22:22:24', '2025-04-09 03:36:35'),
(25, 'Los Perez Osos', 'Una familia quedan devastados cuando una tormenta destruye su restaurante.', 84, 'Aventura', 'Para todo público', 'los perezosos.jpg', 'The Sloth Lane', 'Para todo público', 'Laura, una intrépida perezosa de 12 años, y su maravillosa pero extraña familia, quedan devastados cuando una tormenta destruye su restaurante. Se ven obligados a subirse a su viejo y destartalado camión de comida, sin nada más que su preciado libro de recetas y la ilusión de dirigirse a Sanctuary City.', 'https://youtu.be/DUoVC3AmUBg?si=gPA2dOkg23y_ePPN', '2025-03-27', '2025-04-03 22:22:24', '2025-04-09 04:33:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservaciones`
--

CREATE TABLE `reservaciones` (
  `id_reservacion` bigint(20) NOT NULL,
  `id_usuario` bigint(20) NOT NULL,
  `id_funcion` bigint(20) NOT NULL,
  `id_asiento` bigint(20) NOT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservaciones`
--

INSERT INTO `reservaciones` (`id_reservacion`, `id_usuario`, `id_funcion`, `id_asiento`, `fecha_reserva`, `estado`) VALUES
(1, 11, 13, 405, '2025-04-09 14:02:59', 'confirmada'),
(2, 11, 13, 406, '2025-04-09 14:02:59', 'confirmada'),
(3, 11, 13, 407, '2025-04-09 14:02:59', 'confirmada'),
(4, 11, 13, 429, '2025-04-09 14:02:59', 'confirmada'),
(5, 2, 11, 221, '2025-04-09 21:43:31', 'confirmada'),
(6, 2, 11, 222, '2025-04-09 21:43:31', 'confirmada'),
(7, 2, 11, 223, '2025-04-09 21:43:31', 'confirmada'),
(8, 2, 18, 280, '2025-04-09 22:15:22', 'confirmada'),
(9, 2, 18, 281, '2025-04-09 22:15:22', 'confirmada'),
(10, 2, 18, 282, '2025-04-09 22:15:22', 'confirmada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id_sala` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `capacidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`id_sala`, `nombre`, `capacidad`) VALUES
(6, 'Sala Norte', 40),
(7, 'Sala Estelar', 30),
(8, 'Sala Eclipse', 50),
(9, 'Sala VIP', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` bigint(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` text NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(50) DEFAULT 'avatar1.png',
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `contrasena`, `fecha_registro`, `avatar`, `token_recuperacion`, `token_expiracion`) VALUES
(2, 'Daniel León Osorio', 'dleon2053@gmail.com', '$2y$10$UPE/HqgCzZNQL3BBlU05QevPh2Qsn4q9F0cRnptYW/7wGcSkk.QE2', '2025-03-28 14:13:06', 'avatar1.png', 'cbef1ceda151440e3d6e4919ad31f3fa1c30177106bd3da54b440eaaec705b49', '2025-04-09 21:30:32'),
(6, 'Pedra Pancrasia', 'pedrotalamacha@gmail.com', '$2y$10$aRJDJHQL7YBIA6Re1Hqb5OrB/WHyQ7Gqc/Q8FPmL6EWiaHDNNd.jC', '2025-04-01 01:05:06', 'avatar1.png', 'a6aef7926f2e97dc8b0344548929910b560b109afb61f681dd6fe486f7c62586', '2025-04-09 09:21:29'),
(11, 'Evelyn Velandia', 'evelynvelandialanziano@gmail.com', '$2y$10$Pvha9cFeqfhL2FVjxeP0DOR1VS/USBZ50qpaeAcq/Nzsh29nv9maq', '2025-04-09 06:22:45', 'avatar1.png', 'cd88d3eb6c0e2e1aa1c3cc2a99da1afab23af579ffa42d862514c2516288b794', '2025-04-09 17:38:58'),
(12, 'Administrador', 'administrador@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-04-09 20:54:56', 'admin_avatar.jpg', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asientos`
--
ALTER TABLE `asientos`
  ADD PRIMARY KEY (`id_asiento`),
  ADD KEY `id_sala` (`id_sala`);

--
-- Indices de la tabla `carrusel`
--
ALTER TABLE `carrusel`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `combos`
--
ALTER TABLE `combos`
  ADD PRIMARY KEY (`id_combo`);

--
-- Indices de la tabla `funciones`
--
ALTER TABLE `funciones`
  ADD PRIMARY KEY (`id_funcion`),
  ADD KEY `id_pelicula` (`id_pelicula`),
  ADD KEY `id_sala` (`id_sala`);

--
-- Indices de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD PRIMARY KEY (`id_orden`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_reservacion` (`id_reservacion`),
  ADD KEY `id_combo` (`id_combo`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_orden` (`id_orden`);

--
-- Indices de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  ADD PRIMARY KEY (`id_pelicula`);

--
-- Indices de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD PRIMARY KEY (`id_reservacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_funcion` (`id_funcion`),
  ADD KEY `id_asiento` (`id_asiento`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id_sala`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asientos`
--
ALTER TABLE `asientos`
  MODIFY `id_asiento` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;

--
-- AUTO_INCREMENT de la tabla `carrusel`
--
ALTER TABLE `carrusel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `combos`
--
ALTER TABLE `combos`
  MODIFY `id_combo` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `funciones`
--
ALTER TABLE `funciones`
  MODIFY `id_funcion` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  MODIFY `id_orden` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  MODIFY `id_pelicula` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  MODIFY `id_reservacion` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id_sala` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asientos`
--
ALTER TABLE `asientos`
  ADD CONSTRAINT `asientos_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON DELETE CASCADE;

--
-- Filtros para la tabla `funciones`
--
ALTER TABLE `funciones`
  ADD CONSTRAINT `funciones_ibfk_1` FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas` (`id_pelicula`) ON DELETE CASCADE,
  ADD CONSTRAINT `funciones_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD CONSTRAINT `ordenes_compra_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordenes_compra_ibfk_2` FOREIGN KEY (`id_reservacion`) REFERENCES `reservaciones` (`id_reservacion`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordenes_compra_ibfk_3` FOREIGN KEY (`id_combo`) REFERENCES `combos` (`id_combo`) ON DELETE SET NULL;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_compra` (`id_orden`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD CONSTRAINT `reservaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservaciones_ibfk_2` FOREIGN KEY (`id_funcion`) REFERENCES `funciones` (`id_funcion`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservaciones_ibfk_3` FOREIGN KEY (`id_asiento`) REFERENCES `asientos` (`id_asiento`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
