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

            <div class="card-header">
                <a href="agregar_clientes" class="btn btn-info">Agregar clientes</a>
            </div><!-- end card header -->

            <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped dt-responsive table-responsive nowrap">
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


                                <td><a href="index.php?pagina=editar_clientes&idCliente=<?php echo $cliente["idCliente"] ?>"
                                        class="btn btn-warning"><i class="fas fa-edit"></i></a>

                                    <button class="btn btn-danger btnEliminarClientes" idCliente=<?php echo $cliente["idCliente"]; ?>><i class="fas fa-trash"></i></button>
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
$eliminar->ctrEliminarCliente();
?>