-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-07-2025 a las 03:22:49
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
-- Base de datos: `aminformatica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idCliente` int(11) NOT NULL,
  `nombre_cliente` varchar(45) DEFAULT NULL,
  `dni_cliente` varchar(45) DEFAULT NULL,
  `correo_cliente` varchar(45) DEFAULT NULL,
  `telefono_cliente` varchar(45) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `Usuario_idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idCliente`, `nombre_cliente`, `dni_cliente`, `correo_cliente`, `telefono_cliente`, `fecha_nacimiento`, `fecha_creacion`, `Usuario_idUsuario`) VALUES
(24, 'Gino Ravicini', '44333123', 'ravicha234@gmail.com', '3454134133', '1994-03-16', '2025-07-07 05:13:23', 16),
(25, 'maxi martinez', '38008887', 'ivancho.maxi1994@gmail.com', '3454331341', '0000-00-00', '0000-00-00 00:00:00', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `idFactura` int(11) NOT NULL,
  `Presupuesto_idPresupuesto` int(11) NOT NULL,
  `fechaEmision` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `Vendedor_idVendedor` int(11) NOT NULL,
  `estado_factura` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listapresupuesto`
--

CREATE TABLE `listapresupuesto` (
  `idListaPresupuesto` int(11) NOT NULL,
  `Productos_idProductos` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `marca` varchar(45) DEFAULT NULL,
  `modelo` varchar(45) DEFAULT NULL,
  `costoSubTotal` int(11) NOT NULL,
  `idPresupuesto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `listapresupuesto`
--

INSERT INTO `listapresupuesto` (`idListaPresupuesto`, `Productos_idProductos`, `descripcion`, `marca`, `modelo`, `costoSubTotal`, `idPresupuesto`, `cantidad`) VALUES
(2, 2, 'Teclado Mecánico', 'Generico', 'ModeloX', 10000, 6, 2),
(8, 8, 'Teclado Mecánico', 'Noga', '', 5000, 14, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mercaderia`
--

CREATE TABLE `mercaderia` (
  `idMercaderia` int(11) NOT NULL,
  `nombre_mercaderia` varchar(45) DEFAULT NULL,
  `costo_mercaderia` decimal(10,2) DEFAULT 0.00,
  `idtipo_mercaderia` int(11) DEFAULT NULL,
  `imagen_mercaderia` varchar(255) NOT NULL,
  `stock_mercaderia` int(11) NOT NULL,
  `marca` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mercaderia`
--

INSERT INTO `mercaderia` (`idMercaderia`, `nombre_mercaderia`, `costo_mercaderia`, `idtipo_mercaderia`, `imagen_mercaderia`, `stock_mercaderia`, `marca`) VALUES
(1, 'Teclado Mecánico', 5000.00, 1, 'vistas\\assets\\img\\tecladomecanico.png', 11, 'Noga'),
(2, 'Mouse Gamer', 2500.00, 1, 'vistas\\assets\\img\\mousegamer.jpg', 0, NULL),
(3, 'Tarjeta Gráfica RTX 3060', 150000.00, 2, 'vistas\\assets\\img\\RTX-3060.jpeg', 0, NULL),
(4, 'Procesador Intel i7', 120000.00, 2, 'vistas\\assets\\img\\procesador-intelR-core-i7.jpg', 0, NULL),
(5, 'Router TP-Link AC1200', 30000.00, 3, 'vistas\\assets\\img\\Router TP-Link AC1200.jpg', 0, NULL),
(6, 'Switch de Red 8 puertos', 15000.00, 3, 'vistas\\assets\\img\\SwitchdeRed8puertos.webp', 0, NULL),
(7, 'Windows 11 Pro', 20000.00, 4, 'vistas\\assets\\img\\Windows11Pro.jpg', 0, NULL),
(8, 'Antivirus Norton', 5000.00, 4, 'vistas\\assets\\img\\AntivirusNorton.webp', 0, NULL),
(9, 'Audífonos Bluetooth', 8000.00, 5, 'vistas\\assets\\img\\AudífonosBluetooth.jpg', 0, NULL),
(10, 'Cargador Universal', 3500.00, 5, 'vistas\\assets\\img\\CargadorUniversal.jpg', 0, NULL),
(17, 'Disco duro', 100000.00, 2, 'vistas\\assets\\img\\Discoduro.webp', 0, NULL),
(20, 'Mouse', 5000.00, 1, 'vistas\\assets\\img\\mouseoptico.webp', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto`
--

CREATE TABLE `presupuesto` (
  `idPresupuesto` int(11) NOT NULL,
  `Cliente_idCliente` int(11) NOT NULL,
  `ListaPresupuesto_idListaPresupuesto` int(11) DEFAULT NULL,
  `costoTotal` int(11) DEFAULT NULL,
  `estado_presupuesto` int(11) NOT NULL,
  `fechaEmision` date DEFAULT NULL,
  `fecha_vencimiento` date NOT NULL,
  `tecnico_idtecnico` int(11) NOT NULL,
  `tecnico_Usuario_idUsuario` int(11) NOT NULL,
  `Vendedor_idVendedor` int(11) NOT NULL,
  `Vendedor_Usuario_idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `presupuesto`
--

INSERT INTO `presupuesto` (`idPresupuesto`, `Cliente_idCliente`, `ListaPresupuesto_idListaPresupuesto`, `costoTotal`, `estado_presupuesto`, `fechaEmision`, `fecha_vencimiento`, `tecnico_idtecnico`, `tecnico_Usuario_idUsuario`, `Vendedor_idVendedor`, `Vendedor_Usuario_idUsuario`) VALUES
(6, 25, 2, 10000, 5, '2025-07-09', '2025-08-08', 1, 1, 1, 1),
(14, 25, NULL, 5000, 5, '2025-07-13', '2025-08-12', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProductos` int(11) NOT NULL,
  `Mercaderia_idMercaderia` int(11) DEFAULT NULL,
  `Servicio_idServicio` int(11) DEFAULT NULL,
  `cantidad_productos` int(11) DEFAULT NULL,
  `estado_servicio` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProductos`, `Mercaderia_idMercaderia`, `Servicio_idServicio`, `cantidad_productos`, `estado_servicio`) VALUES
(2, 1, 1, 2, 2),
(3, NULL, 1, 1, 2),
(4, 1, NULL, 1, NULL),
(5, NULL, 1, 1, 1),
(6, 1, NULL, 1, NULL),
(8, 1, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idRol` int(11) NOT NULL,
  `nombre_rol` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idRol`, `nombre_rol`) VALUES
(1, 'vendedor'),
(2, 'cliente'),
(3, 'tecnico'),
(4, 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `idServicio` int(11) NOT NULL,
  `nombre_servicio` varchar(45) DEFAULT NULL,
  `costo_servicio` int(11) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `imagen_servicio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`idServicio`, `nombre_servicio`, `costo_servicio`, `tipo`, `comentario`, `imagen_servicio`) VALUES
(1, 'Reparación de PC', 50, 'Reparación', 'Reparación de hardware y software de computadoras', 'vistas\\assets\\img\\reparacion.webp'),
(2, 'Instalación de software', 30, 'Instalación', 'Instalación y configuración de software en PC', 'vistas\\assets\\img\\instalacionsoftware.webp'),
(3, 'Inspección de equipos', 20, 'Inspección', 'Diagnóstico de fallos en equipos informáticos', 'vistas\\assets\\img\\inspeccionvirus.webp'),
(4, 'Mantenimiento preventivo', 40, 'Mantenimiento', 'Mantenimiento de computadoras para evitar fal', 'vistas\\assets\\img\\mantenimientopc.webp'),
(5, 'Recuperación de datos', 100, 'Recuperación', 'Recuperación de archivos y datos de discos du', 'vistas\\assets\\img\\recuperaciondat0s.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnico`
--

CREATE TABLE `tecnico` (
  `idtecnico` int(11) NOT NULL,
  `Usuario_idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tecnico`
--

INSERT INTO `tecnico` (`idtecnico`, `Usuario_idUsuario`) VALUES
(1, 1),
(1, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomercaderia`
--

CREATE TABLE `tipomercaderia` (
  `idtipo_mercaderia` int(11) NOT NULL,
  `nombre_tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipomercaderia`
--

INSERT INTO `tipomercaderia` (`idtipo_mercaderia`, `nombre_tipo`) VALUES
(1, 'Periféricos'),
(2, 'Componentes de Hardware'),
(3, 'Equipos de Red'),
(4, 'Software'),
(5, 'Accesorios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `Rol_idRol` int(11) NOT NULL,
  `nombre_usuario` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `contrasena` varchar(211) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `Rol_idRol`, `nombre_usuario`, `email`, `contrasena`) VALUES
(1, 1, 'ivan', 'ivan.max.martinez94@gmail.com', '$2y$10$o5bwXALmIkkhEzN.kEkEyeyXtGX5WdrNJVN3Cfc/YvhOI4cxyfELu'),
(3, 2, 'maxi', 'ivancho.maxi1994@gmail.com', '$2y$10$yYVZQE.kLpFyjdtshhqrw.YKG1aDjpZv2IraPz2x7nbXwER4RVIGS'),
(7, 4, 'iv4nch0', 'maxi.ivan1994@gmail.com', '$2y$10$z.V/qFzR1wUh2XxcnnTc2eKknJi98TWjePZvWgTZQXTkwKJtN9kLa'),
(9, 3, 'papu', 'papu@gmail.com', '$2y$10$z.V/qFzR1wUh2XxcnnTc2eKknJi98TWjePZvWgTZQXTkwKJtN9kLa'),
(16, 2, 'Gino Ravicini', 'ravicha234@gmail.com', '$2y$10$L0psUr/zDBFEycU7aPCdHeB8yav7f6LMeT7SHzyHAb8BxIymyMCLG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedor`
--

CREATE TABLE `vendedor` (
  `idVendedor` int(11) NOT NULL,
  `Usuario_idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `vendedor`
--

INSERT INTO `vendedor` (`idVendedor`, `Usuario_idUsuario`) VALUES
(1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCliente`),
  ADD KEY `fk_cliente_usuario` (`Usuario_idUsuario`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`idFactura`),
  ADD KEY `Presupuesto_idPresupuesto` (`Presupuesto_idPresupuesto`);

--
-- Indices de la tabla `listapresupuesto`
--
ALTER TABLE `listapresupuesto`
  ADD PRIMARY KEY (`idListaPresupuesto`),
  ADD KEY `fk_ListaPresupuesto_Productos1_idx` (`Productos_idProductos`),
  ADD KEY `fk_lista_presupuesto_presupuesto` (`idPresupuesto`);

--
-- Indices de la tabla `mercaderia`
--
ALTER TABLE `mercaderia`
  ADD PRIMARY KEY (`idMercaderia`),
  ADD KEY `fk_tipomercaderia` (`idtipo_mercaderia`);

--
-- Indices de la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD PRIMARY KEY (`idPresupuesto`),
  ADD KEY `fk_Presupuesto_ListaPresupuesto1_idx` (`ListaPresupuesto_idListaPresupuesto`),
  ADD KEY `fk_Presupuesto_Cliente1_idx` (`Cliente_idCliente`),
  ADD KEY `fk_Presupuesto_tecnico1_idx` (`tecnico_idtecnico`,`tecnico_Usuario_idUsuario`),
  ADD KEY `fk_Presupuesto_Vendedor1_idx` (`Vendedor_idVendedor`,`Vendedor_Usuario_idUsuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProductos`),
  ADD KEY `fk_Productos_Mercaderia1_idx` (`Mercaderia_idMercaderia`),
  ADD KEY `fk_Productos_Servicio1_idx` (`Servicio_idServicio`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idRol`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`idServicio`);

--
-- Indices de la tabla `tecnico`
--
ALTER TABLE `tecnico`
  ADD PRIMARY KEY (`idtecnico`,`Usuario_idUsuario`),
  ADD KEY `fk_tecnico_Usuario1_idx` (`Usuario_idUsuario`);

--
-- Indices de la tabla `tipomercaderia`
--
ALTER TABLE `tipomercaderia`
  ADD PRIMARY KEY (`idtipo_mercaderia`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_Usuario_Rol_idx` (`Rol_idRol`);

--
-- Indices de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`idVendedor`,`Usuario_idUsuario`),
  ADD KEY `fk_Vendedor_Usuario1_idx` (`Usuario_idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `idFactura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `listapresupuesto`
--
ALTER TABLE `listapresupuesto`
  MODIFY `idListaPresupuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `mercaderia`
--
ALTER TABLE `mercaderia`
  MODIFY `idMercaderia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  MODIFY `idPresupuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProductos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `idServicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tecnico`
--
ALTER TABLE `tecnico`
  MODIFY `idtecnico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipomercaderia`
--
ALTER TABLE `tipomercaderia`
  MODIFY `idtipo_mercaderia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `idVendedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_cliente_usuario` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`Presupuesto_idPresupuesto`) REFERENCES `presupuesto` (`idPresupuesto`);

--
-- Filtros para la tabla `listapresupuesto`
--
ALTER TABLE `listapresupuesto`
  ADD CONSTRAINT `fk_ListaPresupuesto_Productos1` FOREIGN KEY (`Productos_idProductos`) REFERENCES `productos` (`idProductos`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lista_presupuesto_presupuesto` FOREIGN KEY (`idPresupuesto`) REFERENCES `presupuesto` (`idPresupuesto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mercaderia`
--
ALTER TABLE `mercaderia`
  ADD CONSTRAINT `fk_tipomercaderia` FOREIGN KEY (`idtipo_mercaderia`) REFERENCES `tipomercaderia` (`idtipo_mercaderia`);

--
-- Filtros para la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD CONSTRAINT `fk_Presupuesto_Cliente1` FOREIGN KEY (`Cliente_idCliente`) REFERENCES `clientes` (`idCliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Presupuesto_ListaPresupuesto1` FOREIGN KEY (`ListaPresupuesto_idListaPresupuesto`) REFERENCES `listapresupuesto` (`idListaPresupuesto`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Presupuesto_Vendedor1` FOREIGN KEY (`Vendedor_idVendedor`,`Vendedor_Usuario_idUsuario`) REFERENCES `vendedor` (`idVendedor`, `Usuario_idUsuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Presupuesto_tecnico1` FOREIGN KEY (`tecnico_idtecnico`,`tecnico_Usuario_idUsuario`) REFERENCES `tecnico` (`idtecnico`, `Usuario_idUsuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_Productos_Mercaderia1` FOREIGN KEY (`Mercaderia_idMercaderia`) REFERENCES `mercaderia` (`idMercaderia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Productos_Servicio1` FOREIGN KEY (`Servicio_idServicio`) REFERENCES `servicio` (`idServicio`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tecnico`
--
ALTER TABLE `tecnico`
  ADD CONSTRAINT `fk_tecnico_Usuario1` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_Usuario_Rol` FOREIGN KEY (`Rol_idRol`) REFERENCES `rol` (`idRol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD CONSTRAINT `fk_Vendedor_Usuario1` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
