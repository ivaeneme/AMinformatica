<?php

$item = "idServicio";
$valor = $_GET["idServicio"];

$servicio = ControladorServicios::ctrMostrarServicio($item, $valor);


?>

<div class="col-lg-12 mt-4">
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">Editar servicio</h5>
        </div><!-- end card header -->

        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">

                <!-- Campo para el nombre del servicio -->
                <div class="mb-3">
                    <label for="nombre_servicio" class="form-label">Nombre del servicio</label>
                    <input type="text" name="nombre_servicio" class="form-control" value="<?php echo $servicio["nombre_servicio"]; ?>" placeholder="Ingrese el nombre del servicio" required>
                </div>

                <!-- Campo para el costo del servicio -->
                <div class="mb-3">
                    <label for="costo_servicio" class="form-label">Costo</label>
                    <input type="number" step="0.01" name="costo_servicio" class="form-control" value="<?php echo $servicio["costo_servicio"]; ?>" placeholder="Ingrese el costo del servicio" required>
                </div>

                <!--Campo para el tipo de servicio-->
                <div class="mb-3">
                    <label for="tipo" class="form-label">Descripción</label>
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
                                    echo '<option value="' . $servicio["tipo"] . '</option>';
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

                    <!--Campo para el detalle de servicio-->
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Detalle</label>
                        
                        <textarea class="form-control" id="comentario" name="comentario" rows="3" placeholder="Detalle del servicio"><?php echo $servicio["comentario"]; ?></textarea required>
                    </div>

                    <!-- Campo para cargar la nueva imagen -->
                    <div class="mb-3">
                        <label for="imagen_servicio" class="form-label">Imagen</label>
                        <input type="file" name="imagen_servicio" id="imagen_servicio" class="form-control" accept="image/*">

                        <!-- Previsualización de la imagen -->
                        <div class="mt-3">
                            <p>Previsualización de la imagen:</p>
                            <img
                                id="imagen_preview"
                                src="<?php echo $servicio['imagen_servicio']; ?>"
                                alt="Previsualización"
                                style="max-width: 150px; max-height: 150px;">
                        </div>
                    </div>

                    <!-- Script para manejar la previsualización -->
                    <script>
                        document.getElementById("imagen_servicio").addEventListener("change", function(event) {
                            const file = event.target.files[0];
                            const preview = document.getElementById("imagen_preview");

                            if (file) {
                                const reader = new FileReader();

                                reader.onload = function(e) {
                                    // Cambiar la previsualización a la nueva imagen seleccionada
                                    preview.src = e.target.result;
                                };

                                reader.readAsDataURL(file);
                            } else {
                                // Restaurar la previsualización a la imagen actual si no se selecciona un archivo
                                preview.src = "<?php echo $servicio['imagen_servicio']; ?>";
                            }
                        });
                    </script>

                    <!-- Campo oculto para enviar la ruta de la imagen actual -->
                    <input type="hidden" name="imagen_actual" value="<?php echo $servicio['imagen_servicio']; ?>">

                    <!-- Campo oculto para enviar el ID del producto -->
                    <input type="hidden" name="idServicio" value="<?php echo $servicio["idServicio"]; ?>">

                    <!-- Llamada al controlador para procesar los datos -->
                    <?php
                    $editar = new ControladorServicios();
                    $editar->ctrEditarservicio();
                    ?>

                    <!-- Botón de guardar -->
                    <button class="btn btn-info" type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

            </form>
        </div>


    </div>
</div>