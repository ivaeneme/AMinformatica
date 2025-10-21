<div class="container mt-4">
    <h3 class="mb-4 text-primary">
        Editar ítem del presupuesto #<?= htmlspecialchars($item['idPresupuesto']) ?>
    </h3>

    <form method="POST" action="index.php?controlador=carrito&accion=actualizarItem" class="card p-4 shadow-sm rounded-3">
        <input type="hidden" name="idListaPresupuesto" value="<?= htmlspecialchars($item['idListaPresupuesto']) ?>">
        <input type="hidden" name="idPresupuesto" value="<?= htmlspecialchars($item['idPresupuesto']) ?>">

        <!-- Tipo de ítem -->
        <div class="mb-3">
            <label class="form-label fw-bold">Tipo de ítem</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" 
                       type="radio" 
                       name="tipoItem" 
                       id="tipoProducto" 
                       value="producto" 
                       <?= !empty($item['idProducto']) ? 'checked' : '' ?> 
                       onclick="toggleSelects('producto')">
                <label class="form-check-label" for="tipoProducto">Producto</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" 
                       type="radio" 
                       name="tipoItem" 
                       id="tipoServicio" 
                       value="servicio" 
                       <?= !empty($item['idServicio']) ? 'checked' : '' ?> 
                       onclick="toggleSelects('servicio')">
                <label class="form-check-label" for="tipoServicio">Servicio</label>
            </div>
        </div>

        <!-- Producto -->
        <div class="mb-3" id="selectProducto">
            <label class="form-label fw-bold">Producto</label>
            <select name="idProducto" id="idProducto" class="form-select">
                <option value="">-- Seleccionar producto --</option>
                <?php foreach ($productos as $producto): ?>
                    <option 
                        value="<?= $producto['idMercaderia'] ?>"
                        data-stock="<?= (int)$producto['stock_mercaderia'] ?>"
                        <?= (!empty($item['idProducto']) && $item['idProducto'] == $producto['idMercaderia']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($producto['nombre_mercaderia']) ?> - 
                        <?= htmlspecialchars($producto['marca']) ?> 
                        ($<?= number_format($producto['costo_mercaderia'], 2, ',', '.') ?>)
                        [Stock: <?= (int)$producto['stock_mercaderia'] ?>]
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Servicio -->
        <div class="mb-3" id="selectServicio">
            <label class="form-label fw-bold">Servicio</label>
            <select name="idServicio" id="idServicio" class="form-select">
                <option value="">-- Seleccionar servicio --</option>
                <?php foreach ($servicios as $servicio): ?>
                    <option 
                        value="<?= $servicio['idServicio'] ?>"
                        <?= (!empty($item['idServicio']) && $item['idServicio'] == $servicio['idServicio']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($servicio['nombre_servicio']) ?> 
                        ($<?= number_format($servicio['costo_servicio'], 2, ',', '.') ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Cantidad</label>
            <input 
                type="number" 
                id="cantidad" 
                name="cantidad" 
                class="form-control w-25" 
                min="1" 
                value="<?= htmlspecialchars($item['cantidad']) ?>" 
                required>
            <small id="stockInfo" class="text-muted"></small>
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <a href="index.php?controlador=carrito&accion=detalle&id=<?= htmlspecialchars($item['idPresupuesto']) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Actualizar ítem
            </button>
        </div>
    </form>
</div>

<script>
function toggleSelects(tipo) {
    const producto = document.getElementById('selectProducto');
    const servicio = document.getElementById('selectServicio');
    const cantidad = document.getElementById('cantidad');
    const stockInfo = document.getElementById('stockInfo');

    if (tipo === 'producto') {
        producto.style.display = 'block';
        servicio.style.display = 'none';
        actualizarMaximo(); // aplicar límite según stock
    } else {
        producto.style.display = 'none';
        servicio.style.display = 'block';
        cantidad.removeAttribute('max'); // sin límite
        stockInfo.textContent = ''; // limpiar texto de stock
    }
}

function actualizarMaximo() {
    const selectProducto = document.getElementById('idProducto');
    const inputCantidad = document.getElementById('cantidad');
    const stockInfo = document.getElementById('stockInfo');

    const option = selectProducto.options[selectProducto.selectedIndex];
    const stock = option?.getAttribute('data-stock');

    if (stock) {
        inputCantidad.max = stock;
        stockInfo.textContent = `Stock disponible: ${stock}`;
    } else {
        inputCantidad.removeAttribute('max');
        stockInfo.textContent = '';
    }
}

// Mostrar el tipo correcto al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const tipoSeleccionado = document.querySelector('input[name="tipoItem"]:checked')?.value || 'producto';
    toggleSelects(tipoSeleccionado);

    // Si hay productos, conectar el evento de cambio
    const selectProducto = document.getElementById('idProducto');
    if (selectProducto) {
        selectProducto.addEventListener('change', actualizarMaximo);
    }
});
</script>
