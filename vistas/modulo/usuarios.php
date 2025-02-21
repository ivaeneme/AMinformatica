<?php
$usuarios = ControladorUsuarios::ctrMostrarUsuarios(null,null);

if ($usuarios) {
    foreach ($usuarios as $usuario) {
        // Tu código para mostrar los pagos
    }
} else {
    echo "No hay pagos para mostrar.";
}
?>
<div class="row">
    <div class="col-12">
        <h1>usuario</h1>
        <div class="card">

            <div class="card-header">
                <a href="agregar_usuario" class="btn btn-info">Agregar Usuario</a>
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
                                <td> <?php echo $usuario["email"]?></td>
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
                                }
                                
                                ?></td> 
                                
                                
                                <td><a href="index.php?pagina=editar_usuarios&id_usuario=<?php echo $usuario["id_usuario"] ?>"
                                        class="btn btn-warning"><i class="fas fa-edit"></i></a>

                                <button class="btn btn-danger btnEliminarUsuarios" id_usuario=<?php echo $usuario["id_usuario"]; ?>><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

