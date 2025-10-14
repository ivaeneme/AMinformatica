<?php
class ModeloCarrito
{
    public function obtenerProductoPorId($id)
    {
        $modeloProductos = new ModeloProductos();
        return $modeloProductos->obtenerPorId($id);
    }

    public function verificarStockProducto($id, $cantidad)
    {
        $producto = $this->obtenerProductoPorId($id);
        if (!$producto) return false;
        return $producto['stock_mercaderia'] >= $cantidad;
    }

    public function agregarProductoAlCarrito(&$carrito, $id, $cantidad)
    {
        $producto = $this->obtenerProductoPorId($id);
        if (!$producto) return ['error' => 'Producto no encontrado'];

        $clave = "producto_" . $id;

        if (isset($carrito[$clave])) {
            $nuevaCantidad = $carrito[$clave]['cantidad'] + $cantidad;
            if ($producto['stock_mercaderia'] < $nuevaCantidad) {
                return ['error' => 'No se puede agregar más de lo disponible en stock'];
            }
            $carrito[$clave]['cantidad'] = $nuevaCantidad;
        } else {
            if ($producto['stock_mercaderia'] < $cantidad) {
                return ['error' => 'No hay suficiente stock disponible'];
            }
            $carrito[$clave] = [
                'id' => $producto['idMercaderia'],
                'nombre' => $producto['nombre_mercaderia'],
                'precio' => $producto['costo_mercaderia'],
                'cantidad' => $cantidad,
                'tipo' => 'producto'
            ];
        }

        return ['ok' => true];
    }

    public function obtenerServicioPorId($id)
    {
        $modeloServicios = new ModeloServicios();
        return $modeloServicios->obtenerPorId($id);
    }

    public function agregarServicioAlCarrito(&$carrito, $id)
    {
        $servicio = $this->obtenerServicioPorId($id);
        if (!$servicio) return ['error' => 'Servicio no encontrado'];

        $clave = "servicio_" . $id;

        if (isset($carrito[$clave])) {
            return ['error' => 'Este servicio ya fue agregado al carrito'];
        } else {
            $carrito[$clave] = [
                'id' => $servicio['idServicio'],
                'nombre' => $servicio['nombre_servicio'],
                'precio' => $servicio['costo_servicio'],
                'cantidad' => 1,
                'tipo' => 'servicio'
            ];
        }

        return ['ok' => true];
    }

    public function mdlinsertarPresupuesto($clienteId, $total)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO presupuesto 
            (Cliente_idCliente, ListaPresupuesto_idListaPresupuesto, costoTotal, estado_presupuesto, fechaEmision, fecha_vencimiento, tecnico_idtecnico, tecnico_Usuario_idUsuario, Vendedor_idVendedor, Vendedor_Usuario_idUsuario)
            VALUES (?, NULL, ?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 1, 1, 1, 1)");
        $stmt->execute([$clienteId, $total, 1]);
        return $pdo->lastInsertId();
    }

    public function insertarProductoPresupuesto($mercaderiaId, $servicioId, $cantidad)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO productos (Mercaderia_idMercaderia, Servicio_idServicio, cantidad_productos) VALUES (?, ?, ?)");
        $stmt->execute([$mercaderiaId, $servicioId, $cantidad]);
        return $pdo->lastInsertId();
    }

    public function insertarEnListaPresupuesto($idProducto, $descripcion, $marca, $modelo, $subtotal, $idPresupuesto, $cantidad)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO listapresupuesto 
            (Productos_idProductos, descripcion, marca, modelo, costoSubTotal, idPresupuesto, cantidad)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idProducto, $descripcion, $marca, $modelo, $subtotal, $idPresupuesto, $cantidad]);
        return $pdo->lastInsertId();
    }

    public function actualizarPresupuestoConLista($idPresupuesto, $idLista)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("UPDATE presupuesto SET ListaPresupuesto_idListaPresupuesto = ? WHERE idPresupuesto = ?");
        $stmt->execute([$idLista, $idPresupuesto]);
    }


    public function obtenerPresupuestoPorId($idPresupuesto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT estado_presupuesto FROM presupuesto WHERE idPresupuesto = ?");
        $stmt->execute([$idPresupuesto]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function obtenerHistorialPresupuestosCliente($idCliente, $estado = null)
    {
        $pdo = Conexion::conectar();

        $sql = "
        SELECT p.*, lp.descripcion, lp.marca, lp.modelo, lp.costoSubTotal, lp.cantidad
        FROM presupuesto p
        LEFT JOIN listapresupuesto lp ON lp.idPresupuesto = p.idPresupuesto
        WHERE p.Cliente_idCliente = ?
    ";

        $params = [$idCliente];

        if (!is_null($estado)) {
            $sql .= " AND p.estado_presupuesto = ?";
            $params[] = $estado;
        }

        $sql .= " ORDER BY p.fechaEmision DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPresupuestosParaGestion($filtros = [])
    {
        $pdo = Conexion::conectar();

        $where = [];
        $params = [];

        // Filtro por cliente
        if (!empty($filtros['cliente'])) {
            $where[] = "c.nombre_cliente LIKE :cliente";
            $params[':cliente'] = '%' . $filtros['cliente'] . '%';
        }

        // Filtro por mes
        if (!empty($filtros['mes'])) {
            $where[] = "MONTH(p.fechaEmision) = :mes";
            $params[':mes'] = $filtros['mes'];
        }

        // Filtro por año
        if (!empty($filtros['anio'])) {
            $where[] = "YEAR(p.fechaEmision) = :anio";
            $params[':anio'] = $filtros['anio'];
        }

        // Filtro por estado
        if (!empty($filtros['estado'])) {
            $where[] = "p.estado_presupuesto = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        $sql = "
        SELECT p.idPresupuesto, p.fechaEmision, p.costoTotal, p.estado_presupuesto,
               c.nombre_cliente, lp.descripcion, lp.marca, lp.modelo, lp.costoSubTotal, lp.cantidad,
               pr.estado_servicio
        FROM presupuesto p
        LEFT JOIN clientes c ON c.idCliente = p.Cliente_idCliente
        LEFT JOIN listapresupuesto lp ON lp.idPresupuesto = p.idPresupuesto
        LEFT JOIN productos pr ON pr.idProductos = lp.Productos_idProductos
    ";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY p.fechaEmision DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }





    public function obtenerServiciosPorPresupuesto($idPresupuesto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("
        SELECT p.idProductos, p.estado_servicio
        FROM productos p
        INNER JOIN listapresupuesto lp ON lp.Productos_idProductos = p.idProductos
        WHERE lp.idPresupuesto = ?
          AND p.Servicio_idServicio IS NOT NULL
    ");
        $stmt->execute([$idPresupuesto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstadoPresupuesto($idPresupuesto, $nuevoEstado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("UPDATE presupuesto SET estado_presupuesto = ? WHERE idPresupuesto = ?");
        return $stmt->execute([$nuevoEstado, $idPresupuesto]);
    }

    public function obtenerPresupuestoConCliente($idPresupuesto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("
        SELECT p.idPresupuesto, p.fechaEmision, p.estado_presupuesto, p.costoTotal,
               c.nombre_cliente
        FROM presupuesto p
        INNER JOIN clientes c ON p.Cliente_idCliente = c.idCliente
        WHERE p.idPresupuesto = ?
    ");
        $stmt->execute([$idPresupuesto]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerItemsPresupuesto($idPresupuesto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("
        SELECT lp.idListaPresupuesto, lp.descripcion, lp.marca, lp.modelo, lp.cantidad, lp.costoSubTotal
        FROM listapresupuesto lp
        WHERE lp.idPresupuesto = ?
    ");
        $stmt->execute([$idPresupuesto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerIdFacturaPorPresupuesto($idPresupuesto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT idFactura FROM factura WHERE Presupuesto_idPresupuesto = ?");
        $stmt->execute([$idPresupuesto]);
        return $stmt->fetchColumn(); // Devuelve null si no existe
    }

    public function obtenerTareasTecnico()
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("
        SELECT p.idPresupuesto, c.nombre_cliente, pr.idProductos, pr.estado_servicio,
               lp.descripcion, lp.marca, lp.modelo, lp.cantidad
        FROM productos pr
        INNER JOIN listapresupuesto lp ON lp.Productos_idProductos = pr.idProductos
        INNER JOIN presupuesto p ON lp.idPresupuesto = p.idPresupuesto
        INNER JOIN clientes c ON p.Cliente_idCliente = c.idCliente
        WHERE pr.Servicio_idServicio IS NOT NULL
        ORDER BY p.fechaEmision DESC
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function actualizarEstadoServicio($idProducto, $estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("UPDATE productos SET estado_servicio = ? WHERE idProductos = ?");
        return $stmt->execute([$estado, $idProducto]);
    }

    public function borrarPresupuestoSiPermitido($idPresupuesto)
    {
        $pdo = Conexion::conectar();

        // Verificar estado del presupuesto antes de borrar
        $stmt = $pdo->prepare("SELECT estado_presupuesto FROM presupuesto WHERE idPresupuesto = ?");
        $stmt->execute([$idPresupuesto]);
        $estado = $stmt->fetchColumn();

        if (!$estado) {
            return ['error' => 'Presupuesto no encontrado'];
        }

        // Permitir borrar solo si está Cancelado(6) o Entregado(5)
        if (!in_array((int)$estado, [5, 6])) {
            return ['error' => 'No se puede borrar un presupuesto que no esté Cancelado o Entregado'];
        }

        // Opcional: Podrías borrar antes las dependencias en otras tablas (listapresupuesto, productos, factura) si existen

        try {
            $pdo->beginTransaction();

            // Borrar lista de presupuesto
            $stmtDelLista = $pdo->prepare("DELETE FROM listapresupuesto WHERE idPresupuesto = ?");
            $stmtDelLista->execute([$idPresupuesto]);

            // Borrar productos asociados (según estructura, puede que productos no tengan FK ON DELETE CASCADE)
            $stmtDelProd = $pdo->prepare("
            DELETE p FROM productos p
            INNER JOIN listapresupuesto lp ON lp.Productos_idProductos = p.idProductos
            WHERE lp.idPresupuesto = ?
        ");
            $stmtDelProd->execute([$idPresupuesto]);

            // Borrar factura asociada (si existe)
            $stmtDelFactura = $pdo->prepare("DELETE FROM factura WHERE Presupuesto_idPresupuesto = ?");
            $stmtDelFactura->execute([$idPresupuesto]);

            // Finalmente borrar el presupuesto
            $stmtDelPresupuesto = $pdo->prepare("DELETE FROM presupuesto WHERE idPresupuesto = ?");
            $stmtDelPresupuesto->execute([$idPresupuesto]);

            $pdo->commit();

            return ['ok' => true];
        } catch (Exception $e) {
            $pdo->rollBack();
            return ['error' => 'Error al borrar presupuesto: ' . $e->getMessage()];
        }
    }

    public function actualizarTotalPresupuesto($idPresupuesto)
    {
        $pdo = Conexion::conectar();

        try {
            // Calcular total sumando los subtotales de listaPresupuesto
            $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(costoSubTotal), 0) AS total 
            FROM listapresupuesto 
            WHERE idPresupuesto = ?
        ");
            $stmt->execute([$idPresupuesto]);
            $total = $stmt->fetchColumn();

            // Actualizar total en presupuesto
            $stmt2 = $pdo->prepare("
            UPDATE presupuesto 
            SET costoTotal = ? 
            WHERE idPresupuesto = ?
        ");
            $stmt2->execute([$total, $idPresupuesto]);

            return ['ok' => true];
        } catch (Exception $e) {
            return ['error' => 'Error al actualizar total: ' . $e->getMessage()];
        }
    }


    //ABM para los detalles del presupuesto//

    public function presupuestoExiste($idPresupuesto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM presupuesto WHERE idPresupuesto = ?");
        $stmt->execute([$idPresupuesto]);
        return $stmt->fetchColumn() > 0;
    }

    public function guardarItemEnPresupuesto($idPresupuesto, $productosSeleccionados, $serviciosSeleccionados, $cantidades)
    {
        $pdo = Conexion::conectar();

        try {
            // 1️⃣ SELECT masivo para productos
            $mercaderias = [];
            if (!empty($productosSeleccionados)) {
                $ids = implode(',', array_map('intval', $productosSeleccionados));
                $stmt = $pdo->query("SELECT * FROM mercaderia WHERE idMercaderia IN ($ids)");
                $mercaderias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $mercaderias = array_column($mercaderias, null, 'idMercaderia'); // índice por id
            }

            // 2️⃣ SELECT masivo para servicios
            $servicios = [];
            if (!empty($serviciosSeleccionados)) {
                $ids = implode(',', array_map('intval', $serviciosSeleccionados));
                $stmt = $pdo->query("SELECT * FROM servicio WHERE idServicio IN ($ids)");
                $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $servicios = array_column($servicios, null, 'idServicio'); // índice por id
            }

            // 3️⃣ Iniciar una sola transacción
            $pdo->beginTransaction();

            foreach ($productosSeleccionados as $idProducto) {
                if (!isset($mercaderias[$idProducto])) {
                    $pdo->rollBack();
                    return ['error' => "Producto ID $idProducto no encontrado"];
                }

                $merc = $mercaderias[$idProducto];
                $cantidad = isset($cantidades[$idProducto]) ? (int)$cantidades[$idProducto] : 1;

                if ($merc['stock_mercaderia'] < $cantidad) {
                    $pdo->rollBack();
                    return ['error' => "No hay suficiente stock del producto {$merc['nombre_mercaderia']}"];
                }

                // Insert en tabla productos
                $stmt = $pdo->prepare("INSERT INTO productos (Mercaderia_idMercaderia, cantidad_productos) VALUES (?, ?)");
                $stmt->execute([$idProducto, $cantidad]);
                $idProd = $pdo->lastInsertId();

                // Insert en lista de presupuesto
                $stmt = $pdo->prepare("INSERT INTO listapresupuesto 
                (Productos_idProductos, descripcion, marca, modelo, costoSubTotal, idPresupuesto, cantidad)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
                $subtotal = $merc['costo_mercaderia'] * $cantidad;
                $stmt->execute([$idProd, $merc['nombre_mercaderia'], $merc['marca'], '', $subtotal, $idPresupuesto, $cantidad]);
            }

            foreach ($serviciosSeleccionados as $idServicio) {
                if (!isset($servicios[$idServicio])) {
                    $pdo->rollBack();
                    return ['error' => "Servicio ID $idServicio no encontrado"];
                }

                $serv = $servicios[$idServicio];

                // Insert en tabla productos
                $stmt = $pdo->prepare("INSERT INTO productos (Servicio_idServicio, cantidad_productos, estado_servicio) VALUES (?, ?, 1)");
                $stmt->execute([$idServicio, 1]);
                $idProd = $pdo->lastInsertId();

                // Insert en lista de presupuesto
                $stmt = $pdo->prepare("INSERT INTO listapresupuesto 
                (Productos_idProductos, descripcion, marca, modelo, costoSubTotal, idPresupuesto, cantidad)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$idProd, $serv['nombre_servicio'], '', '', $serv['costo_servicio'], $idPresupuesto, 1]);
            }

            // 4️⃣ Recalcular total **una sola vez**
            $this->actualizarTotalPresupuesto($idPresupuesto);

            $pdo->commit();

            return ['ok' => true];
        } catch (Exception $e) {
            $pdo->rollBack();
            return ['error' => 'Error al guardar ítems: ' . $e->getMessage()];
        }
    }



    public function obtenerItemListaPorId($idListaPresupuesto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT * FROM listapresupuesto WHERE idListaPresupuesto = ?");
        $stmt->execute([$idListaPresupuesto]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerProductoPorIdLista($idProducto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE idProductos = ?");
        $stmt->execute([$idProducto]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarItemEnPresupuesto($idItem, $idPresupuesto, $idProductoSeleccionado, $idServicioSeleccionado, $cantidad)
    {
        try {
            $pdo = Conexion::conectar();

            // Obtener el producto actual
            $stmt = $pdo->prepare("SELECT Productos_idProductos FROM listapresupuesto WHERE idListaPresupuesto = ?");
            $stmt->execute([$idItem]);
            $idProductoActual = $stmt->fetchColumn();

            if (!$idProductoActual) {
                return ['error' => 'Ítem no encontrado para actualizar'];
            }

            // Obtener datos actuales del producto
            $stmt = $pdo->prepare("SELECT * FROM productos WHERE idProductos = ?");
            $stmt->execute([$idProductoActual]);
            $productoActual = $stmt->fetch(PDO::FETCH_ASSOC);

            $nuevoMercaderiaId = null;
            $nuevoServicioId = null;
            $estadoServicio = null;

            if ($idProductoSeleccionado) {
                $nuevoMercaderiaId = $idProductoSeleccionado;
                $estadoServicio = null;
            } elseif ($idServicioSeleccionado) {
                $nuevoServicioId = $idServicioSeleccionado;
                $estadoServicio = 1;
            }

            // Actualizar producto
            $stmt = $pdo->prepare("
            UPDATE productos SET 
                Mercaderia_idMercaderia = :mercaderia,
                Servicio_idServicio = :servicio,
                cantidad_productos = :cantidad,
                estado_servicio = :estado
            WHERE idProductos = :id
        ");
            $stmt->bindParam(':mercaderia', $nuevoMercaderiaId);
            $stmt->bindParam(':servicio', $nuevoServicioId);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':estado', $estadoServicio);
            $stmt->bindParam(':id', $idProductoActual);

            if (!$stmt->execute()) {
                return ['error' => 'Error al actualizar producto/servicio'];
            }

            // Calcular nuevos datos para listapresupuesto
            if ($nuevoMercaderiaId) {
                $stmt = $pdo->prepare("SELECT nombre_mercaderia, marca, costo_mercaderia FROM mercaderia WHERE idMercaderia = ?");
                $stmt->execute([$nuevoMercaderiaId]);
                $datos = $stmt->fetch(PDO::FETCH_ASSOC);

                $descripcion = $datos['nombre_mercaderia'];
                $marca = $datos['marca'];
                $modelo = '';
                $subtotal = $datos['costo_mercaderia'] * $cantidad;
            } else {
                $stmt = $pdo->prepare("SELECT nombre_servicio, costo_servicio FROM servicio WHERE idServicio = ?");
                $stmt->execute([$nuevoServicioId]);
                $datos = $stmt->fetch(PDO::FETCH_ASSOC);

                $descripcion = $datos['nombre_servicio'];
                $marca = '';
                $modelo = '';
                $subtotal = $datos['costo_servicio'] * $cantidad;
            }

            // Actualizar listapresupuesto
            $stmt = $pdo->prepare("
            UPDATE listapresupuesto 
            SET descripcion = ?, marca = ?, modelo = ?, costoSubTotal = ?, cantidad = ? 
            WHERE idListaPresupuesto = ?
        ");
            $stmt->execute([$descripcion, $marca, $modelo, $subtotal, $cantidad, $idItem]);

            // Actualizar total del presupuesto
            $this->actualizarTotalPresupuesto($idPresupuesto);

            return ['ok' => true];
        } catch (Exception $e) {
            return ['error' => 'Error inesperado: ' . $e->getMessage()];
        }
    }

    public function eliminarItemDelPresupuesto($idListaPresupuesto)
    {
        try {
            $pdo = Conexion::conectar();

            // 1. Obtener idPresupuesto e idProducto
            $stmt = $pdo->prepare("
            SELECT idPresupuesto, Productos_idProductos 
            FROM listapresupuesto 
            WHERE idListaPresupuesto = ?
        ");
            $stmt->execute([$idListaPresupuesto]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$fila) {
                return ['error' => 'Ítem no encontrado'];
            }

            $idPresupuesto = $fila['idPresupuesto'];
            $idProducto = $fila['Productos_idProductos'];

            // 2. Iniciar transacción
            $pdo->beginTransaction();

            // 3. Eliminar de listapresupuesto
            $stmt = $pdo->prepare("DELETE FROM listapresupuesto WHERE idListaPresupuesto = ?");
            $stmt->execute([$idListaPresupuesto]);

            // 4. Eliminar de productos
            $stmt = $pdo->prepare("DELETE FROM productos WHERE idProductos = ?");
            $stmt->execute([$idProducto]);

            // 5. Actualizar total del presupuesto
            $this->actualizarTotalPresupuesto($idPresupuesto);

            $pdo->commit();

            return ['ok' => true, 'idPresupuesto' => $idPresupuesto];
        } catch (Exception $e) {
            $pdo->rollBack();
            return ['error' => 'Error al eliminar el ítem: ' . $e->getMessage()];
        }
    }
}
