<?php
class ControladorFactura
{
    public function generarFactura()
    {
        if (!in_array($_SESSION["Rol_idRol"], [1, 4])) {
            echo "<script>alert('Acceso restringido'); window.location.href='index.php';</script>";
            return;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<script>alert('ID de presupuesto inválido'); window.location.href='index.php?controlador=carrito&accion=gestionar';</script>";
            return;
        }

        $idPresupuesto = (int)$_GET['id'];

        $pdo = Conexion::conectar();

        // Verificar que el presupuesto exista y esté terminado
        $stmt = $pdo->prepare("SELECT * FROM presupuesto WHERE idPresupuesto = ? AND estado_presupuesto = 4");
        $stmt->execute([$idPresupuesto]);
        $presupuesto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$presupuesto) {
            echo "<script>alert('Presupuesto no encontrado o no está terminado'); window.location.href='index.php?controlador=carrito&accion=gestionar';</script>";
            return;
        }

        // Verificar que no exista factura ya para ese presupuesto
        $stmt2 = $pdo->prepare("SELECT * FROM factura WHERE Presupuesto_idPresupuesto = ?");
        $stmt2->execute([$idPresupuesto]);
        if ($stmt2->fetch()) {
            echo "<script>alert('Ya existe una factura para este presupuesto'); window.location.href='index.php?controlador=carrito&accion=gestionar';</script>";
            return;
        }

        // Insertar factura
        $stmtInsert = $pdo->prepare("INSERT INTO factura (Presupuesto_idPresupuesto, fechaEmision, total, Vendedor_idVendedor) VALUES (?, CURDATE(), ?, ?)");
        $stmtInsert->execute([$idPresupuesto, $presupuesto['costoTotal'], $_SESSION['id_vendedor'] ?? 1]);

        echo "<script>alert('Factura generada correctamente'); window.location.href='index.php?controlador=factura&accion=ver&id={$pdo->lastInsertId()}';</script>";
    }
    public function ver()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<script>alert('ID inválido'); window.location.href='index.php?controlador=carrito&accion=gestionar';</script>";
            return;
        }

        $idFactura = (int)$_GET['id'];
        $pdo = Conexion::conectar();

        $stmt = $pdo->prepare("SELECT f.*, c.nombre_cliente 
                       FROM factura f
                       INNER JOIN presupuesto p ON f.Presupuesto_idPresupuesto = p.idPresupuesto
                       INNER JOIN clientes c ON p.Cliente_idCliente = c.idCliente
                       WHERE f.idFactura = ?");
        $stmt->execute([$idFactura]);
        $factura = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$factura) {
            echo "<script>alert('Factura no encontrada'); window.location.href='index.php?controlador=carrito&accion=gestionar';</script>";
            return;
        }

        include 'vistas/modulo/ver_factura.php';

    }
}
