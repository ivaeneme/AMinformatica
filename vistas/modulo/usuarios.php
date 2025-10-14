<?php

if (!isset($_SESSION["Rol_idRol"])) $_SESSION["Rol_idRol"] = 2; // cliente por defecto

if ($_SESSION["Rol_idRol"] != 4) {
    echo "<h2 class='text-center text-danger'>Acceso restringido. Solo para administradores.</h2>";
    exit();
}

if (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {
    $filtro = trim($_GET['buscar']);
    $usuarios = ControladorUsuarios::ctrBuscarUsuarios($filtro);
} else {
    $usuarios = ControladorUsuarios::ctrMostrarUsuarios(null, null);
}

?>
<div class="row">
    <div class="col-12 mt-3">
        <h1>Usuarios</h1>
        <div class="col-4 mt-3 mb-3">
            <form method="GET" class="d-flex" role="search">
                <input type="hidden" name="pagina" value="usuarios">
                <input class="form-control me-2" type="search" name="buscar" placeholder="Buscar usuario..." aria-label="Buscar" value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
        </div>
        <div class="card">

            <div class="card-header">
                <a href="agregar_usuarios" class="btn btn-info">Agregar Usuario</a>
            </div><!-- end card header -->


            <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped dt-responsive table-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo Electronico</th>
                            <th>Rol</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($usuarios as $key => $usuario) {
                        ?>
                            <tr style="background-color:#000888">
                                <td> <?php echo $usuario["nombre_usuario"] ?> </td>
                                <td> <?php echo $usuario["email"] ?></td>
                                <td><?php
                                    switch ($usuario["Rol_idRol"]) {
                                        case '1':
                                            echo '<span class="badge text-bg-primary">Vendedor</span>';
                                            break;

                                        case '2':
                                            echo '<span class="badge text-bg-success">Cliente</span>';
                                            break;
                                        case '3':
                                            echo '<span class="badge text-bg-warning">Tecnico</span>';
                                            break;
                                        case '4':
                                            echo '<span class="badge text-bg-info">Admin</span>';
                                            break;
                                    }

                                    ?></td>


                                <td><a href="index.php?pagina=editar_usuarios&id_usuario=<?php echo $usuario["id_usuario"] ?>"
                                        class="btn btn-warning"><i class="fas fa-edit"></i></a>

                                    <a href="index.php?controlador=usuarios&accion=eliminar&id_usuario=<?php echo $usuario['id_usuario']; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>