<div class="container mt-4">
    <h3>Agregar ítems al presupuesto #<?= $idPresupuesto ?></h3>

    <form method="POST" action="index.php?controlador=carrito&accion=guardarItem">
        <input type="hidden" name="idPresupuesto" value="<?= $idPresupuesto ?>">

        <!-- PRODUCTOS -->
        <div class="card p-3 mb-3">
            <h5>Agregar Productos</h5>

            <?php if (!empty($productos)): ?>
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td><input type="checkbox" name="productosSeleccionados[]" value="<?= $p['idMercaderia'] ?>"></td>
                                <td><?= htmlspecialchars($p['nombre_mercaderia']) ?></td>
                                <td>$<?= htmlspecialchars($p['costo_mercaderia']) ?></td>
                                <td><?= (int)$p['stock_mercaderia'] ?></td>
                                <td>
                                    <input type="number" 
                                           name="cantidad[<?= $p['idMercaderia'] ?>]" 
                                           value="1" 
                                           min="1" 
                                           max="<?= (int)$p['stock_mercaderia'] ?>" 
                                           class="form-control">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay productos disponibles para agregar.</p>
            <?php endif; ?>
        </div>

        <!-- SERVICIOS -->
        <div class="card p-3 mb-3">
            <h5>Agregar Servicios</h5>

            <?php if (!empty($serviciosDisponibles)): ?>
                <?php foreach ($serviciosDisponibles as $s): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="serviciosSeleccionados[]" value="<?= $s['idServicio'] ?>" id="servicio<?= $s['idServicio'] ?>">
                        <label class="form-check-label" for="servicio<?= $s['idServicio'] ?>">
                            <?= htmlspecialchars($s['nombre_servicio']) ?> - $<?= number_format($s['costo_servicio'], 2, ',', '.') ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Todos los servicios ya están incluidos en este presupuesto.</p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php?controlador=carrito&accion=verPresupuesto&id=<?= $idPresupuesto ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
