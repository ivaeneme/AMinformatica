<div class="col-lg-12 mt-4">
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">Agregar servicio</h5>
        </div><!-- end card header -->

        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="nombre_mercaderia" class="form-label">Nombre</label>
                    <input type="text" id="nombre_mercaderia" name="nombre_servicio" class="form-control" placeholder="Nombre del servicio" required>
                </div>
                <div class="mb-3">
                    <label for="costo_servicio" class="form-label">Costo</label>
                    <input type="number" id="costo_servicio" name="costo_servicio" class="form-control" placeholder="Costo del servicio" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="imagen_servicio" class="form-label">Imagen</label>
                    <input type="file" id="imagen_servicio" name="imagen_servicio" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="comentario" class="form-label">Detalle</label>
                    <textarea class="form-control" id="comentario" name="comentario" rows="3" placeholder="Detalle del servicio"></textarea>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de servicio</label>
                    <select id="tipo" name="tipo" class="form-select">
                        <?php
                        // Incluir el archivo de conexión
                        include 'conexion.php';

                        // Conexión a la base de datos usando la clase Conexion
                        $conexion = Conexion::conectar();

                        // Consulta para obtener los tipos de servicios
                        $query = "SELECT DISTINCT tipo FROM servicio";

                        try {
                            $stmt = $conexion->prepare($query);
                            $stmt->execute();

                            // Verifica si hay resultados y genera las opciones
                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . htmlspecialchars($row['tipo']) . '">' . htmlspecialchars($row['tipo']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No hay servicios disponibles</option>';
                            }
                        } catch (PDOException $e) {
                            echo '<option value="">Error al cargar los servicios</option>';
                        }
                        ?>
                    </select>
                </div>


                <?php
                $guardar = new ControladorServicios();
                $guardar->ctrAgregarServicio();
                ?>

                <button class="btn btn-info" type="submit">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar
                </button>

            </form>

        </div>


    </div>

</div>