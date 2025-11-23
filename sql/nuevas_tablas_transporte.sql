-- Nuevas tablas para módulo de transporte

CREATE TABLE IF NOT EXISTS `camiones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `patente` VARCHAR(20) NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `km_actual` INT(11) DEFAULT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_hora_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_ultima_modificacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patente` (`patente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `vueltas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_camion` INT(11) NOT NULL,
  `id_chofer` INT(11) NOT NULL,
  `fecha_salida` DATE NOT NULL,
  `km_salida` INT(11) NOT NULL,
  `fecha_cierre` DATE DEFAULT NULL,
  `km_cierre` INT(11) DEFAULT NULL,
  `observaciones` TEXT DEFAULT NULL,
  `estado` ENUM('abierta','cerrada','liquidada') NOT NULL DEFAULT 'abierta',
  `anulado` TINYINT(1) NOT NULL DEFAULT 0,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_hora_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_ultima_modificacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `vueltas_anticipos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_vuelta` INT(11) NOT NULL,
  `fecha` DATE NOT NULL,
  `forma_pago` ENUM('efectivo','transferencia','cheque') NOT NULL,
  `importe` DECIMAL(12,2) NOT NULL,
  `observaciones` VARCHAR(255) DEFAULT NULL,
  `anulado` TINYINT(1) NOT NULL DEFAULT 0,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_hora_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_ultima_modificacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `viajes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_vuelta` INT(11) NOT NULL,
  `id_destino` INT(11) NOT NULL,
  `fecha` DATE NOT NULL,
  `origen` VARCHAR(120) DEFAULT NULL,
  `destino` VARCHAR(120) DEFAULT NULL,
  `flete_total` DECIMAL(12,2) NOT NULL,
  `observaciones` VARCHAR(255) DEFAULT NULL,
  `estado` ENUM('ok','borrador') NOT NULL DEFAULT 'ok',
  `anulado` TINYINT(1) NOT NULL DEFAULT 0,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_hora_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_ultima_modificacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `viajes_cobros` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_viaje` INT(11) NOT NULL,
  `fecha` DATE NOT NULL,
  `forma_pago` ENUM('efectivo','transferencia','cheque') NOT NULL,
  `importe` DECIMAL(12,2) NOT NULL,
  `referencia` VARCHAR(120) DEFAULT NULL,
  `anulado` TINYINT(1) NOT NULL DEFAULT 0,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_hora_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_ultima_modificacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `viajes_gastos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_viaje` INT(11) NOT NULL,
  `fecha` DATE NOT NULL,
  `tipo` ENUM('peaje','gasoil','otros') NOT NULL,
  `importe` DECIMAL(12,2) NOT NULL,
  `detalle` VARCHAR(255) DEFAULT NULL,
  `anulado` TINYINT(1) NOT NULL DEFAULT 0,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_hora_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_ultima_modificacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `liquidaciones_choferes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_vuelta` INT(11) NOT NULL,
  `fecha` DATE NOT NULL,
  `honorarios` DECIMAL(12,2) NOT NULL,
  `anticipos` DECIMAL(12,2) NOT NULL,
  `cobros_chofer` DECIMAL(12,2) NOT NULL,
  `gastos` DECIMAL(12,2) NOT NULL,
  `saldo_caja_chofer` DECIMAL(12,2) NOT NULL,
  `entregado_a_empresa_en_efectivo` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `monto_a_pagar` DECIMAL(12,2) NOT NULL,
  `resultado` ENUM('a_pagar','a_cobrar') NOT NULL,
  `anulado` TINYINT(1) NOT NULL DEFAULT 0,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_hora_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_ultima_modificacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_vuelta` (`id_vuelta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `liquidaciones_choferes_pagos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_liquidacion` INT(11) NOT NULL,
  `fecha` DATE NOT NULL,
  `forma_pago` ENUM('efectivo','transferencia','cheque') NOT NULL,
  `importe` DECIMAL(12,2) NOT NULL,
  `observaciones` VARCHAR(255) DEFAULT NULL,
  `anulado` TINYINT(1) NOT NULL DEFAULT 0,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_hora_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_ultima_modificacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Índices para claves foráneas
ALTER TABLE `vueltas`
  ADD KEY `id_camion` (`id_camion`),
  ADD KEY `id_chofer` (`id_chofer`),
  ADD KEY `id_usuario` (`id_usuario`);

ALTER TABLE `camiones`
  ADD KEY `id_usuario` (`id_usuario`);

ALTER TABLE `vueltas_anticipos`
  ADD KEY `id_vuelta` (`id_vuelta`),
  ADD KEY `id_usuario` (`id_usuario`);

ALTER TABLE `viajes`
  ADD KEY `id_vuelta` (`id_vuelta`),
  ADD KEY `id_destino` (`id_destino`),
  ADD KEY `id_usuario` (`id_usuario`);

ALTER TABLE `viajes_cobros`
  ADD KEY `id_viaje` (`id_viaje`),
  ADD KEY `id_usuario` (`id_usuario`);

ALTER TABLE `viajes_gastos`
  ADD KEY `id_viaje` (`id_viaje`),
  ADD KEY `id_usuario` (`id_usuario`);

ALTER TABLE `liquidaciones_choferes`
  ADD KEY `id_usuario` (`id_usuario`);

ALTER TABLE `liquidaciones_choferes_pagos`
  ADD KEY `id_liquidacion` (`id_liquidacion`),
  ADD KEY `id_usuario` (`id_usuario`);

-- Restricciones de clave foránea
ALTER TABLE `camiones`
  ADD CONSTRAINT `camiones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;

ALTER TABLE `vueltas`
  ADD CONSTRAINT `vueltas_ibfk_1` FOREIGN KEY (`id_camion`) REFERENCES `camiones` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `vueltas_ibfk_2` FOREIGN KEY (`id_chofer`) REFERENCES `choferes` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `vueltas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;

ALTER TABLE `vueltas_anticipos`
  ADD CONSTRAINT `vueltas_anticipos_ibfk_1` FOREIGN KEY (`id_vuelta`) REFERENCES `vueltas` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `vueltas_anticipos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;

ALTER TABLE `viajes`
  ADD CONSTRAINT `viajes_ibfk_1` FOREIGN KEY (`id_vuelta`) REFERENCES `vueltas` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `viajes_ibfk_2` FOREIGN KEY (`id_destino`) REFERENCES `destinos` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `viajes_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;

ALTER TABLE `viajes_cobros`
  ADD CONSTRAINT `viajes_cobros_ibfk_1` FOREIGN KEY (`id_viaje`) REFERENCES `viajes` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `viajes_cobros_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;

ALTER TABLE `viajes_gastos`
  ADD CONSTRAINT `viajes_gastos_ibfk_1` FOREIGN KEY (`id_viaje`) REFERENCES `viajes` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `viajes_gastos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;

ALTER TABLE `liquidaciones_choferes`
  ADD CONSTRAINT `liquidaciones_choferes_ibfk_1` FOREIGN KEY (`id_vuelta`) REFERENCES `vueltas` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `liquidaciones_choferes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;

ALTER TABLE `liquidaciones_choferes_pagos`
  ADD CONSTRAINT `liquidaciones_choferes_pagos_ibfk_1` FOREIGN KEY (`id_liquidacion`) REFERENCES `liquidaciones_choferes` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `liquidaciones_choferes_pagos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;
