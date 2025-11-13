<?php
// Idealmente esta función iría en un helper común, que cargues en el controlador antes de la vista
function estadoTextoBadge(int $estado): string
{
    return match ($estado) {
        1 => '<span class="badge bg-light text-dark">Creado</span>',
        2 => '<span class="badge bg-primary">Aprobado</span>',
        3 => '<span class="badge bg-warning text-dark">En proceso</span>',
        4 => '<span class="badge bg-success">Terminado</span>',
        5 => '<span class="badge bg-secondary">Entregado</span>',
        6 => '<span class="badge bg-danger">Cancelado</span>',
        default => '<span class="badge bg-dark">Desconocido</span>',
    };
}
?>

<div class="container mt-4">
    <h2>Gestión de presupuestos</h2>
    <?php if (in_array($_SESSION['Rol_idRol'] ?? 0, [1, 4])): ?>
        <form method="GET" class="row g-3 align-items-center mb-4">
            <input type="hidden" name="controlador" value="carrito">
            <input type="hidden" name="accion" value="gestionar">

            <!-- Nombre del cliente -->
            <div class="col-md-4">
                <label for="filtroCliente" class="form-label">Nombre del cliente</label>
                <input type="text" class="form-control" name="cliente" id="filtroCliente" placeholder="Buscar cliente...">
            </div>

            <!-- Filtro por mes y año -->
            <div class="col-md-3">
                <label for="filtroMes" class="form-label">Mes</label>
                <select name="mes" id="filtroMes" class="form-select">
                    <option value="">-- Todos --</option>
                    <?php
                    $meses = [
                        1 => 'Enero',
                        2 => 'Febrero',
                        3 => 'Marzo',
                        4 => 'Abril',
                        5 => 'Mayo',
                        6 => 'Junio',
                        7 => 'Julio',
                        8 => 'Agosto',
                        9 => 'Septiembre',
                        10 => 'Octubre',
                        11 => 'Noviembre',
                        12 => 'Diciembre'
                    ];
                    foreach ($meses as $num => $nombre) {
                        echo "<option value=\"$num\">$nombre</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-2">
                <label for="filtroAnio" class="form-label">Año</label>
                <select name="anio" id="filtroAnio" class="form-select">
                    <option value="">-- Todos --</option>
                    <?php
                    $anioActual = date('Y');
                    for ($i = $anioActual; $i >= $anioActual - 10; $i--) {
                        echo "<option value=\"$i\">$i</option>";
                    }
                    ?>
                </select>
            </div>


            <!-- Estado del presupuesto -->
            <div class="col-md-3">
                <label for="filtroEstado" class="form-label">Estado</label>
                <select name="estado" id="filtroEstado" class="form-select">
                    <option value="">-- Todos --</option>
                    <option value="1">Creado</option>
                    <option value="2">Aprobado</option>
                    <option value="3">En proceso</option>
                    <option value="4">Terminado</option>
                    <option value="5">Entregado</option>
                    <option value="6">Cancelado</option>
                </select>
            </div>

            <!-- Botón aplicar -->
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100">Aplicar</button>
            </div>
        </form>
    <?php endif; ?>


    <?php if (empty($presupuestos)): ?>
        <p>No hay presupuestos registrados.</p>
    <?php else: ?>


        <?php
        $itemsAgrupados = [];

        foreach ($presupuestos as $item) {
            $id = $item['idPresupuesto'];
            $itemsAgrupados[$id]['cliente'] = $item['nombre_cliente'];
            $itemsAgrupados[$id]['fecha'] = $item['fechaEmision'];
            $itemsAgrupados[$id]['total'] = $item['costoTotal'];
            $itemsAgrupados[$id]['estado'] = $item['estado_presupuesto'];
            $itemsAgrupados[$id]['items'][] = $item;
            $tieneFactura = $facturasMap[$id] ?? null;
        }

        uasort($itemsAgrupados, fn($a, $b) => strtotime($b['fecha']) - strtotime($a['fecha']));

        foreach ($itemsAgrupados as $id => $grupo):
            $collapseId = 'detalle' . $id;

            // Verificar si hay servicios pendientes
            $hayServiciosPendientes = false;
            foreach ($grupo['items'] as $item) {
                if (isset($item['estado_servicio']) && $item['estado_servicio'] != 2) {
                    $hayServiciosPendientes = true;
                    break;
                }
            }

            $cardClass = $hayServiciosPendientes ? 'border-warning' : 'border-success';

            $rol = $_SESSION['Rol_idRol'] ?? null;
            $puedeBorrar = in_array($rol, [1, 4]) && in_array($grupo['estado'], [5, 6]);
        ?>
            <div class="card mb-3 <?= htmlspecialchars($cardClass) ?>">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Presupuesto N°<?= htmlspecialchars($id) ?></strong> |
                        Cliente: <?= htmlspecialchars($grupo['cliente']) ?> |
                        Fecha: <?= htmlspecialchars($grupo['fecha']) ?> |
                        <span class="text-primary fw-bold">Total: $<?= number_format($grupo['total'], 2, ',', '.') ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <?= estadoTextoBadge($grupo['estado']) ?>

                        <!-- Formulario que se envía automáticamente al cambiar el select -->
                        <form method="POST" action="index.php?controlador=carrito&accion=actualizarEstado" class="d-flex align-items-center ms-3" novalidate>
                            <input type="hidden" name="idPresupuesto" value="<?= htmlspecialchars($id) ?>">
                            <select name="estado" class="form-select form-select-sm me-2" onchange="this.form.submit()" aria-label="Cambiar estado presupuesto <?= htmlspecialchars($id) ?>">
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <?php $bloquearTerminado = $hayServiciosPendientes && $i == 4; ?>
                                    <option value="<?= $i ?>" <?= $i == $grupo['estado'] ? 'selected' : '' ?> <?= $bloquearTerminado ? 'disabled' : '' ?>>
                                        <?= strip_tags(estadoTextoBadge($i)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </form>

                        <?php if ($puedeBorrar): ?>
                            <a href="index.php?controlador=carrito&accion=borrar&id=<?= htmlspecialchars($id) ?>"
                                onclick="event.preventDefault();
            fncSweetAlert('confirm', '¿Confirma borrar este presupuesto?')
              .then((ok) => { if (ok) window.location = this.href; });"
                                class="btn btn-sm btn-danger ms-3"
                                aria-label="Borrar presupuesto <?= htmlspecialchars($id) ?>">
                                <i class="fas fa-trash"></i>
                            </a>

                        <?php endif; ?>

                        <button class="btn btn-sm btn-outline-secondary ms-3" data-bs-toggle="collapse" data-bs-target="#<?= htmlspecialchars($collapseId) ?>" aria-expanded="false" aria-controls="<?= htmlspecialchars($collapseId) ?>">
                            Ver detalles
                        </button>
                    </div>
                </div>

                <div class="collapse" id="<?= htmlspecialchars($collapseId) ?>">
                    <table class="table table-bordered mb-0">
                        <thead class="table-secondary text-center">
                            <tr>
                                <th>Descripción</th>
                                <th>Marca</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Estado Servicio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grupo['items'] as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['descripcion']) ?></td>
                                    <td><?= htmlspecialchars($item['marca'] ?? '') ?></td>

                                    <td class="text-center"><?= (int)$item['cantidad'] ?></td>
                                    <td class="text-end">$<?= number_format($item['costoSubTotal'], 2, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?php
                                        if (!is_null($item['estado_servicio'])) {
                                            echo match ($item['estado_servicio']) {
                                                1 => "<span class='badge bg-warning text-dark'>En proceso</span>",
                                                2 => "<span class='badge bg-success'>Terminado</span>",
                                                default => "<span class='badge bg-secondary'>Sin estado</span>",
                                            };
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <!-- Fila extra con el botón Ver Detalle -->
                            <tr>
                                <td colspan="6" class="text-end">
                                    <?php if ($grupo['estado'] == 1): ?>
                                        <a href="index.php?controlador=carrito&accion=detalle&id=<?= htmlspecialchars($id) ?>"
                                            class="btn btn-sm btn-info"
                                            aria-label="Modificar presupuesto <?= htmlspecialchars($id) ?>">
                                            Modificar
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($grupo['estado'] != 1): ?>
                                        <?php
                                        $idFactura = $modeloCarrito->obtenerIdFacturaPorPresupuesto($id);
                                        ?>

                                        <?php if (!$idFactura): ?>
                                            <a href="index.php?controlador=factura&accion=generarFactura&id=<?= $id ?>" class="btn btn-success btn-sm">
                                                Generar Factura
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?controlador=factura&accion=ver&id=<?= $idFactura ?>" class="btn btn-info btn-sm">
                                                Ver Factura
                                            </a>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        <?php endforeach; ?>


    <?php endif; ?>

</div>