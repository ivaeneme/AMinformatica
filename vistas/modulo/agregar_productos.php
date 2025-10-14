<div class="col-lg-12 mt-4">
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">Agregar producto</h5>
        </div><!-- end card header -->

        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="nombre_mercaderia" class="form-label">Nombre</label>
                    <input type="text" id="nombre_mercaderia" name="nombre_mercaderia" class="form-control" placeholder="Nombre del producto" required>
                </div>
                <div class="mb-3">
                    <label for="costo_mercaderia" class="form-label">Costo</label>
                    <input type="number" id="costo_mercaderia" name="costo_mercaderia" class="form-control" placeholder="Costo del producto" min="0" required>
                </div>
                
                <div class="mb-3">
                    <label for="stock_mercaderia" class="form-label"> Stock</label>
                    <input type="number" name="stock_mercaderia" class="form-control" placeholder="Ingrese el stock" min="0" required>
                </div>
      
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" name="marca" class="form-control"  placeholder="Ingrese la marca" required>
                </div>
                <div class="mb-3">
                    <label for="imagen_mercaderia" class="form-label">Imagen</label>
                    <input type="file" id="imagen_mercaderia" name="imagen_mercaderia" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="idtipo_mercaderia" class="form-label">Tipo</label>
                    <select id="idtipo_mercaderia" name="idtipo_mercaderia" class="form-control" required>
                        <option value="" disabled selected>Seleccione un tipo</option>
                        <?php
                        // Conexión a la base de datos
                        $conexion = new mysqli("localhost", "root", "", "aminformatica");

                        // Verificar conexión
                        if ($conexion->connect_error) {
                            die("Error de conexión: " . $conexion->connect_error);
                        }

                        // Consulta para obtener los tipos de mercadería
                        $query = "SELECT idtipo_mercaderia, nombre_tipo FROM tipomercaderia";
                        $result = $conexion->query($query);

                        // Generar las opciones del select
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Cada <option> generado dinámicamente con PHP
                                echo "<option value='" . $row['idtipo_mercaderia'] . "'>" . htmlspecialchars($row['nombre_tipo']) . "</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No hay tipos disponibles</option>";
                        }

                        // Cerrar conexión
                        $conexion->close();
                        ?>
                    </select>

                </div>

                <?php
                $guardar = new ControladorProductos();
                $guardar->ctrAgregarProducto();
                ?>

                <button class="btn btn-info" type="submit">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar
                </button>

            </form>
        </div>

    </div>
</div>