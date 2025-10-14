<h2>Carrito de Compras</h2>

<?php if (empty($_SESSION['carrito'])): ?>
    <p>Tu carrito está vacío</p>
<?php else: ?>
    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['carrito'] as $key => $item):
                $subtotal = $item['cantidad'] * $item['precio'];
                $total += $subtotal;
                $esServicio = ($item['tipo'] ?? 'producto') === 'servicio';
            ?>
                <tr>
                    <td>
                        <?= $esServicio ? '<span class="badge bg-info">Servicio</span>' : '<span class="badge bg-primary">Producto</span>' ?>
                    </td>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>$<?= number_format($item['precio'], 2, ',', '.') ?></td>
                    <td>$<?= number_format($subtotal, 2, ',', '.') ?></td>
                    <td>
                        <a href="index.php?controlador=carrito&accion=quitar&id=<?= urlencode($key) ?>" class="btn btn-sm btn-danger">
                            Quitar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light">
            <tr>
                <td colspan="4" class="text-end fw-bold">Total:</td>
                <td colspan="2" class="fw-bold">$<?= number_format($total, 2, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="text-end">
        <a href="index.php?controlador=carrito&accion=confirmar" class="btn btn-success btn-lg">
            Confirmar Presupuesto
        </a>
    </div>
    
<?php endif; ?>
