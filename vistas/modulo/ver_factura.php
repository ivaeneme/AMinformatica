<div class="container mt-5">
    <h2>Factura N°<?= $factura['idFactura'] ?></h2>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($factura['nombre_cliente']) ?></p>
    <p><strong>Fecha de emisión:</strong> <?= $factura['fechaEmision'] ?></p>
    <p><strong>Total:</strong> $<?= number_format($factura['total'], 2, ',', '.') ?></p>

    <hr>
    <a href="index.php?controlador=carrito&accion=gestionar" class="btn btn-secondary">Volver</a>
</div>
