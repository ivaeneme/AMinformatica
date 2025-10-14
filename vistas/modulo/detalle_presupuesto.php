<?php
function estadoTexto($estado)
{
    switch ($estado) {
        case 1:
            return 'Creado';
        case 2:
            return 'Aprobado';
        case 3:
            return 'En proceso';
        case 4:
            return 'Terminado';
        case 5:
            return 'Entregado';
        case 6:
            return 'Cancelado';
        default:
            return 'Desconocido';
    }
}
?>

<div class="container mt-4">
    <h2>Detalle del Presupuesto #<?= $presupuesto['idPresupuesto'] ?? 'N/D' ?></h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Cliente:</strong> <?= htmlspecialchars($presupuesto['nombre_cliente']) ?></p>
            <p><strong>Fecha de Emisión:</strong> <?= $presupuesto['fechaEmision'] ?></p>
            <p><strong>Estado:</strong> <?= estadoTexto($presupuesto['estado_presupuesto']) ?></p>
            <p><strong>Total:</strong> $<?= number_format($presupuesto['costoTotal'], 2, ',', '.') ?></p>
        </div>
    </div>

    <h5>Productos y Servicios incluidos</h5>

    <!-- Botón Agregar -->
    <a href="index.php?controlador=carrito&accion=agregarItem&idPresupuesto=<?= $presupuesto['idPresupuesto'] ?>" class="btn btn-primary mb-3">
        + Agregar
    </a>

    <?php if (empty($detalleItems)): ?>
        <p>No se encontraron productos o servicios asociados a este presupuesto.</p>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Descripción</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalleItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['descripcion']) ?></td>
                        <td><?= htmlspecialchars($item['marca']) ?></td>
                        <td><?= htmlspecialchars($item['modelo']) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td>$<?= number_format($item['costoSubTotal'], 2, ',', '.') ?></td>
                        <td>
                            <!-- Botón Editar -->
                            <a href="index.php?controlador=carrito&accion=editarItem&id=<?= $item['idListaPresupuesto'] ?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>

                            <!-- Botón Eliminar -->
                            <form method="POST" action="index.php?controlador=carrito&accion=eliminarItem" onsubmit="return confirm('¿Eliminar este ítem?');">
                                <input type="hidden" name="id" value="<?= $item['idListaPresupuesto'] ?>">
                                <button type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (!$tieneFactura): ?>
            <!-- Botón para generar factura -->
            <a href="index.php?controlador=factura&accion=generarFactura&id=<?= $presupuesto['idPresupuesto'] ?>" class="btn btn-success my-3">
                Generar Factura
            </a>

        <?php else: ?>

            <!-- Ver factura cuando ya existe -->
            <a href="index.php?controlador=factura&accion=ver&id=<?= $tieneFactura['idFactura'] ?>" class="btn btn-info my-3">
                Ver Factura
            </a>


        <?php endif; ?>



    <?php endif; ?>
</div>