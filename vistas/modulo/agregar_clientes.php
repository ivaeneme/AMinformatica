<div class="col-lg-12 mt-4">
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">Agregar cliente</h5>
        </div><!-- end card header -->

        <div class="card-body">
            <form action="" method="POST">

                <div class="mb-3">
                    <label for="nombre_cliente" class="form-label">Nombre y Apellido</label>
                    <input type="text" id="nombre_cliente" name="nombre_cliente" class="form-control" placeholder="Nombre del producto" required>
                </div>
                <div class="mb-3">
                    <label for="dni_cliente" class="form-label">Dni</label>
                    <input type="number" id="dni_cliente" name="dni_cliente" class="form-control" placeholder="Costo del producto" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="correo_cliente" class="form-label">Email</label>
                    <input type="email" class="form-control" id="correo_cliente" name="correo_cliente" placeholder="name@example.com">
                </div>
                <div class="mb-3">
                    <label for="telefono_cliente" class="form-label">Telefono</label>
                    <input type="number" id="telefono_cliente" name="telefono_cliente" class="form-control" placeholder="Ingrese su numero de telefono" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required>
                </div>

                <input type="hidden" id="fecha_creacion" name="fecha_creacion" value="<?php echo date('Y-m-d H:i:s'); ?>">


                <?php
                $guardar = new ControladorClientes();
                $guardar->ctrAgregarClientes();
                ?>

                <button class="btn btn-info" type="submit">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar
                </button>

            </form>
        </div>

    </div>
</div>