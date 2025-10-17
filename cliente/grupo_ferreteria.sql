-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-06-2025 a las 19:31:11
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
-- Base de datos: `grupo_ferreteria`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_ObtenerDatosDashboard` ()   BEGIN
    DECLARE totalProductos INT;
    DECLARE totalCompras FLOAT;
    DECLARE totalVentas FLOAT;
    DECLARE ganancias FLOAT;
    DECLARE productosPocoStock INT;
    DECLARE ventasHoy FLOAT;

    -- Total de productos registrados
    SET totalProductos = (SELECT COUNT(*) FROM producto);

    -- Total invertido: suma del stock por precio de compra (último registro activo por producto)
    SET totalCompras = (
        SELECT SUM(pp.precio_compra * pp.cantidad)
        FROM producto_proveedor pp
        WHERE pp.estado = 'activo'
    );

    -- Total de ventas realizadas (sumar los totales de facturas)
    SET totalVentas = (
        SELECT SUM(f.total)
        FROM factura f
    );

    -- Productos con poco stock (menor o igual al stock mínimo)
    SET productosPocoStock = (
        SELECT COUNT(*) FROM producto
        WHERE stock <= min_stock
    );

    -- Ganancias (ventas - costo de compra más reciente)
    SET ganancias = (
        SELECT SUM(df.cantidad * df.precio_unitario) - SUM(
            df.cantidad * (
                SELECT pp.precio_compra
                FROM producto_proveedor pp
                WHERE pp.id_producto = df.id_producto
                ORDER BY pp.fecha_suministro DESC
                LIMIT 1
            )
        )
        FROM detalle_factura df
    );

    -- Ventas realizadas hoy
    SET ventasHoy = (
        SELECT SUM(f.total)
        FROM factura f
        WHERE DATE(f.fecha) = CURDATE()
    );

    -- Resultado final
    SELECT
        IFNULL(totalProductos, 0) AS totalProductos,
        IFNULL(ROUND(totalCompras, 2), 0) AS totalCompras,
        IFNULL(ROUND(totalVentas, 2), 0) AS totalVentas,
        IFNULL(ROUND(ganancias, 2), 0) AS ganancias,
        IFNULL(productosPocoStock, 0) AS productosPocoStock,
        IFNULL(ROUND(ventasHoy, 2), 0) AS ventasHoy;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `admin_usuario` varchar(50) NOT NULL,
  `admin_contrasena` varchar(255) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_persona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_admin`, `admin_usuario`, `admin_contrasena`, `activo`, `fecha_creacion`, `fecha_actualizacion`, `id_persona`) VALUES
(1, 'luis', 'luisadmin', 1, '2025-06-14 21:39:24', '2025-06-14 21:39:58', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id_asistencia`, `id_empleado`, `fecha`, `hora_entrada`, `hora_salida`) VALUES
(1, 1, '2025-06-15', '18:41:46', NULL),
(2, 1, '2025-06-16', '08:16:14', '08:16:18'),
(3, 3, '2025-06-16', '21:52:59', '21:53:03'),
(4, 1, '2025-06-17', '00:47:33', '00:47:36'),
(5, 3, '2025-06-17', '00:48:02', '00:48:04'),
(6, 2, '2025-06-17', '01:51:57', '01:51:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo_empleado`
--

CREATE TABLE `cargo_empleado` (
  `id_cargo` int(11) NOT NULL,
  `nombre_cargo` varchar(100) NOT NULL,
  `sueldo_base` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargo_empleado`
--

INSERT INTO `cargo_empleado` (`id_cargo`, `nombre_cargo`, `sueldo_base`) VALUES
(1, 'Encargado de Tienda', 3500.00),
(2, 'Vendedor', 3000.00),
(3, 'Cajero', 2800.00),
(4, 'Almacenero', 2700.00),
(5, 'Ayudante de Mostrador', 2500.00),
(6, 'Repartidor', 2000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED,
  `fecha_agregado` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id_carrito`, `id_cliente`, `id_producto`, `cantidad`, `precio_unitario`, `fecha_agregado`) VALUES
(34, 2, 49, 1, 65.00, '2025-06-17 01:27:40'),
(35, 2, 50, 1, 590.00, '2025-06-17 01:27:41'),
(36, 2, 51, 1, 120.00, '2025-06-17 01:27:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`) VALUES
(1, 'HERRAMIENTAS MANUALES'),
(2, 'HERRAMIENTAS ELÉCTRICAS'),
(3, 'MATERIALES DE CONSTRUCCIÓN'),
(4, 'TORNILLERÍA Y SUJECIÓN'),
(5, 'ELECTRICIDAD'),
(6, 'FONTANERÍA'),
(7, 'PINTURAS Y ACCESORIOS'),
(8, 'SEGURIDAD INDUSTRIAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_persona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `usuario`, `contrasena`, `fecha_creacion`, `id_persona`) VALUES
(1, 'carla', '1234', '2025-06-14 21:17:46', 1),
(2, 'kataria', '12345', '2025-06-16 04:43:52', 6),
(3, 'luisg92', 'luis1234', '2025-06-16 04:48:54', 7),
(4, 'maria88', 'maria123', '2025-06-16 04:48:54', 8),
(5, 'carlosv95', 'carlos456', '2025-06-16 04:48:54', 9),
(6, 'analu90', 'ana2020', '2025-06-16 04:48:54', 10),
(7, 'jorger87', 'jorge321', '2025-06-16 04:48:54', 11),
(8, 'camila96', 'camila789', '2025-06-16 04:48:54', 12),
(9, 'rodrigop', 'rodrigo111', '2025-06-16 04:48:54', 13),
(10, 'valeriaa', 'valeria000', '2025-06-16 04:48:54', 14),
(11, 'andres94', 'andres777', '2025-06-16 04:48:54', 15),
(12, 'paolag', 'paola888', '2025-06-16 04:48:54', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id_departamento` int(11) NOT NULL,
  `nom_departamento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id_departamento`, `nom_departamento`) VALUES
(1, 'La Paz'),
(2, 'Santa Cruz'),
(3, 'Chuquisaca'),
(4, 'Cochabamba'),
(5, 'Oruro'),
(6, 'Potosí'),
(7, 'Tarija'),
(8, 'Beni'),
(9, 'Pando');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `id_detalle` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`id_detalle`, `id_factura`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 53, 1, 500.00),
(2, 2, 1, 1, 20.00),
(3, 3, 24, 1, 0.80),
(4, 3, 10, 1, 560.00),
(5, 3, 5, 2, 50.00),
(7, 4, 12, 1, 420.00),
(8, 4, 50, 1, 590.00),
(9, 5, 44, 1, 35.00),
(10, 5, 50, 1, 590.00),
(11, 5, 12, 1, 420.00),
(12, 6, 11, 1, 750.00),
(13, 6, 1, 1, 20.00),
(14, 7, 5, 1, 50.00),
(15, 7, 10, 1, 560.00),
(16, 7, 49, 1, 65.00),
(17, 7, 18, 17, 2.00),
(18, 7, 4, 1, 70.00),
(19, 8, 24, 2, 0.80),
(20, 9, 24, 1, 0.80),
(21, 9, 10, 1, 560.00),
(22, 9, 13, 1, 350.00),
(23, 9, 49, 1, 65.00),
(24, 9, 32, 1, 30.00),
(25, 9, 44, 1, 35.00),
(26, 10, 16, 1, 25.00),
(27, 11, 24, 1, 0.80),
(28, 12, 5, 1, 50.00),
(29, 13, 16, 1, 25.00),
(30, 14, 10, 1, 560.00),
(31, 15, 24, 1, 0.80),
(32, 15, 10, 1, 560.00),
(33, 16, 5, 1, 50.00),
(34, 18, 10, 1, 560.00),
(35, 18, 50, 1, 590.00),
(36, 18, 12, 1, 420.00),
(37, 18, 44, 1, 35.00),
(38, 19, 10, 1, 560.00),
(39, 19, 5, 1, 50.00),
(40, 20, 10, 1, 560.00),
(41, 20, 5, 1, 50.00),
(42, 21, 10, 1, 560.00),
(43, 21, 24, 1, 0.80),
(44, 22, 5, 1, 50.00),
(45, 22, 10, 1, 560.00),
(46, 23, 24, 1, 0.80),
(47, 23, 10, 1, 560.00),
(48, 24, 40, 3, 35.00),
(49, 24, 52, 1, 10.00),
(50, 24, 53, 1, 500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`, `total`) VALUES
(1, 1, 40, 3, 35.00, 105.00),
(2, 1, 52, 1, 10.00, 10.00),
(3, 1, 53, 1, 500.00, 500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_empleado` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_ingreso` date DEFAULT curdate(),
  `estado` tinyint(1) DEFAULT 1,
  `salario` decimal(10,2) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_cargo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_empleado`, `usuario`, `contrasena`, `fecha_ingreso`, `estado`, `salario`, `id_persona`, `id_sucursal`, `id_cargo`) VALUES
(1, 'ronaldo', '117', '2025-06-14', 1, 2800.00, 2, 1, 2),
(2, 'pilar', 'gonzales', '2025-06-15', 1, 2000.00, 3, 2, 5),
(3, 'cristobal', 'quispe', '2025-06-15', 1, 3000.00, 4, 1, 6),
(4, 'galindo', 'maria1234', '2025-06-15', 1, 2000.00, 5, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(11) NOT NULL,
  `tipo_venta` enum('virtual','presencial') NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_metodo` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','completado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_factura`, `tipo_venta`, `id_cliente`, `id_empleado`, `total`, `id_metodo`, `fecha`, `estado`) VALUES
(1, 'presencial', NULL, 1, 500.00, 2, '2025-06-14 22:24:31', 'pendiente'),
(2, 'presencial', NULL, 1, 20.00, 2, '2025-06-15 22:54:52', 'pendiente'),
(3, 'presencial', NULL, 1, 680.80, 1, '2025-06-16 03:50:58', 'pendiente'),
(4, 'presencial', NULL, 1, 1010.00, 1, '2025-06-16 04:57:29', 'pendiente'),
(5, 'presencial', NULL, 1, 1045.00, 1, '2025-06-16 05:25:00', 'pendiente'),
(6, 'presencial', NULL, 1, 770.00, 1, '2025-06-16 16:45:21', 'pendiente'),
(7, 'presencial', NULL, 1, 779.00, 2, '2025-06-16 16:48:03', 'pendiente'),
(8, 'presencial', NULL, 1, 1.60, 2, '2025-06-16 18:37:48', 'pendiente'),
(9, 'presencial', NULL, 3, 1040.80, 2, '2025-06-16 18:39:54', 'pendiente'),
(10, 'presencial', NULL, 3, 25.00, 3, '2025-06-16 18:52:15', 'pendiente'),
(11, 'presencial', NULL, 3, 0.80, 1, '2025-06-16 19:11:21', 'pendiente'),
(12, 'presencial', NULL, 3, 50.00, 3, '2025-06-16 19:16:57', 'pendiente'),
(13, 'virtual', 1, 3, 25.00, 3, '2025-06-16 19:21:45', 'completado'),
(14, 'presencial', NULL, 3, 560.00, 1, '2025-06-16 19:47:04', 'pendiente'),
(15, 'virtual', 1, 3, 560.80, 2, '2025-06-16 19:49:14', 'completado'),
(16, 'presencial', NULL, 3, 50.00, 3, '2025-06-16 19:51:41', 'pendiente'),
(18, 'presencial', NULL, 1, 1605.00, 3, '2025-06-16 22:30:12', 'pendiente'),
(19, 'presencial', NULL, 1, 610.00, 1, '2025-06-16 22:40:06', 'pendiente'),
(20, 'presencial', NULL, 1, 610.00, 1, '2025-06-16 22:44:46', 'pendiente'),
(21, 'presencial', NULL, 1, 560.80, 1, '2025-06-16 23:16:09', 'pendiente'),
(22, 'presencial', NULL, 1, 610.00, 2, '2025-06-17 02:43:26', 'pendiente'),
(23, 'presencial', NULL, 1, 560.80, 1, '2025-06-17 03:21:14', 'pendiente'),
(24, 'virtual', 1, NULL, 630.00, 1, '2025-06-17 17:24:04', 'completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `id_metodo` int(11) NOT NULL,
  `nombre_metodo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodo_pago`
--

INSERT INTO `metodo_pago` (`id_metodo`, `nombre_metodo`) VALUES
(1, 'Tarjeta'),
(2, 'Efectivo'),
(3, 'QR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `id_sucursal` int(11) NOT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp(),
  `estado` enum('pendiente','procesado','enviado','entregado','cancelado') DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL,
  `tipo_pago` enum('efectivo','tarjeta','transferencia','otro') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_cliente`, `id_empleado`, `id_sucursal`, `fecha_pedido`, `estado`, `total`, `tipo_pago`) VALUES
(1, 1, NULL, 1, '2025-06-17 13:24:04', 'pendiente', 630.00, 'tarjeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `ci` varchar(20) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `genero` enum('Femenino','Masculino') NOT NULL,
  `id_departamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombres`, `apellidos`, `ci`, `fecha_nacimiento`, `direccion`, `telefono`, `correo`, `genero`, `id_departamento`) VALUES
(1, 'Carla', 'Quispe Ticona', '23214353413', '2003-06-12', 'calle 2 frente a la gran capital', '21321534324', 'correo@ejemplo.com', 'Femenino', 1),
(2, 'Luis Ronaldo ', 'Mamani Mayta', '10071090', '2003-03-31', 'Av. Camino a Laja, calle punata #4464', '63171544', 'luisronaldomamanimayta01@gmail.com', 'Masculino', 1),
(3, 'Pilar', 'Gonzales Poma', '100298324', '2000-02-26', 'Av. Laja serca del Local Caballetos del Zodiaco', '72547069', 'Pilar@gmail.com', 'Femenino', 1),
(4, 'Cristobal ', 'Ticona Quispe', '281390242', '2001-05-28', 'Calle Juan Felipe #3425', '726372941', 'cristoba@gmail.com', 'Masculino', 3),
(5, 'Maria Galindo', 'Chacinto Marquez', '23243465', '1990-03-21', 'Urbanizacion Juan Salvador Calle 2 #3452', '728382415', 'MARIAGALINDO@gmail.com', 'Femenino', 2),
(6, 'Kataria', 'Tarqui Chelas', '2324324532', '1990-03-02', 'Calle sucursal de la paz distrito 14 calle uya #235', '3213243532', 'kataew@gmail.com', 'Femenino', 1),
(7, 'Luis Fernando', 'Gómez Salazar', '8596234', '1992-04-12', 'Av. Cañoto N° 123', '72145623', 'luis.gomez@gmail.com', 'Masculino', 3),
(8, 'María José', 'Rojas Pinto', '7201548', '1988-09-22', 'Calle Colón #88', '77896541', 'maria.rojas@gmail.com', 'Femenino', 1),
(9, 'Carlos Andrés', 'Vargas Méndez', '6314521', '1995-01-15', 'Av. Banzer, Edif. El Faro', '76781234', 'carlos.vargas@gmail.com', 'Masculino', 6),
(10, 'Ana Luisa', 'Quiroz Romero', '8596632', '1990-06-30', 'Calle Bolívar #45', '76543128', 'ana.quiroz@gmail.com', 'Femenino', 5),
(11, 'Jorge Luis', 'Ramírez Ortega', '7985263', '1987-12-08', 'Zona Norte, calle 4', '72111222', 'jorge.ramirez@gmail.com', 'Masculino', 2),
(12, 'Camila Fernanda', 'Molina Téllez', '6354871', '1996-08-18', 'Av. Busch #1570', '78326541', 'camila.molina@gmail.com', 'Femenino', 9),
(13, 'Rodrigo', 'Peralta Gutiérrez', '7245139', '1993-03-11', 'Calle 21 de Calacoto', '75896321', 'rodrigo.peralta@gmail.com', 'Masculino', 8),
(14, 'Valeria', 'Aguilar Cárdenas', '6412789', '2000-11-25', 'Av. Circunvalación 304', '79987654', 'valeria.aguilar@gmail.com', 'Femenino', 7),
(15, 'Andrés', 'Morales Suárez', '7015234', '1994-02-07', 'Av. Alemania N° 501', '74562133', 'andres.morales@gmail.com', 'Masculino', 4),
(16, 'Paola', 'Gutiérrez Mendoza', '6689457', '1991-10-20', 'Calle Sucre #12', '76982345', 'paola.gutierrez@gmail.com', 'Femenino', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `min_stock` int(11) DEFAULT 5,
  `imagen` varchar(255) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `precio_unitario`, `stock`, `min_stock`, `imagen`, `id_categoria`) VALUES
(1, 'Martillo de Carpintero', 'Herramienta para clavar y extraer clavos, con cabeza de acero y mango ergonómico.', 20.00, 98, 5, 'prod_684643e2b65c5.webp', 1),
(2, 'Destornillador plano', 'Herramienta para atornillar o desatornillar tornillos de ranura recta.', 15.00, 100, 5, 'prod_684644071c3e1.webp', 1),
(3, 'Destornillador Philips', 'Similar al plano, pero con punta en forma de cruz para tornillos Philips.', 15.00, 100, 5, 'prod_6846443eb7fde.jpg', 4),
(4, 'Llave Inglesa', 'Llave ajustable para tuercas y pernos de distintos tamaños.', 70.00, 99, 5, 'prod_6846448a2baf9.jpg', 1),
(5, 'Alicate', 'Herramienta para sujetar, cortar o doblar alambres y cables.', 50.00, 92, 5, 'prod_684644a52a212.jpg', 1),
(6, 'Cinta métrica', 'Instrumento para medir longitudes, con carcasa retráctil.', 30.00, 100, 5, 'prod_684644d72a0f5.png', 1),
(7, 'Serrucho', 'Sierra manual para cortar madera, con dientes grandes y mango cómodo.', 80.00, 100, 5, 'prod_68464527395ff.webp', 1),
(8, 'Nivel de Burbuja', 'Instrumento para verificar si una superficie está nivelada.', 40.00, 100, 5, 'prod_6846454e5f97e.webp', 1),
(9, 'Taladro eléctrico', 'Herramienta para perforar madera, metal u hormigón, con diferentes brocas.', 450.00, 100, 5, 'taladroeletrico1.jpg', 2),
(10, 'Amoladora angular', 'Máquina para cortar, lijar o pulir con disco rotativo.', 560.00, 89, 5, 'amuladoraangular1.jpg', 2),
(11, 'Sierra circular', 'Herramienta eléctrica para cortes rectos en madera o metal.', 750.00, 99, 5, 'gierracircular1.jpg', 2),
(12, 'Atornillador eléctrico', 'Herramienta para atornillar o desatornillar rápidamente con batería recargable.', 420.00, 97, 5, 'artornilladorelectrico1.webp', 2),
(13, 'Lijadora', 'Máquina para alisar superficies mediante papel de lija.', 350.00, 99, 5, 'lijadora1.jpg', 2),
(14, 'Rotomartillo', 'Taladro potente para perforar concreto, con función de impacto.', 850.00, 100, 5, 'rotoartilo1.jpg', 2),
(15, 'Cemento (50 Kg)', 'Polvo fino que mezclado con agua forma una pasta que endurece.', 45.00, 100, 5, 'cemento1.jpg', 3),
(16, 'Arena (carga pequeña)', 'Material granulado usado como agregado en concreto o mezcla.', 25.00, 98, 5, 'arenapequeña1.webp', 3),
(17, 'Grava (carga pequeña)', 'Piedra triturada usada en mezclas de concreto.', 15.00, 100, 5, 'graba1.webp', 3),
(18, 'Ladrillos (por unidad)', 'Bloques de cemento usados para muros.', 2.00, 4983, 100, 'ladrillo1.webp', 3),
(19, 'Cal (25 Kg)', 'Polvo blanco usado en mezclas para construcción y acabados.', 30.00, 100, 5, 'cal1.webp', 3),
(20, 'Yeso (25 Kg)', 'Material blanco para acabados, molduras o reparaciones.', 45.00, 100, 5, 'yeso1.webp', 3),
(21, 'Tornillos para madera (caja 100 unidades)', 'Tornillos con rosca gruesa, ideales para unir piezas de madera.', 35.00, 100, 5, 'tornillometal100uni.webp', 4),
(22, 'Tornillos para metal (caja 100 unidades)', 'Tornillos para rosca fina y cuerpo frente, para estructuras metálicas.', 45.00, 100, 5, 'tornillometal100un.webp', 4),
(23, 'Tuercas (por unidad)', 'Elementos roscados que se usan junto a tornillos o pernos.', 1.00, 100, 5, 'tuerca1.webp', 4),
(24, 'Arandelas (por unidad)', 'Discos que van entre tornillo y superficie para distribuir presión.', 0.80, 92, 5, 'arandelas1.webp', 4),
(25, 'Clavos (caja 100 unidades)', 'Piezas metálicas delgadas usadas para fijaciones rápidas en madera.', 25.00, 100, 5, 'prod_6849f6290c832.jpg', 4),
(26, 'Pernos', 'Barras roscadas más gruesas, usadas en estructuras resistentes.', 5.00, 100, 5, 'pernos1.webp', 4),
(27, 'Tarugos (paquete 50 unidades)', 'Soportes de plástico que permiten fijar tornillos en paredes.', 30.00, 100, 5, 'prod_6849e9fc84b06.webp', 4),
(28, 'Cables eléctricos (por metro)', 'Conductores recubiertos para instalaciones eléctricas.', 35.00, 100, 5, 'prod_6849394870908.webp', 5),
(29, 'Enchufes (por unidad)', 'Dispositivos que permiten conectar aparatos a la corriente.', 25.00, 100, 5, 'prod_68466f9d9dc37.webp', 5),
(30, 'Tomas y clavijas (por unidad)', 'Conectores macho y hembra para instalaciones eléctricas.', 20.00, 100, 5, 'prod_68466ef8c2c4c.jpeg', 5),
(31, 'Interruptores', 'Dispositivos para encender o apagar el paso de corriente.', 35.00, 100, 5, 'prod_68466ec3eb9ac.jpg', 5),
(32, 'Focos LED', 'Bombillas de bajo consumo con iluminación eficiente.', 30.00, 99, 5, 'prod_68466e4c95504.jpg', 5),
(33, 'Cinta aislante', 'Cinta plástica para aislar cables eléctricos y evitar cortos.', 6.00, 100, 5, 'prod_68466e09de5e1.jpg', 5),
(34, 'Canaletas (por metro)', 'Canales de plástico para cubrir y organizar cables.', 25.00, 100, 5, 'prod_68466db5eb7ad.webp', 5),
(35, 'Tubos PVC', 'Tuberías plásticas para agua potable. ', 70.00, 100, 5, 'prod_68466b7150c83.webp', 6),
(36, 'Conexiones PVC', 'Piezas como codos, uniones para unir tubos PVC.', 10.00, 100, 5, 'prod_68466b4c5c1c9.webp', 6),
(37, 'Llaves de paso', 'Válvulas que controlan el flujo de agua en las tuberías.', 50.00, 100, 5, 'prod_68466ad58d5ce.webp', 6),
(38, 'Duchas', 'Dispositivos que dispersan agua para uso en baños.', 175.00, 100, 5, 'prod_68466aa0d5381.webp', 6),
(39, 'Grifos', 'Llaves de control de agua para lavamanos o fregaderos.', 90.00, 100, 5, 'prod_68466a328a292.webp', 6),
(40, 'Pegamento para PVC', 'Adhesivo especial para unir tubos y conexiones de PVC', 35.00, 97, 5, 'prod_684669f4e9185.jpg', 6),
(41, 'Pintura látex interior (4 litros)', 'Pintura base agua, ideal para paredes interiores.', 235.00, 100, 5, 'prod_6846438c71158.png', 7),
(42, 'Pintura esmalte sintético (4 litros)', 'Pintura de acabado brillante, resistente y lavable.', 285.00, 100, 5, 'prod_6846434a06a35.webp', 7),
(43, 'Rodillos', 'Herramientas cilíndricas para aplicar pintura sobre superficies grandes.', 45.00, 100, 5, 'prod_68464311a3cc6.jpg', 7),
(44, 'Brochas', 'Pinceles grandes para pintar bordes o detalles.', 35.00, 97, 5, 'prod_6846427022c60.jpg', 7),
(45, 'Diluyente (por litro)', 'Líquido que reduce la viscosidad de la pintura o limpia herramientas.', 45.00, 100, 5, 'prod_68464243cf8f6.png', 7),
(46, 'Cintas para enmascarar', 'Cinta adhesiva para delimitar áreas al pintar.', 15.00, 100, 5, 'prod_684642064241d.png', 7),
(47, 'Guantes de trabajo', 'Protección para manos contra cortes, químicos o golpes.', 30.00, 100, 5, 'prod_684641bd105d5.jpg', 8),
(48, 'Casco de seguridad', 'Protección para la cabeza en obras o zonas de riesgo.', 180.00, 100, 5, 'prod_684641b490020.webp', 8),
(49, 'Lentes de protección', 'Gafas para proteger los ojos de partículas o polvo.', 65.00, 98, 5, 'prod_6846415dcf375.webp', 8),
(50, 'Botas con punta de acero', 'Calzado resistente para evitar lesiones en pies.', 590.00, 97, 5, 'prod_684641302a484.png', 8),
(51, 'Chaleco reflectivo', 'Prenda con bandas reflectantes para alta visibilidad.', 120.00, 100, 5, 'prod_684639c202195.jpeg', 8),
(52, 'Tapabocas', 'Protección respiratoria contra polvo o químicos.', 10.00, 99, 5, 'prod_68463881b47c8.webp', 8),
(53, 'Taza de inodoro moderno ', 'tasa super resistente que soporta 100kg ', 500.00, 98, 20, 'tasa1.jpg', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_proveedor`
--

CREATE TABLE `producto_proveedor` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `cantidad` int(11) DEFAULT 0,
  `fecha_suministro` date DEFAULT curdate(),
  `tiempo_entrega_dias` int(11) NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_proveedor`
--

INSERT INTO `producto_proveedor` (`id`, `id_producto`, `id_proveedor`, `precio_compra`, `cantidad`, `fecha_suministro`, `tiempo_entrega_dias`, `estado`) VALUES
(1, 53, 4, 400.00, 100, '2025-06-14', 7, 'activo'),
(2, 1, 6, 15.00, 100, '2025-06-14', 5, 'activo'),
(3, 2, 2, 10.00, 100, '2025-06-15', 5, 'activo'),
(4, 1, 1, 16.00, 100, '2025-06-15', 3, 'activo'),
(5, 2, 1, 12.00, 100, '2025-06-15', 3, 'activo'),
(6, 3, 1, 12.00, 100, '2025-06-15', 3, 'activo'),
(7, 4, 1, 56.00, 100, '2025-06-15', 3, 'activo'),
(8, 5, 1, 40.00, 100, '2025-06-15', 3, 'activo'),
(9, 6, 1, 24.00, 100, '2025-06-15', 3, 'activo'),
(10, 7, 1, 64.00, 100, '2025-06-15', 3, 'activo'),
(11, 8, 1, 32.00, 100, '2025-06-15', 3, 'activo'),
(12, 9, 1, 360.00, 100, '2025-06-15', 3, 'activo'),
(13, 10, 1, 448.00, 100, '2025-06-15', 3, 'activo'),
(14, 11, 1, 600.00, 100, '2025-06-15', 3, 'activo'),
(15, 12, 1, 336.00, 100, '2025-06-15', 3, 'activo'),
(16, 13, 1, 280.00, 100, '2025-06-15', 3, 'activo'),
(17, 14, 1, 680.00, 100, '2025-06-15', 3, 'activo'),
(18, 15, 1, 36.00, 100, '2025-06-15', 3, 'activo'),
(19, 16, 1, 20.00, 100, '2025-06-15', 3, 'activo'),
(20, 17, 1, 12.00, 100, '2025-06-15', 3, 'activo'),
(21, 18, 1, 1.60, 5000, '2025-06-15', 3, 'activo'),
(22, 19, 1, 24.00, 100, '2025-06-15', 3, 'activo'),
(23, 20, 1, 36.00, 100, '2025-06-15', 3, 'activo'),
(24, 21, 1, 28.00, 100, '2025-06-15', 3, 'activo'),
(25, 22, 1, 36.00, 100, '2025-06-15', 3, 'activo'),
(26, 23, 1, 0.80, 100, '2025-06-15', 3, 'activo'),
(27, 24, 1, 0.64, 100, '2025-06-15', 3, 'activo'),
(28, 25, 1, 20.00, 100, '2025-06-15', 3, 'activo'),
(29, 26, 1, 4.00, 100, '2025-06-15', 3, 'activo'),
(30, 27, 1, 24.00, 100, '2025-06-15', 3, 'activo'),
(31, 28, 1, 28.00, 100, '2025-06-15', 3, 'activo'),
(32, 29, 1, 20.00, 100, '2025-06-15', 3, 'activo'),
(33, 30, 1, 16.00, 100, '2025-06-15', 3, 'activo'),
(34, 31, 1, 28.00, 100, '2025-06-15', 3, 'activo'),
(35, 32, 1, 24.00, 100, '2025-06-15', 3, 'activo'),
(36, 33, 1, 4.80, 100, '2025-06-15', 3, 'activo'),
(37, 34, 1, 20.00, 100, '2025-06-15', 3, 'activo'),
(38, 35, 1, 56.00, 100, '2025-06-15', 3, 'activo'),
(39, 36, 1, 8.00, 100, '2025-06-15', 3, 'activo'),
(40, 37, 1, 40.00, 100, '2025-06-15', 3, 'activo'),
(41, 38, 1, 140.00, 100, '2025-06-15', 3, 'activo'),
(42, 39, 1, 72.00, 100, '2025-06-15', 3, 'activo'),
(43, 40, 1, 28.00, 100, '2025-06-15', 3, 'activo'),
(44, 41, 1, 188.00, 100, '2025-06-15', 3, 'activo'),
(45, 42, 1, 228.00, 100, '2025-06-15', 3, 'activo'),
(46, 43, 1, 36.00, 100, '2025-06-15', 3, 'activo'),
(47, 44, 1, 28.00, 100, '2025-06-15', 3, 'activo'),
(48, 45, 1, 36.00, 100, '2025-06-15', 3, 'activo'),
(49, 46, 1, 12.00, 100, '2025-06-15', 3, 'activo'),
(50, 47, 1, 24.00, 100, '2025-06-15', 3, 'activo'),
(51, 48, 1, 144.00, 100, '2025-06-15', 3, 'activo'),
(52, 49, 1, 52.00, 100, '2025-06-15', 3, 'activo'),
(53, 50, 1, 472.00, 100, '2025-06-15', 3, 'activo'),
(54, 51, 1, 96.00, 100, '2025-06-15', 3, 'activo'),
(55, 52, 1, 8.00, 100, '2025-06-15', 3, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre`, `direccion`, `telefono`, `email`) VALUES
(1, 'Ferretería El Tornillo SRL', 'Calle Illampu #456, La Paz', '+591 2 2356789', 'contacto@eltornillo.bo'),
(2, 'Distribuidora Andina de Materiales', 'Av. Blanco Galindo Km 5, Cochabamba', '+591 4 4567890', 'ventas@andinamateriales.bo'),
(3, 'Suministros Industriales Chuquisaca', 'Calle Avaroa #89, Sucre', '+591 4 6421133', 'info@chuquisacasuministros.bo'),
(4, 'Proveedora del Oriente', '3er Anillo y Av. Beni, Santa Cruz', '+591 3 3456789', 'proveedoraoriente@gmail.com'),
(5, 'Herramientas y Más SRL', 'Av. Busch #1023, El Alto', '+591 2 2837465', 'herramientasymas@outlook.com'),
(6, 'Metálicas Potosí', 'Calle La Paz #75, Potosí', '+591 2 6223344', 'ventas@metalicaspotosi.bo'),
(7, 'Materiales Tarija S.A.', 'Zona Mercado Campesino, Tarija', '+591 4 6668899', 'contacto@materialestarija.bo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reclamos`
--

CREATE TABLE `reclamos` (
  `id_reclamo` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_reclamo` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reclamos`
--

INSERT INTO `reclamos` (`id_reclamo`, `descripcion`, `fecha_reclamo`, `id_cliente`) VALUES
(1, 'El producto me llego super tarde y no es del color que pedi', '2025-06-14 23:23:34', 1),
(2, 'El joven me pego', '2025-06-15 02:45:19', 1),
(3, 'el producto me llego super tarde y me dieron un billete roto', '2025-06-17 16:26:15', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_sucursal`
--

CREATE TABLE `stock_sucursal` (
  `id_stock` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `id_sucursal` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `nombre`, `direccion`, `email`, `telefono`) VALUES
(1, 'Sucursal El Prado', 'Av. 16 de Julio #789, El Prado', 'elprado@gmail.com', '77683912'),
(2, 'Sucursal Sopocachi', 'Calle Aspiazu #456, Sopocachi', 'sofocachi@gmail.com', '78965423');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `admin_usuario` (`admin_usuario`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `cargo_empleado`
--
ALTER TABLE `cargo_empleado`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_sucursal` (`id_sucursal`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_metodo` (`id_metodo`);

--
-- Indices de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`id_metodo`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_sucursal` (`id_sucursal`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `ci` (`ci`),
  ADD KEY `id_departamento` (`id_departamento`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `producto_proveedor`
--
ALTER TABLE `producto_proveedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD PRIMARY KEY (`id_reclamo`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `stock_sucursal`
--
ALTER TABLE `stock_sucursal`
  ADD PRIMARY KEY (`id_stock`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_sucursal` (`id_sucursal`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`id_sucursal`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cargo_empleado`
--
ALTER TABLE `cargo_empleado`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `producto_proveedor`
--
ALTER TABLE `producto_proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  MODIFY `id_reclamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `stock_sucursal`
--
ALTER TABLE `stock_sucursal`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `id_sucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`);

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`);

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `detalle_factura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`),
  ADD CONSTRAINT `detalle_factura_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`),
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`id_cargo`) REFERENCES `cargo_empleado` (`id_cargo`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`),
  ADD CONSTRAINT `factura_ibfk_3` FOREIGN KEY (`id_metodo`) REFERENCES `metodo_pago` (`id_metodo`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`),
  ADD CONSTRAINT `pedido_ibfk_3` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id_departamento`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `producto_proveedor`
--
ALTER TABLE `producto_proveedor`
  ADD CONSTRAINT `producto_proveedor_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `producto_proveedor_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD CONSTRAINT `reclamos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Filtros para la tabla `stock_sucursal`
--
ALTER TABLE `stock_sucursal`
  ADD CONSTRAINT `stock_sucursal_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `stock_sucursal_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
