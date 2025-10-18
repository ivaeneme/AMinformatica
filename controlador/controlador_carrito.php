<?php
class ControladorCarrito
{
    // public function index()
    // {
    //     $modelo = new ModeloProductos();
    //     $productos = $modelo->listarDisponibles(); // Método para obtener productos con stock > 0
    //     include 'vistas/carrito/tienda.php';
    // }

    public function agregar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controlador=carrito&accion=ver');
            exit;
        }

        $tipo = $_POST['tipo'] ?? 'producto';
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

        if ($cantidad <= 0) {
            echo "<script>alert('La cantidad debe ser mayor a 0'); window.history.back();</script>";
            return;
        }

        if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

        $modeloCarrito = new ModeloCarrito();

        if ($tipo === 'producto') {
            $resultado = $modeloCarrito->agregarProductoAlCarrito($_SESSION['carrito'], $_POST['id'], $cantidad);
        } elseif ($tipo === 'servicio') {
            $resultado = $modeloCarrito->agregarServicioAlCarrito($_SESSION['carrito'], $_POST['id']);
        } else {
            echo "<script>alert('Tipo inválido'); window.history.back();</script>";
            return;
        }

        if (isset($resultado['error'])) {
            echo "<script>alert('" . $resultado['error'] . "'); window.history.back();</script>";
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
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if (!isset($_SESSION['id_cliente']) || empty($_SESSION['carrito'])) {
            echo "<script>alert('Debe estar logueado como cliente y tener productos en el carrito.'); window.location.href='index.php';</script>";
            return;
        }

        $pdo = Conexion::conectar();
        $modeloProductos = new ModeloProductos();
        $modeloCarrito = new ModeloCarrito();

        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        try {
            $pdo->beginTransaction();

            // 1. Insertar presupuesto
            $idPresupuesto = $modeloCarrito->mdlinsertarPresupuesto($_SESSION['id_cliente'], $total);

            foreach ($_SESSION['carrito'] as $item) {
                $tipo = $item['tipo'];
                $idProducto = null;

                if ($tipo === 'producto') {

                    // Insertar producto con servicio_id = NULL
                    $idProducto = $modeloCarrito->insertarProductoPresupuesto($item['id'], null, $item['cantidad']);
                } else {
                    // Insertar servicio con mercaderia_id = NULL
                    $idProducto = $modeloCarrito->insertarProductoPresupuesto(null, $item['id'], $item['cantidad']);
                }

                // Insertar en listapresupuesto
                $idListaPresupuesto = $modeloCarrito->insertarEnListaPresupuesto(
                    $idProducto,
                    $item['nombre'],
                    'Generico',
                    'ModeloX',
                    $item['precio'] * $item['cantidad'],
                    $idPresupuesto,
                    $item['cantidad']
                );

                // Actualizar presupuesto con idListaPresupuesto (por ahora el último)
                $modeloCarrito->actualizarPresupuestoConLista($idPresupuesto, $idListaPresupuesto);
            }

            $pdo->commit();
            unset($_SESSION['carrito']);
            echo "<script>alert('Presupuesto generado correctamente'); window.location.href='index.php';</script>";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Error al generar presupuesto: " . $e->getMessage() . "'); window.location.href='index.php?controlador=carrito&accion=ver';</script>";
        }
    }



    public function historial()
    {
        $idCliente = $_SESSION['id_cliente'] ?? null;

        if (!$idCliente) {
            echo "<script>alert('Debe iniciar sesión como cliente.'); window.location.href='index.php';</script>";
            return;
        }

        $estado = $_GET['estado'] ?? null;
        if ($estado === '') {
            $estado = null; // Si selecciona "Todos", no filtrar por estado
        }


        $modeloCarrito = new ModeloCarrito();
        $presupuestos = $modeloCarrito->obtenerHistorialPresupuestosCliente($idCliente, $estado);

        include 'vistas/modulo/mis_presupuestos.php';
    }



    public function gestionar()
    {
        if (!isset($_SESSION["Rol_idRol"]) || !in_array($_SESSION["Rol_idRol"], [1, 4])) {
            echo "<script>alert('Acceso restringido'); window.location.href='index.php';</script>";
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
                echo "<script>alert('Presupuesto no encontrado'); window.location.href='index.php?controlador=carrito&accion=gestionar';</script>";
                return;
            }

            $estadoActual = (int)$presupuesto['estado_presupuesto'];

            // Definir las transiciones válidas
            $transicionesValidas = [
                1 => [2, 6], // Creado → Aprobado o Cancelado
                2 => [3, 6], // Aprobado → En proceso o Cancelado
                3 => [4, 6], // En proceso → Terminado o Cancelado
                4 => [5],    // Terminado → Entregado
                5 => [],     // Entregado → No se puede cambiar
                6 => []      // Cancelado → No se puede cambiar
            ];

            // Validar si el nuevo estado es una transición permitida
            if (!in_array($nuevoEstado, $transicionesValidas[$estadoActual] ?? [])) {
                echo "<script>
                alert('Transición de estado no permitida.'); 
                window.location.href='index.php?controlador=carrito&accion=gestionar';
            </script>";
                return;
            }

            // Validar servicios terminados para "Terminado" o "Entregado"
            if (in_array($nuevoEstado, [4, 5])) {
                $servicios = $modeloCarrito->obtenerServiciosPorPresupuesto($idPresupuesto);

                foreach ($servicios as $servicio) {
                    if ((int)$servicio['estado_servicio'] !== 2) {
                        $mensaje = $nuevoEstado === 4
                            ? 'No se puede marcar como "Terminado" hasta que todos los servicios estén terminados.'
                            : 'No se puede marcar como "Entregado" hasta que todos los servicios estén terminados.';

                        echo "<script>
                            alert('$mensaje'); 
                            window.location.href='index.php?controlador=carrito&accion=gestionar';
                        </script>";
                        return;
                    }
                }
            }
            // 🚀 NUEVO BLOQUE: descontar stock si el presupuesto pasa a "Aprobado"
            if ($nuevoEstado === 2) {
                $productosSinStock = $modeloCarrito->descontarStockPorPresupuesto($idPresupuesto);

                if ($productosSinStock === false) {
                    echo "<script>
                    alert('Error al procesar stock.');
                    window.location.href='index.php?controlador=carrito&accion=gestionar';
                </script>";
                    return;
                }

                if (!empty($productosSinStock)) {
                    $lista = implode(", ", $productosSinStock);
                    echo "<script>
                        alert('No hay stock suficiente para: $lista. No se puede aprobar el presupuesto.');
                        window.location.href='index.php?controlador=carrito&accion=gestionar';
                    </script>";
                    return; // Evita continuar si no hay stock
                }
            }

            // Actualizar estado si pasa todas las validaciones
            $modeloCarrito->actualizarEstadoPresupuesto($idPresupuesto, $nuevoEstado);
            echo "<script>
                alert('Estado actualizado correctamente');
                window.location.href='index.php?controlador=carrito&accion=gestionar';
            </script>";
        } else {
            echo "<script>
            alert('Petición inválida');
            window.location.href='index.php';
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
            echo "<script>alert('Acceso restringido'); window.location.href='index.php';</script>";
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

            echo "<script>alert('Estado del servicio actualizado'); window.location.href='index.php?controlador=carrito&accion=tareasTecnico';</script>";
        } else {
            echo "<script>alert('Petición inválida'); window.location.href='index.php';</script>";
        }
    }

    
    public function borrar()
    {
        if (!isset($_SESSION["Rol_idRol"]) || !in_array($_SESSION["Rol_idRol"], [1, 4])) {
            echo "<script>alert('Acceso restringido'); window.location.href='index.php';</script>";
            return;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<script>alert('ID inválido'); window.location.href='index.php?controlador=carrito&accion=gestionar';</script>";
            return;
        }

        $idPresupuesto = (int)$_GET['id'];

        $modeloCarrito = new ModeloCarrito();
        $resultado = $modeloCarrito->borrarPresupuestoSiPermitido($idPresupuesto);

        if (isset($resultado['error'])) {
            echo "<script>alert('" . $resultado['error'] . "'); window.location.href='index.php?controlador=carrito&accion=gestionar';</script>";
            return;
        }

        echo "<script>alert('Presupuesto borrado correctamente'); window.location.href='index.php?controlador=carrito&accion=gestionar';</script>";
    }


    public function verPresupuesto()
    {
        $this->detalle();
    }

    public function agregarItem()
    {
        if (!isset($_GET['idPresupuesto']) || !is_numeric($_GET['idPresupuesto'])) {
            echo "<script>alert('ID de presupuesto inválido'); window.location.href='index.php';</script>";
            return;
        }

        $idPresupuesto = (int)$_GET['idPresupuesto'];

        $modeloCarrito = new ModeloCarrito();
        // Validar que el presupuesto exista
        if (!$modeloCarrito->presupuestoExiste($idPresupuesto)) {
            echo "<script>alert('Presupuesto no encontrado'); window.location.href='index.php';</script>";
            return;
        }

        $modeloProductos = new ModeloProductos();
        $modeloServicios = new ModeloServicios();

        $productos = $modeloProductos->listarDisponibles();
        $servicios = $modeloServicios->listar();

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
                echo "<script>alert('Debe seleccionar al menos un producto o servicio'); window.history.back();</script>";
                return;
            }

            $modeloCarrito = new ModeloCarrito();
            $resultado = $modeloCarrito->guardarItemEnPresupuesto($idPresupuesto, $productosSeleccionados, $serviciosSeleccionados, $cantidades);

            if (isset($resultado['error'])) {
                echo "<script>alert('{$resultado['error']}'); window.history.back();</script>";
            } else {
                echo "<script>alert('Ítems agregados correctamente'); 
                  window.location.href='index.php?controlador=carrito&accion=verPresupuesto&id={$idPresupuesto}';</script>";
            }
        }
    }




    public function editarItem()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<script>alert('ID inválido'); window.location.href='index.php';</script>";
            return;
        }

        $idItem = (int)$_GET['id'];
        $modeloCarrito = new ModeloCarrito();

        $item = $modeloCarrito->obtenerItemListaPorId($idItem);
        if (!$item) {
            echo "<script>alert('Ítem no encontrado'); window.location.href='index.php';</script>";
            return;
        }

        $producto = $modeloCarrito->obtenerProductoPorIdLista($item['Productos_idProductos']);
        if (!$producto) {
            echo "<script>alert('Producto/servicio no encontrado'); window.location.href='index.php';</script>";
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "<script>alert('Petición inválida'); window.location.href='index.php';</script>";
            return;
        }

        $idItem = (int)$_POST['idListaPresupuesto'];
        $idPresupuesto = (int)$_POST['idPresupuesto'];
        $cantidad = (int)$_POST['cantidad'];
        $idProducto = !empty($_POST['idProducto']) ? (int)$_POST['idProducto'] : null;
        $idServicio = !empty($_POST['idServicio']) ? (int)$_POST['idServicio'] : null;

        if (!$idProducto && !$idServicio) {
            echo "<script>alert('Debes seleccionar un producto o un servicio'); window.history.back();</script>";
            return;
        }

        $modeloCarrito = new ModeloCarrito();
        $resultado = $modeloCarrito->actualizarItemEnPresupuesto($idItem, $idPresupuesto, $idProducto, $idServicio, $cantidad);

        if (isset($resultado['error'])) {
            echo "<script>alert('{$resultado['error']}'); window.history.back();</script>";
            return;
        }
        echo "<script>alert('Ítem actualizado correctamente'); 
        window.location.href='index.php?controlador=carrito&accion=verPresupuesto&id={$idPresupuesto}';</script>";
    }


    public function eliminarItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
            echo "<script>alert('Petición inválida'); window.location.href='index.php';</script>";
            return;
        }

        $idLista = (int)$_POST['id'];

        $modeloCarrito = new ModeloCarrito();
        $resultado = $modeloCarrito->eliminarItemDelPresupuesto($idLista);

        if (isset($resultado['error'])) {
            echo "<script>alert('{$resultado['error']}'); window.location.href='index.php';</script>";
        } else {
            $idPresupuesto = $resultado['idPresupuesto'];
            $modeloCarrito->actualizarTotalPresupuesto($idPresupuesto);
            echo "<script>alert('Ítem eliminado correctamente'); window.location.href='index.php?controlador=carrito&accion=verPresupuesto&id={$idPresupuesto}';</script>";
        }
        $idPresupuesto = $resultado['idPresupuesto'];
    }


}
