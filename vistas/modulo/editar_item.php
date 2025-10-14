<h3>Editar ítem del presupuesto</h3>

<form method="POST" action="index.php?controlador=carrito&accion=actualizarItem" class="mt-3">
    <input type="hidden" name="idListaPresupuesto" value="<?= $item['idListaPresupuesto'] ?>">
    <input type="hidden" name="idPresupuesto" value="<?= $item['idPresupuesto'] ?>">

    <!-- Producto -->
    <div class="mb-3">
        <label>Producto:</label>
        <select name="idProducto" class="form-select">
            <option value="">-- Ninguno --</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['idMercaderia'] ?>"
                    <?= (isset($item['idProducto']) && $item['idProducto'] == $producto['idMercaderia']) ? 'selected' : '' ?>>
                    <?= $producto['nombre_mercaderia'] ?> - <?= $producto['marca'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Servicio -->
    <div class="mb-3">
        <label>Servicio:</label>
        <select name="idServicio" class="form-select">
            <option value="">-- Ninguno --</option>
            <?php foreach ($servicios as $servicio): ?>
                <option value="<?= $servicio['idServicio'] ?>"
                    <?= (isset($item['idServicio']) && $item['idServicio'] == $servicio['idServicio']) ? 'selected' : '' ?>>
                    <?= $servicio['nombre_servicio'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="mb-3">
        <label>Cantidad:</label>
        <input type="number" name="cantidad" class="form-control" min="1" value="<?= $item['cantidad'] ?>">
    </div>

    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="index.php?controlador=carrito&accion=detalle&id=<?= $item['idPresupuesto'] ?>" class="btn btn-secondary">Cancelar</a>
</form>