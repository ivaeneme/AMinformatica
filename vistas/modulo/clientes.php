<?php
$clientes = ControladorClientes::ctrMostrarClientes(null, null);
if ($clientes) {
    foreach ($clientes as $cliente) {
        // Tu código para mostrar los clientes
    }
} else {
    echo "No hay clientes para mostrar.";
}
?>
<div class="row">
    <div class="col-12">
        <h1>Clientes</h1>
        <div class="card">

            <!-- <div class="card-header">
                <?php if ($_SESSION["Rol_idRol"] == 4): ?>
                    <a href="agregar_clientes" class="btn btn-info">Agregar Clientes</a>
                <?php endif; ?>
            </div>end card header -->

            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive table-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Fecha nacimiento</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Fecha inscripcion</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($clientes as $key => $cliente) {
                        ?>
                            <tr style="background-color:#000888">
                                <td> <?php echo $cliente["nombre_cliente"] ?></td>
                                <td> <?php echo $cliente["dni_cliente"] ?></td>
                                <td> <?php echo date('d-m-Y', strtotime($cliente["fecha_nacimiento"])) ?> </td>
                                <td> <?php echo $cliente["telefono_cliente"] ?></td>
                                <td> <?php echo $cliente["correo_cliente"] ?></td>
                                <td>
                                    <?php
                                    $fecha = new DateTime($cliente["fecha_creacion"]);
                                    echo $fecha->format('d-m-Y H:i:s');
                                    ?>
                                </td>


                                <td><?php if ($_SESSION["Rol_idRol"] == 4): ?>
                                    <a href="index.php?pagina=editar_clientes&idCliente=<?php echo $cliente["idCliente"] ?>"
                                        class="btn btn-warning"><i class="fas fa-edit"></i></a>

                                    <a href="index.php?pagina=clientes&idCliente=<?php echo $cliente['idCliente']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que querés eliminar este cliente?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php
$eliminar = new ControladorClientes();
$eliminar->ctrEliminarClientes();
?>