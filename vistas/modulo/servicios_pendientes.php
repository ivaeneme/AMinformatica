<div class="container mt-4">
    <h2 class="mb-4">Servicios asignados</h2>

    <?php if (empty($tareas)) : ?>
        <div class="alert alert-info">No hay servicios asignados aún.</div>
    <?php else : ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Cliente</th>
                    <th>Presupuesto</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tareas as $servicio) : ?>
                    <tr>
                        <td><?= htmlspecialchars($servicio['nombre_cliente']) ?></td>
                        <td>#<?= $servicio['idPresupuesto'] ?></td>
                        <td><?= htmlspecialchars($servicio['descripcion']) ?></td>
                        <td><?= htmlspecialchars($servicio['cantidad']) ?></td>
                        <td>
                            <?php
                            switch ($servicio['estado_servicio']) {
                                case 1:
                                    echo "<span class='badge bg-warning text-dark'>En proceso</span>";
                                    break;
                                case 2:
                                    echo "<span class='badge bg-success'>Terminado</span>";
                                    break;
                                default:
                                    echo "<span class='badge bg-secondary'>Sin estado</span>";
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($servicio['estado_servicio'] != 2) : ?>
                                <form method="POST" action="index.php?controlador=carrito&accion=actualizarEstadoServicio" class="d-inline">
                                    <input type="hidden" name="idProducto" value="<?= $servicio['idProductos'] ?>">
                                    <select name="estado" class="form-select form-select-sm d-inline w-auto" required onchange="this.form.submit()">
                                        <option value="1" <?= $servicio['estado_servicio'] == 1 ? 'selected' : '' ?>>En proceso</option>
                                        <option value="2" <?= $servicio['estado_servicio'] == 2 ? 'selected' : '' ?>>Terminado</option>
                                    </select>
                                </form>
                            <?php else : ?>
                                <span class="text-muted">Finalizado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>