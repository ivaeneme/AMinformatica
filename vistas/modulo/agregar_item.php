<div class="container mt-4">
    <h3>Agregar ítems al presupuesto #<?= $idPresupuesto ?></h3>

    <form method="POST" action="index.php?controlador=carrito&accion=guardarItem">
        <input type="hidden" name="idPresupuesto" value="<?= $idPresupuesto ?>">

        <!-- PRODUCTOS -->
        <div class="card p-3 mb-3">
            <h5>Agregar Productos</h5>

            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                            <td><input type="checkbox" name="productosSeleccionados[]" value="<?= $p['idMercaderia'] ?>"></td>
                            <td><?= $p['nombre_mercaderia'] ?></td>
                            <td><?= $p['stock_mercaderia'] ?></td>
                            <td><input type="number" name="cantidad[<?= $p['idMercaderia'] ?>]" value="1" min="1" class="form-control"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- SERVICIOS -->
        <div class="card p-3 mb-3">
            <h5>Agregar Servicios</h5>

            <?php foreach ($servicios as $s): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="serviciosSeleccionados[]" value="<?= $s['idServicio'] ?>">
                    <label class="form-check-label"><?= $s['nombre_servicio'] ?> - $<?= number_format($s['costo_servicio'], 2, ',', '.') ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php?controlador=carrito&accion=verPresupuesto&id=<?= $idPresupuesto ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>


<script>
    function mostrarCampos() {
        const tipo = document.getElementById('tipo').value;
        document.getElementById('productoDiv').style.display = (tipo === 'producto') ? 'block' : 'none';
        document.getElementById('servicioDiv').style.display = (tipo === 'servicio') ? 'block' : 'none';
    }
</script>