<?php

$item = "idCliente";
$valor = $_GET["idCliente"];

$cliente = ControladorClientes::ctrMostrarClientes($item, $valor);


?>

<div class="col-lg-12 mt-4">
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">Editar cliente</h5>
        </div><!-- end card header -->

        <div class="card-body">
            <form action="" method="POST">

                <!-- Campo para el nombre del cliente -->
                <div class="mb-3">
                    <label for="nombre_cliente" class="form-label">Nombre del cliente</label>
                    <input type="text" name="nombre_cliente" class="form-control" value="<?php echo $cliente["nombre_cliente"]; ?>" placeholder="Ingrese el nombre del cliente" required>
                </div>

                <!-- Campo para el DNI del cliente -->
                <div class="mb-3">
                    <label for="dni_cliente" class="form-label">DNI</label>
                    <input type="text" name="dni_cliente" class="form-control" value="<?php echo $cliente["dni_cliente"]; ?>" placeholder="Ingrese el costo del cliente" required>
                </div>

                <!--Campo para el email de cliente-->
                <div class="mb-3">
                    <label for="correo_cliente" class="form-label">Email</label>
                    <input type="email" class="form-control" id="correo_cliente" name="correo_cliente" value="<?php echo $cliente["correo_cliente"] ?>">
                </div>

                <!--Campo para el telefono del cliente-->
                <div class="mb-3">
                    <label for="telefono_cliente" class="form-label">Telefono</label>
                    <input type="text" id="telefono_cliente" name="telefono_cliente" class="form-control" value="<?php echo $cliente["telefono_cliente"]?>" required>
                </div>

                <!--Campo para la fecha de nacimiento del cliente-->
                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="<?php echo $cliente["fecha_nacimiento"]?>" required>
                </div>

                <!-- Campo oculto para enviar el ID del producto -->
                <input type="hidden" name="idCliente" value="<?php echo $cliente["idCliente"]; ?>">

                <!-- Llamada al controlador para procesar los datos -->
                <?php
                $editar = new ControladorClientes();
                $editar->ctrEditarClientes();
                ?>

                <!-- Botón de guardar -->
                <button class="btn btn-info" type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

            </form>
        </div>


    </div>
</div>