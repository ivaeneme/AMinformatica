-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250914.f72491a1c0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 19, 2025 at 07:22 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aminformatica`
--

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `idCliente` int NOT NULL,
  `nombre_cliente` varchar(45) DEFAULT NULL,
  `dni_cliente` varchar(45) DEFAULT NULL,
  `correo_cliente` varchar(45) DEFAULT NULL,
  `telefono_cliente` varchar(45) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `Usuario_idUsuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`idCliente`, `nombre_cliente`, `dni_cliente`, `correo_cliente`, `telefono_cliente`, `fecha_nacimiento`, `fecha_creacion`, `Usuario_idUsuario`) VALUES
(24, 'Gino Ravicini', '44333123', 'ravicha234@gmail.com', '3454134133', '1994-03-16', '2025-07-07 05:13:23', 16),
(25, 'maxi martinez', '38008887', 'ivancho.maxi1994@gmail.com', '3454331341', '0000-00-00', '0000-00-00 00:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `factura`
--

CREATE TABLE `factura` (
  `idFactura` int NOT NULL,
  `Presupuesto_idPresupuesto` int NOT NULL,
  `fechaEmision` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `Vendedor_idVendedor` int NOT NULL,
  `estado_factura` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listapresupuesto`
--

CREATE TABLE `listapresupuesto` (
  `idListaPresupuesto` int NOT NULL,
  `Productos_idProductos` int NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `marca` varchar(45) DEFAULT NULL,
  `modelo` varchar(45) DEFAULT NULL,
  `costoSubTotal` int NOT NULL,
  `idPresupuesto` int DEFAULT NULL,
  `cantidad` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `listapresupuesto`
--

INSERT INTO `listapresupuesto` (`idListaPresupuesto`, `Productos_idProductos`, `descripcion`, `marca`, `modelo`, `costoSubTotal`, `idPresupuesto`, `cantidad`) VALUES
(89, 95, 'Teclado Mecánico', 'Noga', '', 5000, 38, 1),
(90, 96, 'Mouse Gamer', 'Generico', 'ModeloX', 5000, 38, 2),
(91, 97, 'Mouse', 'Logitech', '', 5000, 38, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mercaderia`
--

CREATE TABLE `mercaderia` (
  `idMercaderia` int NOT NULL,
  `nombre_mercaderia` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `costo_mercaderia` decimal(10,2) DEFAULT '0.00',
  `idtipo_mercaderia` int DEFAULT NULL,
  `imagen_mercaderia` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `stock_mercaderia` int NOT NULL,
  `marca` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mercaderia`
--

INSERT INTO `mercaderia` (`idMercaderia`, `nombre_mercaderia`, `costo_mercaderia`, `idtipo_mercaderia`, `imagen_mercaderia`, `stock_mercaderia`, `marca`) VALUES
(1, 'Teclado Mecánico', 5000.00, 1, 'vistas\\assets\\img\\tecladomecanico.png', 11, 'Noga'),
(2, 'Mouse Gamer', 2500.00, 1, 'vistas\\assets\\img\\mousegamer.jpg', 11, 'Noga'),
(3, 'Tarjeta Gráfica RTX 3060', 150000.00, 2, 'vistas\\assets\\img\\RTX-3060.jpeg', 0, NULL),
(4, 'Procesador Intel i7', 120000.00, 2, 'vistas\\assets\\img\\procesador-intelR-core-i7.jpg', 0, NULL),
(5, 'Router TP-Link AC1200', 30000.00, 3, 'vistas\\assets\\img\\Router TP-Link AC1200.jpg', 0, NULL),
(6, 'Switch de Red 8 puertos', 15000.00, 3, 'vistas\\assets\\img\\SwitchdeRed8puertos.webp', 0, NULL),
(7, 'Windows 11 Pro', 20000.00, 4, 'vistas\\assets\\img\\Windows11Pro.jpg', 0, NULL),
(8, 'Antivirus Norton', 5000.00, 4, 'vistas\\assets\\img\\AntivirusNorton.webp', 0, NULL),
(9, 'Audífonos Bluetooth', 8000.00, 5, 'vistas\\assets\\img\\AudífonosBluetooth.jpg', 0, NULL),
(10, 'Cargador Universal', 3500.00, 5, 'vistas\\assets\\img\\CargadorUniversal.jpg', 0, NULL),
(17, 'Disco duro', 100000.00, 2, 'vistas\\assets\\img\\Discoduro.webp', 0, NULL),
(20, 'Mouse', 5000.00, 1, 'vistas\\assets\\img\\mouseoptico.webp', 11, 'Logitech');

-- --------------------------------------------------------

--
-- Table structure for table `presupuesto`
--

CREATE TABLE `presupuesto` (
  `idPresupuesto` int NOT NULL,
  `Cliente_idCliente` int NOT NULL,
  `ListaPresupuesto_idListaPresupuesto` int DEFAULT NULL,
  `costoTotal` int DEFAULT NULL,
  `estado_presupuesto` int NOT NULL,
  `fechaEmision` date DEFAULT NULL,
  `fecha_vencimiento` date NOT NULL,
  `tecnico_idtecnico` int NOT NULL,
  `tecnico_Usuario_idUsuario` int NOT NULL,
  `Vendedor_idVendedor` int NOT NULL,
  `Vendedor_Usuario_idUsuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `presupuesto`
--

INSERT INTO `presupuesto` (`idPresupuesto`, `Cliente_idCliente`, `ListaPresupuesto_idListaPresupuesto`, `costoTotal`, `estado_presupuesto`, `fechaEmision`, `fecha_vencimiento`, `tecnico_idtecnico`, `tecnico_Usuario_idUsuario`, `Vendedor_idVendedor`, `Vendedor_Usuario_idUsuario`) VALUES
(38, 24, NULL, 15000, 1, '2025-10-19', '2025-11-18', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `idProductos` int NOT NULL,
  `Mercaderia_idMercaderia` int DEFAULT NULL,
  `Servicio_idServicio` int DEFAULT NULL,
  `cantidad_productos` int DEFAULT NULL,
  `estado_servicio` tinyint DEFAULT NULL,
  `ListaPresupuesto_idListaPresupuesto` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`idProductos`, `Mercaderia_idMercaderia`, `Servicio_idServicio`, `cantidad_productos`, `estado_servicio`, `ListaPresupuesto_idListaPresupuesto`) VALUES
(77, 1, NULL, 1, NULL, NULL),
(78, 2, NULL, 3, NULL, NULL),
(79, 20, NULL, 3, NULL, NULL),
(80, NULL, 1, 1, 1, NULL),
(89, 1, NULL, 1, NULL, NULL),
(90, 2, NULL, 1, NULL, NULL),
(91, 20, NULL, 1, NULL, NULL),
(92, NULL, 5, 1, 1, NULL),
(94, NULL, 1, 1, 1, NULL),
(95, 1, NULL, 1, NULL, NULL),
(96, 2, NULL, 2, NULL, NULL),
(97, 20, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

CREATE TABLE `rol` (
  `idRol` int NOT NULL,
  `nombre_rol` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`idRol`, `nombre_rol`) VALUES
(1, 'vendedor'),
(2, 'cliente'),
(3, 'tecnico'),
(4, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `servicio`
--

  CREATE TABLE `servicio` (
    `idServicio` int NOT NULL,
    `nombre_servicio` varchar(45) DEFAULT NULL,
    `costo_servicio` int DEFAULT NULL,
    `tipo` varchar(45) DEFAULT NULL,
    `comentario` varchar(255) DEFAULT NULL,
    `imagen_servicio` varchar(255) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `servicio`
--

INSERT INTO `servicio` (`idServicio`, `nombre_servicio`, `costo_servicio`, `tipo`, `comentario`, `imagen_servicio`) VALUES
(1, 'Reparación de PC', 10000, 'Reparación</option><option value=', 'Reparación de hardware y software de computadoras', 'vistas\\assets\\img\\reparacion.webp'),
(2, 'Instalación de software', 5000, 'Instalación</option><option value=', 'Instalación y configuración de software en PC', 'vistas\\assets\\img\\instalacionsoftware.webp'),
(3, 'Inspección de equipos', 5000, 'Inspección</option><option value=', 'Diagnóstico de fallos en equipos informáticos', 'vistas\\assets\\img\\inspeccionvirus.webp'),
(4, 'Mantenimiento preventivo', 5000, 'Mantenimiento</option><option value=', 'Mantenimiento de computadoras para evitar fal', 'vistas\\assets\\img\\mantenimientopc.webp'),
(5, 'Recuperación de datos', 10000, 'Recuperación</option><option value=', 'Recuperación de archivos y datos de discos du', 'vistas\\assets\\img\\recuperaciondat0s.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tecnico`
--

CREATE TABLE `tecnico` (
  `idtecnico` int NOT NULL,
  `Usuario_idUsuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tecnico`
--

INSERT INTO `tecnico` (`idtecnico`, `Usuario_idUsuario`) VALUES
(1, 1),
(1, 9),
(2, 17);

-- --------------------------------------------------------

--
-- Table structure for table `tipomercaderia`
--

CREATE TABLE `tipomercaderia` (
  `idtipo_mercaderia` int NOT NULL,
  `nombre_tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tipomercaderia`
--

INSERT INTO `tipomercaderia` (`idtipo_mercaderia`, `nombre_tipo`) VALUES
(1, 'Periféricos'),
(2, 'Componentes de Hardware'),
(3, 'Equipos de Red'),
(4, 'Software'),
(5, 'Accesorios');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `Rol_idRol` int NOT NULL,
  `nombre_usuario` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `contrasena` varchar(211) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `Rol_idRol`, `nombre_usuario`, `email`, `contrasena`) VALUES
(1, 1, 'ivan', 'ivan.max.martinez94@gmail.com', '$2y$10$o5bwXALmIkkhEzN.kEkEyeyXtGX5WdrNJVN3Cfc/YvhOI4cxyfELu'),
(3, 2, 'maxi', 'ivancho.maxi1994@gmail.com', '$2y$10$yYVZQE.kLpFyjdtshhqrw.YKG1aDjpZv2IraPz2x7nbXwER4RVIGS'),
(7, 4, 'iv4nch0', 'maxi.ivan1994@gmail.com', '$2y$10$z.V/qFzR1wUh2XxcnnTc2eKknJi98TWjePZvWgTZQXTkwKJtN9kLa'),
(9, 3, 'papu', 'papu@gmail.com', '$2y$10$z.V/qFzR1wUh2XxcnnTc2eKknJi98TWjePZvWgTZQXTkwKJtN9kLa'),
(16, 2, 'Gino Ravicini', 'ravicha234@gmail.com', '$2y$10$L0psUr/zDBFEycU7aPCdHeB8yav7f6LMeT7SHzyHAb8BxIymyMCLG'),
(17, 3, 'Silvia', 'silviarios@gmail.com', '$2y$10$WDLrC8YVuD9r4g2xiM78dOvgYIGt4wKbFXqAMc/3ZWVTNpJKfqoOm');

-- --------------------------------------------------------

--
-- Table structure for table `vendedor`
--

CREATE TABLE `vendedor` (
  `idVendedor` int NOT NULL,
  `Usuario_idUsuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `vendedor`
--

INSERT INTO `vendedor` (`idVendedor`, `Usuario_idUsuario`) VALUES
(1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCliente`),
  ADD KEY `fk_cliente_usuario` (`Usuario_idUsuario`);

--
-- Indexes for table `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`idFactura`),
  ADD KEY `Presupuesto_idPresupuesto` (`Presupuesto_idPresupuesto`);

--
-- Indexes for table `listapresupuesto`
--
ALTER TABLE `listapresupuesto`
  ADD PRIMARY KEY (`idListaPresupuesto`),
  ADD KEY `fk_ListaPresupuesto_Productos1_idx` (`Productos_idProductos`),
  ADD KEY `fk_lista_presupuesto_presupuesto` (`idPresupuesto`);

--
-- Indexes for table `mercaderia`
--
ALTER TABLE `mercaderia`
  ADD PRIMARY KEY (`idMercaderia`),
  ADD KEY `fk_tipomercaderia` (`idtipo_mercaderia`);

--
-- Indexes for table `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD PRIMARY KEY (`idPresupuesto`),
  ADD KEY `fk_Presupuesto_ListaPresupuesto1_idx` (`ListaPresupuesto_idListaPresupuesto`),
  ADD KEY `fk_Presupuesto_Cliente1_idx` (`Cliente_idCliente`),
  ADD KEY `fk_Presupuesto_tecnico1_idx` (`tecnico_idtecnico`,`tecnico_Usuario_idUsuario`),
  ADD KEY `fk_Presupuesto_Vendedor1_idx` (`Vendedor_idVendedor`,`Vendedor_Usuario_idUsuario`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProductos`),
  ADD KEY `fk_Productos_Mercaderia1_idx` (`Mercaderia_idMercaderia`),
  ADD KEY `fk_Productos_Servicio1_idx` (`Servicio_idServicio`),
  ADD KEY `fk_productos_listapresupuesto` (`ListaPresupuesto_idListaPresupuesto`);

--
-- Indexes for table `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idRol`);

--
-- Indexes for table `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`idServicio`);

--
-- Indexes for table `tecnico`
--
ALTER TABLE `tecnico`
  ADD PRIMARY KEY (`idtecnico`,`Usuario_idUsuario`),
  ADD KEY `fk_tecnico_Usuario1_idx` (`Usuario_idUsuario`);

--
-- Indexes for table `tipomercaderia`
--
ALTER TABLE `tipomercaderia`
  ADD PRIMARY KEY (`idtipo_mercaderia`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_Usuario_Rol_idx` (`Rol_idRol`);

--
-- Indexes for table `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`idVendedor`,`Usuario_idUsuario`),
  ADD KEY `fk_Vendedor_Usuario1_idx` (`Usuario_idUsuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `factura`
--
ALTER TABLE `factura`
  MODIFY `idFactura` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `listapresupuesto`
--
ALTER TABLE `listapresupuesto`
  MODIFY `idListaPresupuesto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `mercaderia`
--
ALTER TABLE `mercaderia`
  MODIFY `idMercaderia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `presupuesto`
--
ALTER TABLE `presupuesto`
  MODIFY `idPresupuesto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `idProductos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `rol`
--
ALTER TABLE `rol`
  MODIFY `idRol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `servicio`
--
ALTER TABLE `servicio`
  MODIFY `idServicio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tecnico`
--
ALTER TABLE `tecnico`
  MODIFY `idtecnico` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tipomercaderia`
--
ALTER TABLE `tipomercaderia`
  MODIFY `idtipo_mercaderia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `idVendedor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_cliente_usuario` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`Presupuesto_idPresupuesto`) REFERENCES `presupuesto` (`idPresupuesto`);

--
-- Constraints for table `listapresupuesto`
--
ALTER TABLE `listapresupuesto`
  ADD CONSTRAINT `fk_lista_presupuesto_presupuesto` FOREIGN KEY (`idPresupuesto`) REFERENCES `presupuesto` (`idPresupuesto`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ListaPresupuesto_Productos1` FOREIGN KEY (`Productos_idProductos`) REFERENCES `productos` (`idProductos`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mercaderia`
--
ALTER TABLE `mercaderia`
  ADD CONSTRAINT `fk_tipomercaderia` FOREIGN KEY (`idtipo_mercaderia`) REFERENCES `tipomercaderia` (`idtipo_mercaderia`);

--
-- Constraints for table `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD CONSTRAINT `fk_Presupuesto_Cliente1` FOREIGN KEY (`Cliente_idCliente`) REFERENCES `clientes` (`idCliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Presupuesto_ListaPresupuesto1` FOREIGN KEY (`ListaPresupuesto_idListaPresupuesto`) REFERENCES `listapresupuesto` (`idListaPresupuesto`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Presupuesto_tecnico1` FOREIGN KEY (`tecnico_idtecnico`,`tecnico_Usuario_idUsuario`) REFERENCES `tecnico` (`idtecnico`, `Usuario_idUsuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Presupuesto_Vendedor1` FOREIGN KEY (`Vendedor_idVendedor`,`Vendedor_Usuario_idUsuario`) REFERENCES `vendedor` (`idVendedor`, `Usuario_idUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_listapresupuesto` FOREIGN KEY (`ListaPresupuesto_idListaPresupuesto`) REFERENCES `listapresupuesto` (`idListaPresupuesto`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_Productos_Mercaderia1` FOREIGN KEY (`Mercaderia_idMercaderia`) REFERENCES `mercaderia` (`idMercaderia`),
  ADD CONSTRAINT `fk_Productos_Servicio1` FOREIGN KEY (`Servicio_idServicio`) REFERENCES `servicio` (`idServicio`);

--
-- Constraints for table `tecnico`
--
ALTER TABLE `tecnico`
  ADD CONSTRAINT `fk_tecnico_Usuario1` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_Usuario_Rol` FOREIGN KEY (`Rol_idRol`) REFERENCES `rol` (`idRol`);

--
-- Constraints for table `vendedor`
--
ALTER TABLE `vendedor`
  ADD CONSTRAINT `fk_Vendedor_Usuario1` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
