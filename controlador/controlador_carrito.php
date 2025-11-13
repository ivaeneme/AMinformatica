<?php

class ControladorCarrito
{

    public function agregar()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controlador=carrito&accion=ver');
            exit;
        }

        $tipo = $_POST['tipo'] ?? 'producto';
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

        if ($cantidad <= 0) {
            echo "<script>
                fncSweetAlert('warning', 'La cantidad debe ser mayor a 0', null);
                window.history.back();
            </script>";
            return;
        }

        if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
        $totalItems = 0;
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $item) {
                $totalItems += $item['cantidad'];
            }
        }

        if ($totalItems >= 10) {
            echo "<script>
                fncSweetAlert('warning', 'No podés agregar más de 10 ítems en el carrito.', null);
                window.history.back();
            </script>";
            return;
        }

        $modeloCarrito = new ModeloCarrito();

        if ($tipo === 'producto') {
            $resultado = $modeloCarrito->agregarProductoAlCarrito($_SESSION['carrito'], $_POST['id'], $cantidad);
        } elseif ($tipo === 'servicio') {
            $resultado = $modeloCarrito->agregarServicioAlCarrito($_SESSION['carrito'], $_POST['id']);
        } else {
            echo "<script>
                fncSweetAlert('error', 'Tipo inválido', null);
                window.history.back();
            </script>";
            return;
        }

        if (isset($resultado['error'])) {
            $msg = addslashes($resultado['error']);
            echo "<script>
                fncSweetAlert('error', '{$msg}', null);
                window.history.back();
            </script>";
            return;
        }

        header('Location: index.php?controlador=carrito&accion=ver');
    }


    public function quitar()
    {
        $id = $_GET['id'];
        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
        }
        header('Location: index.php?controlador=carrito&accion=ver');
    }

    public function ver()
    {
        include 'vistas/modulo/vercarrito.php';
    }


    public function confirmar()
    {
        // ✅ Verificar login y contenido del carrito
        if (!isset($_SESSION['id_cliente']) || empty($_SESSION['carrito'])) {
            echo "<script>
                fncSweetAlert('warning', 'Debe estar logueado como cliente y tener productos en el carrito.', 'index.php');
            </script>";
            return;
        }

        $pdo = Conexion::conectar();
        $modeloProductos = new ModeloProductos();
        $modeloCarrito = new ModeloCarrito();
        $idCliente = $_SESSION['id_cliente'];

        // ✅ Validar que no tenga más de 2 presupuestos "Creados"
        $presupuestosActivos = $modeloCarrito->contarPresupuestosCreadosPorCliente($idCliente);
        if ($presupuestosActivos >= 2) {
            echo "<script>
                fncSweetAlert('warning', 'Ya tienes 2 presupuestos pendientes de aprobacion', 'index.php?controlador=carrito&accion=historial');
            </script>";
            return;
        }

        // ✅ Calcular total del carrito
        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        try {
            $pdo->beginTransaction();

            // 1️⃣ Insertar presupuesto principal
            $idPresupuesto = $modeloCarrito->mdlinsertarPresupuesto($idCliente, $total);

            // 2️⃣ Insertar los productos y servicios del carrito
            foreach ($_SESSION['carrito'] as $item) {
                $tipo = $item['tipo'];
                $idProducto = null;
                $estado_servicio = null;
                $marca = $tipo === 'producto' ? ($item['marca'] ?? 'Sin marca') : '-';

                if ($tipo === 'producto') {
                    // Producto físico
                    $idProducto = $modeloCarrito->insertarProductoPresupuesto(
                        $item['id'],
                        null,
                        $item['cantidad']
                    );
                } else {
                    // Servicio técnico
                    $estado_servicio = 1; // En proceso
                    $idProducto = $modeloCarrito->insertarProductoPresupuesto(
                        null,
                        $item['id'],
                        $item['cantidad'],
                        $estado_servicio
                    );
                }

                // 3️⃣ Insertar en lista de presupuesto
                $modeloCarrito->insertarEnListaPresupuesto(
                    $idProducto,
                    $item['nombre'],
                    $marca,
                    $item['precio'] * $item['cantidad'],
                    $idPresupuesto,
                    $item['cantidad']
                );
            }

            // 4️⃣ Confirmar transacción
            $pdo->commit();
            unset($_SESSION['carrito']);

            echo "<script>
                fncSweetAlert('success', 'Presupuesto generado correctamente.', 'index.php');
            </script>";
        } catch (Exception $e) {
            $pdo->rollBack();
            $msg = addslashes('Error al generar presupuesto: ' . $e->getMessage());
            echo "<script>
                fncSweetAlert('error', '{$msg}', 'index.php?controlador=carrito&accion=ver');
            </script>";
        }
    }




    public function historial()
    {
        $idCliente = $_SESSION['id_cliente'] ?? null;

        if (!$idCliente) {
            echo "<script>
                fncSweetAlert('warning', 'Debe iniciar sesión como cliente.', 'index.php');
            </script>";
            return;
        }

        $estado = $_GET['estado'] ?? null;
        if ($estado === '') {
            $estado = null; // Si selecciona "Todos", no filtrar por estado
        }

        $modeloCarrito = new ModeloCarrito();
        $presupuestos = $modeloCarrito->obtenerHistorialPresupuestosCliente($idCliente, $estado);
        $presupuestosActivos = $modeloCarrito->contarPresupuestosCreadosPorCliente($idCliente);

        include 'vistas/modulo/mis_presupuestos.php';
    }



    public function gestionar()
    {
        if (!isset($_SESSION["Rol_idRol"]) || !in_array($_SESSION["Rol_idRol"], [1, 4])) {
            echo "<script>
                fncSweetAlert('warning', 'Acceso restringido', 'index.php');
            </script>";
            return;
        }

        $modeloCarrito = new ModeloCarrito();

        // Recibir filtros
        $filtros = [
            'cliente' => $_GET['cliente'] ?? null,
            'mes' => $_GET['mes'] ?? null,
            'anio' => $_GET['anio'] ?? null,
            'estado' => $_GET['estado'] ?? null,
        ];

        $presupuestos = $modeloCarrito->obtenerPresupuestosParaGestion($filtros);
        $facturasMap = [];
        foreach ($presupuestos as $p) {
            $facturasMap[$p['idPresupuesto']] = $modeloCarrito->obtenerIdFacturaPorPresupuesto($p['idPresupuesto']);
        }

        include 'vistas/modulo/gestionar_presupuestos.php';
    }




    public function actualizarEstado()
    {
        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST['idPresupuesto']) &&
            isset($_POST['estado']) &&
            in_array($_SESSION["Rol_idRol"], [1, 4])
        ) {
            $idPresupuesto = (int)$_POST['idPresupuesto'];
            $nuevoEstado = (int)$_POST['estado'];

            $modeloCarrito = new ModeloCarrito();

            // Obtener el estado actual del presupuesto
            $presupuesto = $modeloCarrito->obtenerPresupuestoPorId($idPresupuesto);
            if (!$presupuesto) {
                echo "<script>
                    fncSweetAlert('error', 'Presupuesto no encontrado', 'index.php?controlador=carrito&accion=gestionar');
                </script>";
                return;
            }

            $estadoActual = (int)$presupuesto['estado_presupuesto'];

            // Transiciones válidas
            $transicionesValidas = [
                1 => [2, 6], // Creado → Aprobado o Cancelado
                2 => [3, 6], // Aprobado → En proceso o Cancelado
                3 => [4, 6], // En proceso → Terminado o Cancelado
                4 => [5],    // Terminado → Entregado
                5 => [],     // Entregado → No se puede cambiar
                6 => []      // Cancelado → No se puede cambiar
            ];

            if (!in_array($nuevoEstado, $transicionesValidas[$estadoActual] ?? [])) {
                echo "<script>
                    fncSweetAlert('warning', 'Transición de estado no permitida.', 'index.php?controlador=carrito&accion=gestionar');
                </script>";
                return;
            }

            // Validar servicios terminados para "Terminado" o "Entregado"
            if (in_array($nuevoEstado, [4, 5])) {
                $servicios = $modeloCarrito->obtenerServiciosPorPresupuesto($idPresupuesto);
                foreach ($servicios as $servicio) {
                    if ((int)$servicio['estado_servicio'] !== 2) {
                        $mensaje = $nuevoEstado === 4
                            ? 'No se puede marcar como \"Terminado\" hasta que todos los servicios estén terminados.'
                            : 'No se puede marcar como \"Entregado\" hasta que todos los servicios estén terminados.';
                        echo "<script>
                            fncSweetAlert('warning', '{$mensaje}', 'index.php?controlador=carrito&accion=gestionar');
                        </script>";
                        return;
                    }
                }
            }

            // Gestionar stock
            if ($nuevoEstado === 2) { // Aprobado
                $productosSinStock = $modeloCarrito->descontarStockPorPresupuesto($idPresupuesto);
                if ($productosSinStock === false) {
                    echo "<script>
                        fncSweetAlert('error', 'Error al procesar stock.', 'index.php?controlador=carrito&accion=gestionar');
                    </script>";
                    return;
                }
                if (!empty($productosSinStock)) {
                    $lista = addslashes(implode(", ", $productosSinStock));
                    echo "<script>
                        fncSweetAlert('warning', 'No hay stock suficiente para: {$lista}. No se puede aprobar el presupuesto.', 'index.php?controlador=carrito&accion=gestionar');
                    </script>";
                    return;
                }
            }

            // Solo devolver stock si antes se descontó (Aprobado, En proceso o Terminado)
            if ($nuevoEstado === 6 && in_array($estadoActual, [2, 3, 4])) {
                $productosDevueltos = $modeloCarrito->devolverStockPorPresupuesto($idPresupuesto);
                if ($productosDevueltos === false) {
                    echo "<script>
                        fncSweetAlert('error', 'Error al devolver stock.', 'index.php?controlador=carrito&accion=gestionar');
                    </script>";
                    return;
                }
            }

            // Actualizar estado finalmente
            $modeloCarrito->actualizarEstadoPresupuesto($idPresupuesto, $nuevoEstado);
            echo "<script>
                fncSweetAlert('success', 'Estado actualizado correctamente', 'index.php?controlador=carrito&accion=gestionar');
            </script>";
        } else {
            echo "<script>
                fncSweetAlert('error', 'Petición inválida', 'index.php');
            </script>";
        }
    }



    public function detalle()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<div class='alert alert-danger'>ID de presupuesto inválido</div>";
            return;
        }

        $idPresupuesto = (int)$_GET['id'];
        $modeloCarrito = new ModeloCarrito();

        // 1. Presupuesto + Cliente
        $presupuesto = $modeloCarrito->obtenerPresupuestoConCliente($idPresupuesto);
        if (!$presupuesto) {
            echo "<div class='alert alert-warning'>Presupuesto no encontrado.</div>";
            return;
        }

        // 2. Items (productos y/o servicios)
        $detalleItems = $modeloCarrito->obtenerItemsPresupuesto($idPresupuesto);

        // 3. ¿Tiene factura?
        $idFactura = $modeloCarrito->obtenerIdFacturaPorPresupuesto($idPresupuesto);
        $tieneFactura = $idFactura ? ['idFactura' => $idFactura] : false;

        include 'vistas/modulo/detalle_presupuesto.php';
    }

    public function tareasTecnico()
    {
        if (!isset($_SESSION["Rol_idRol"]) || $_SESSION["Rol_idRol"] != 3) {
            echo "<script>
                fncSweetAlert('warning', 'Acceso restringido', 'index.php');
            </script>";
            return;
        }

        $modeloCarrito = new ModeloCarrito();
        $tareas = $modeloCarrito->obtenerTareasTecnico();

        include 'vistas/modulo/servicios_pendientes.php';
    }


    public function actualizarEstadoServicio()
    {
        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST['idProducto'], $_POST['estado']) &&
            $_SESSION["Rol_idRol"] == 3
        ) {
            $idProducto = (int)$_POST['idProducto'];
            $estado = (int)$_POST['estado'];

            $modeloCarrito = new ModeloCarrito();
            $modeloCarrito->actualizarEstadoServicio($idProducto, $estado);

            echo '<script>fncSweetAlert("success", "Estado del servicio actualizado.", "index.php?controlador=carrito&accion=tareasTecnico");</script>';
        } else {
            echo '<script>fncSweetAlert("error", "Error al actualizar estado.", null);</script>';
        }
    }


    public function borrar()
    {
        if (!isset($_SESSION["Rol_idRol"]) || !in_array($_SESSION["Rol_idRol"], [1, 4])) {
            echo "<script>
            if (typeof fncSweetAlert === 'function') {
                fncSweetAlert('warning', 'Acceso restringido', 'index.php');
            } else {
                alert('Acceso restringido');
                window.location.href = 'index.php';
            }
        </script>";
            return;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<script>
            if (typeof fncSweetAlert === 'function') {
                fncSweetAlert('error', 'ID inválido', 'index.php?controlador=carrito&accion=gestionar');
            } else {
                alert('ID inválido');
                window.location.href = 'index.php?controlador=carrito&accion=gestionar';
            }
        </script>";
            return;
        }

        $idPresupuesto = (int)$_GET['id'];

        $modeloCarrito = new ModeloCarrito();
        $resultado = $modeloCarrito->borrarPresupuestoSiPermitido($idPresupuesto);

        if (isset($resultado['error'])) {
            $msg = addslashes($resultado['error']);
            echo "<script>
            if (typeof fncSweetAlert === 'function') {
                fncSweetAlert('error', '{$msg}', 'index.php?controlador=carrito&accion=gestionar');
            } else {
                alert('{$msg}');
                window.location.href = 'index.php?controlador=carrito&accion=gestionar';
            }
        </script>";
            return;
        }

        echo "<script>
        if (typeof fncSweetAlert === 'function') {
            fncSweetAlert('success', 'Presupuesto borrado correctamente', 'index.php?controlador=carrito&accion=gestionar');
        } else {
            alert('Presupuesto borrado correctamente');
            window.location.href = 'index.php?controlador=carrito&accion=gestionar';
        }
    </script>";
    }



    public function verPresupuesto()
    {
        $this->detalle();
    }

    public function agregarItem()
    {
        if (!isset($_GET['idPresupuesto']) || !is_numeric($_GET['idPresupuesto'])) {
            echo "<script>
                fncSweetAlert('error', 'ID de presupuesto inválido', 'index.php');
            </script>";
            return;
        }

        $idPresupuesto = (int)$_GET['idPresupuesto'];

        $modeloCarrito = new ModeloCarrito();

        // Validar que el presupuesto exista
        if (!$modeloCarrito->presupuestoExiste($idPresupuesto)) {
            echo "<script>
                fncSweetAlert('warning', 'Presupuesto no encontrado', 'index.php');
            </script>";
            return;
        }

        $modeloProductos = new ModeloProductos();
        $modeloServicios = new ModeloServicios();

        // Listar productos y servicios
        $productos = $modeloProductos->listarDisponibles();
        $servicios = $modeloServicios->listar();

        // 🔹 Filtrar servicios que ya existen en el presupuesto
        $serviciosExistentes = $modeloCarrito->obtenerIdServicioPorPresupuesto($idPresupuesto);
        $idsServiciosExistentes = array_column($serviciosExistentes, 'idServicio');

        $serviciosDisponibles = array_filter($servicios, function ($s) use ($idsServiciosExistentes) {
            return !in_array($s['idServicio'], $idsServiciosExistentes);
        });

        // Enviar a la vista
        include 'vistas/modulo/agregar_item.php';
    }




    public function guardarItem()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idPresupuesto = (int) $_POST['idPresupuesto'];
            $productosSeleccionados = $_POST['productosSeleccionados'] ?? [];
            $serviciosSeleccionados = $_POST['serviciosSeleccionados'] ?? [];
            $cantidades = $_POST['cantidad'] ?? [];

            if (empty($productosSeleccionados) && empty($serviciosSeleccionados)) {
                echo "<script>
                    fncSweetAlert('warning', 'Debe seleccionar al menos un producto o servicio', null);
                    window.history.back();
                </script>";
                return;
            }

            $modeloCarrito = new ModeloCarrito();
            $resultado = $modeloCarrito->guardarItemEnPresupuesto($idPresupuesto, $productosSeleccionados, $serviciosSeleccionados, $cantidades);

            if (isset($resultado['error'])) {
                
                $msg = addslashes($resultado['error']);
                echo "<script>
                    fncSweetAlert('error', '{$msg}', null);
                    window.history.back();
                </script>";
            } else {
                $url = "index.php?controlador=carrito&accion=verPresupuesto&id={$idPresupuesto}";
                echo "<script>
                    fncSweetAlert('success', 'Ítems agregados correctamente', '{$url}');
                </script>";
            }
        }
    }




    public function editarItem()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<script>
                fncSweetAlert('error', 'ID inválido', 'index.php');
            </script>";
            return;
        }

        $idItem = (int)$_GET['id'];
        $modeloCarrito = new ModeloCarrito();

        $item = $modeloCarrito->obtenerItemListaPorId($idItem);
        if (!$item) {
            echo "<script>
                fncSweetAlert('warning', 'Ítem no encontrado', 'index.php');
            </script>";
            return;
        }

        $producto = $modeloCarrito->obtenerProductoPorIdLista($item['Productos_idProductos']);
        if (!$producto) {
            echo "<script>
                fncSweetAlert('warning', 'Producto/servicio no encontrado', 'index.php');
            </script>";
            return;
        }

        $modeloProductos = new ModeloProductos();
        $modeloServicios = new ModeloServicios();

        $productos = $modeloProductos->listarDisponibles();
        $servicios = $modeloServicios->listar();

        include 'vistas/modulo/editar_item.php';
    }



    public function actualizarItem()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Petición inválida');
            }

            $idItem = (int)$_POST['idListaPresupuesto'];
            $idPresupuesto = (int)$_POST['idPresupuesto'];
            $cantidad = (int)$_POST['cantidad'];
            $idProducto = !empty($_POST['idProducto']) ? (int)$_POST['idProducto'] : null;
            $idServicio = !empty($_POST['idServicio']) ? (int)$_POST['idServicio'] : null;

            if (!$idProducto && !$idServicio) {
                throw new Exception('Debes seleccionar un producto o un servicio');
            }

            $modeloCarrito = new ModeloCarrito();
            $resultado = $modeloCarrito->actualizarItemEnPresupuesto(
                $idItem,
                $idPresupuesto,
                $idProducto,
                $idServicio,
                $cantidad
            );

            if (isset($resultado['error'])) {
                throw new Exception($resultado['error']);
            }

            $url = "index.php?controlador=carrito&accion=verPresupuesto&id={$idPresupuesto}";
            echo "<script>
                fncSweetAlert('success', 'Ítem actualizado correctamente', '{$url}');
            </script>";
        } catch (Throwable $e) {
            echo "<pre style='background:#111;color:#0f0;padding:15px;border-radius:10px'>
        <b>Error:</b> {$e->getMessage()}<br>
        <b>Archivo:</b> {$e->getFile()}<br>
        <b>Línea:</b> {$e->getLine()}
        </pre>";
        }
    }



    public function eliminarItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
            echo "<script>
                fncSweetAlert('error', 'Petición inválida', 'index.php');
            </script>";
            return;
        }

        $idLista = (int)$_POST['id'];

        $modeloCarrito = new ModeloCarrito();
        $resultado = $modeloCarrito->eliminarItemDelPresupuesto($idLista);

        if (isset($resultado['error'])) {
            $msg = addslashes($resultado['error']);
            echo "<script>
                fncSweetAlert('error', '{$msg}', 'index.php');
            </script>";
        } else {
            $idPresupuesto = $resultado['idPresupuesto'];
            $modeloCarrito->actualizarTotalPresupuesto($idPresupuesto);
            $url = "index.php?controlador=carrito&accion=verPresupuesto&id={$idPresupuesto}";
            echo "<script>
                fncSweetAlert('success', 'Ítem eliminado correctamente', '{$url}');
            </script>";
        }
        // $idPresupuesto = $resultado['idPresupuesto']; // ya no necesario
    }
}
