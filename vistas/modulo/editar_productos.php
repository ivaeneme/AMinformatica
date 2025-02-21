<?php

$item = "idMercaderia";
$valor = $_GET["idMercaderia"];

$productos = ControladorProductos::ctrMostrarProductos($item, $valor);


?>

<div class="col-lg-12 mt-4">
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">Editar producto</h5>
        </div><!-- end card header -->

        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">

                <!-- Campo para el nombre del producto -->
                <div class="mb-3">
                    <label for="nombre_mercaderia" class="form-label">Nombre de la Mercadería</label>
                    <input type="text" name="nombre_mercaderia" class="form-control" value="<?php echo $productos["nombre_mercaderia"]; ?>" placeholder="Ingrese el nombre del producto" required>
                </div>

                <!-- Campo para el costo del producto -->
                <div class="mb-3">
                    <label for="costo_mercaderia" class="form-label">Costo</label>
                    <input type="number" step="0.01" name="costo_mercaderia" class="form-control" value="<?php echo $productos["costo_mercaderia"]; ?>" placeholder="Ingrese el costo del producto" required>
                </div>

                <!-- Campo para seleccionar la categoría -->
                <div class="mb-3">
                    <label for="idtipo_mercaderia" class="form-label">Categoría</label>
                    <select name="idtipo_mercaderia" class="form-control" required>
                        <!-- Mostrar la categoría actual seleccionada -->
                        <option value="<?php echo $productos["idtipo_mercaderia"]; ?>" selected>
                            <?php
                            foreach (ControladorProductos::ctrObtenerCategorias() as $categoria) {
                                if ($categoria["idtipo_mercaderia"] == $productos["idtipo_mercaderia"]) {
                                    echo $categoria["nombre_tipo"];
                                    break;
                                }
                            }
                            ?>
                        </option>
                        <!-- Mostrar las demás categorías como opciones -->
                        <?php
                        foreach (ControladorProductos::ctrObtenerCategorias() as $categoria) {
                            if ($categoria["idtipo_mercaderia"] != $productos["idtipo_mercaderia"]) {
                                echo '<option value="' . $categoria["idtipo_mercaderia"] . '">' . $categoria["nombre_tipo"] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Campo para cargar la nueva imagen -->
                <div class="mb-3">
                    <label for="imagen_mercaderia" class="form-label">Imagen</label>
                    <input type="file" name="imagen_mercaderia" id="imagen_mercaderia" class="form-control" accept="image/*">

                    <!-- Previsualización de la imagen -->
                    <div class="mt-3">
                        <p>Previsualización de la imagen:</p>
                        <img
                            id="imagen_preview"
                            src="<?php echo $productos['imagen_mercaderia']; ?>"
                            alt="Previsualización"
                            style="max-width: 150px; max-height: 150px;">
                    </div>
                </div>

                <!-- Script para manejar la previsualización -->
                <script>
                    document.getElementById("imagen_mercaderia").addEventListener("change", function(event) {
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
                            preview.src = "<?php echo $productos['imagen_mercaderia']; ?>";
                        }
                    });
                </script>

                <!-- Campo oculto para enviar la ruta de la imagen actual -->
                <input type="hidden" name="imagen_actual" value="<?php echo $productos['imagen_mercaderia']; ?>">

                <!-- Campo oculto para enviar el ID del producto -->
                <input type="hidden" name="idMercaderia" value="<?php echo $productos["idMercaderia"]; ?>">

                <!-- Llamada al controlador para procesar los datos -->
                <?php
                $editar = new ControladorProductos();
                $editar->ctrEditarProductos();
                ?>

                <!-- Botón de guardar -->
                <button class="btn btn-info" type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

            </form>
        </div>


    </div>
</div>