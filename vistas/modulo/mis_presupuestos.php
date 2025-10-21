<?php
function estadoTexto($estado)
{
    switch ($estado) {
        case 1:
            return '<span class="badge bg-light text-dark">Creado</span>';
        case 2:
            return '<span class="badge bg-primary">Aprobado</span>';
        case 3:
            return '<span class="badge bg-warning text-dark">En proceso</span>';
        case 4:
            return '<span class="badge bg-success">Terminado</span>';
        case 5:
            return '<span class="badge bg-secondary">Entregado</span>';
        case 6:
            return '<span class="badge bg-danger">Cancelado</span>';
        default:
            return '<span class="badge bg-dark">Desconocido</span>';
    }
}
?>

<div class="container mt-4">
    <h2>Mis presupuestos</h2>

    <!-- Filtro por estado -->
    <form method="GET" class="mb-3 d-flex align-items-center gap-2">
        <input type="hidden" name="controlador" value="carrito">
        <input type="hidden" name="accion" value="historial">
        <label for="estado" class="form-label mb-0">Filtrar por estado:</label>
        <select name="estado" id="estado" class="form-select form-select-sm" style="width: auto;">
            <option value="">Todos</option>
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <option value="<?= $i ?>" <?= ($_GET['estado'] ?? '') == $i ? 'selected' : '' ?>>
                    <?= strip_tags(estadoTexto($i)) ?>
                </option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-sm btn-secondary">Filtrar</button>
    </form>

    <?php if (empty($presupuestos)): ?>
        <p>No hay presupuestos registrados.</p>
    <?php else: ?>

        <?php
        // Agrupar presupuestos por ID
        $presupuestosAgrupados = [];
        foreach ($presupuestos as $item) {
            $presupuestosAgrupados[$item['idPresupuesto']]['info'] = [
                'fecha' => $item['fechaEmision'],
                'total' => $item['costoTotal'],
                'estado' => $item['estado_presupuesto']
            ];
            $presupuestosAgrupados[$item['idPresupuesto']]['items'][] = $item;
        }

        // Mostrar en orden descendente (más nuevos arriba)
        krsort($presupuestosAgrupados);
        ?>

        <div class="accordion" id="accordionPresupuestos">
            <?php foreach ($presupuestosAgrupados as $id => $grupo): ?>
                <?php
                $info = $grupo['info'];
                $collapseId = "collapse_" . $id;
                ?>
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading_<?= $id ?>">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#<?= $collapseId ?>"
                            aria-expanded="false"
                            aria-controls="<?= $collapseId ?>">
                            <strong>Presupuesto N°<?= $id ?></strong>
                            &nbsp;| Fecha: <?= $info['fecha'] ?>
                            &nbsp;| Total: <span class="text-primary fw-bold">$<?= number_format($info['total'], 2, ',', '.') ?></span>
                            &nbsp;| Estado: <?= estadoTexto($info['estado']) ?>
                        </button>
                    </h2>
                    <div id="<?= $collapseId ?>" class="accordion-collapse collapse"
                        aria-labelledby="heading_<?= $id ?>" data-bs-parent="#accordionPresupuestos">
                        <div class="accordion-body">
                            <table class="table table-sm table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Marca</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($grupo['items'] as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['descripcion']) ?></td>
                                            <td><?= htmlspecialchars($item['marca']) ?></td>
                                            <td><?= $item['cantidad'] ?></td>
                                            <td>$<?= number_format($item['costoSubTotal'], 2, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>