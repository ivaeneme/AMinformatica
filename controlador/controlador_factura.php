<?php

class ControladorFactura
{
    public function generarFactura()
    {
        // Solo vendedor o admin
        if (!in_array($_SESSION["Rol_idRol"], [1, 4])) {
            echo "<script>
                fncSweetAlert('error', 'Acceso restringido', 'index.php');
            </script>";
            return;
        }

        // Validar ID de presupuesto
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<script>
                fncSweetAlert('error', 'ID de presupuesto inválido', 'index.php?controlador=carrito&accion=gestionar');
            </script>";
            return;
        }

        $idPresupuesto = (int)$_GET['id'];
        $pdo = Conexion::conectar();

        // 1) Verificar que el presupuesto exista y esté terminado
        $stmt = $pdo->prepare("
            SELECT * 
            FROM presupuesto 
            WHERE idPresupuesto = ? 
              AND estado_presupuesto = 4
        ");
        $stmt->execute([$idPresupuesto]);
        $presupuesto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$presupuesto) {
            echo "<script>
                fncSweetAlert('error', 'Presupuesto no encontrado o no está terminado', 'index.php?controlador=carrito&accion=gestionar');
            </script>";
            return;
        }

        // 2) Verificar que no exista factura ya para ese presupuesto
        $stmt2 = $pdo->prepare("SELECT 1 FROM factura WHERE Presupuesto_idPresupuesto = ?");
        $stmt2->execute([$idPresupuesto]);
        if ($stmt2->fetch()) {
            echo "<script>
                fncSweetAlert('warning', 'Ya existe una factura para este presupuesto', 'index.php?controlador=carrito&accion=gestionar');
            </script>";
            return;
        }

        // 3) Insertar factura
        $stmtInsert = $pdo->prepare("
            INSERT INTO factura (Presupuesto_idPresupuesto, fechaEmision, total, Vendedor_idVendedor)
            VALUES (?, CURDATE(), ?, ?)
        ");
        $stmtInsert->execute([
            $idPresupuesto,
            $presupuesto['costoTotal'],
            $_SESSION['id_vendedor'] ?? 1
        ]);

        $idFactura = $pdo->lastInsertId();

        // 4) Obtener datos completos de factura + cliente
        $stmtF = $pdo->prepare("
            SELECT f.*,
                   c.nombre_cliente,
                   c.dni_cliente,
                   c.correo_cliente,
                   p.idPresupuesto
            FROM factura f
            INNER JOIN presupuesto p ON f.Presupuesto_idPresupuesto = p.idPresupuesto
            INNER JOIN clientes c ON p.Cliente_idCliente = c.idCliente
            WHERE f.idFactura = ?
        ");
        $stmtF->execute([$idFactura]);
        $factura = $stmtF->fetch(PDO::FETCH_ASSOC);

        // 5) Obtener items del presupuesto
        $modeloCarrito = new ModeloCarrito();
        $items = $modeloCarrito->obtenerItemsPresupuesto($factura['Presupuesto_idPresupuesto']);

        if (!$items || !is_array($items)) {
            $items = []; // por las dudas no romper el foreach del helper
        }

        // 6) Enviar la factura por correo
        require_once "utils/correo_helper.php";
        $enviado = CorreoHelper::enviarFactura($factura, $items);

        // 7) Mensaje de salida
        $urlVer = "index.php?controlador=factura&accion=ver&id=" . $idFactura;

        if ($enviado) {
            echo "<script>
                fncSweetAlert(
                    'success',
                    'Factura generada y enviada correctamente al cliente.',
                    '$urlVer'
                );
            </script>";
        } else {
            echo "<script>
                fncSweetAlert(
                    'warning',
                    'Factura generada, pero no se pudo enviar el correo.',
                    '$urlVer'
                );
            </script>";
        }
    }



    public function ver()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<script>
                fncSweetAlert('error', 'ID inválido', 'index.php?controlador=carrito&accion=gestionar');
            </script>";
            return;
        }

        $idFactura = (int)$_GET['id'];
        $pdo = Conexion::conectar();

        $stmt = $pdo->prepare("
            SELECT f.*, c.nombre_cliente, c.dni_cliente, c.correo_cliente
            FROM factura f
            INNER JOIN presupuesto p ON f.Presupuesto_idPresupuesto = p.idPresupuesto
            INNER JOIN clientes c ON p.Cliente_idCliente = c.idCliente
            WHERE f.idFactura = ?
        ");
        $stmt->execute([$idFactura]);
        $factura = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$factura) {
            echo "<script>
                fncSweetAlert('error', 'Factura no encontrada', 'index.php?controlador=carrito&accion=gestionar');
            </script>";
            return;
        }

        $modeloCarrito = new ModeloCarrito();
        $items = $modeloCarrito->obtenerItemsPresupuesto($factura['Presupuesto_idPresupuesto']);
        include 'vistas/modulo/ver_factura.php';
    }


    public function enviarEmailFactura()
    {
        // Solo vendedor o admin
        if (!in_array($_SESSION["Rol_idRol"], [1, 4])) {
            echo "<script>
                fncSweetAlert('error', 'Acceso restringido', 'index.php');
            </script>";
            return;
        }

        // Validar ID
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<script>
                fncSweetAlert('error', 'ID de factura inválido', 'index.php?controlador=carrito&accion=gestionar');
            </script>";
            return;
        }

        $idFactura = (int)$_GET['id'];
        $pdo = Conexion::conectar();

        // 1) Traer FACTURA + PRESUPUESTO + CLIENTE (incluyendo dni y correo)
        $stmt = $pdo->prepare("
            SELECT f.*,
                   c.nombre_cliente,
                   c.dni_cliente,
                   c.correo_cliente,
                   p.idPresupuesto
            FROM factura f
            INNER JOIN presupuesto p ON f.Presupuesto_idPresupuesto = p.idPresupuesto
            INNER JOIN clientes c ON p.Cliente_idCliente = c.idCliente
            WHERE f.idFactura = ?
        ");
        $stmt->execute([$idFactura]);
        $factura = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$factura) {
            echo "<script>
                fncSweetAlert('error', 'Factura no encontrada', 'index.php?controlador=carrito&accion=gestionar');
            </script>";
            return;
        }

        // 2) Traer ITEMS del presupuesto asociado
        $modeloCarrito = new ModeloCarrito();
        $items = $modeloCarrito->obtenerItemsPresupuesto($factura['Presupuesto_idPresupuesto']);

        if (!$items || !is_array($items)) {
            // Por las dudas, para no romper el foreach del helper
            $items = [];
        }

        // 3) Enviar correo
        require_once "utils/correo_helper.php";
        $enviado = CorreoHelper::enviarFactura($factura, $items);

        // 4) Mensaje al usuario
        if ($enviado) {
            echo "<script>
                fncSweetAlert(
                    'success',
                    'Factura enviada correctamente al correo del cliente.',
                    'index.php?controlador=factura&accion=ver&id={$idFactura}'
                );
            </script>";
        } else {
            echo "<script>
                fncSweetAlert(
                    'error',
                    'No se pudo enviar la factura.',
                    'index.php?controlador=factura&accion=ver&id={$idFactura}'
                );
            </script>";
        }
    }
}
