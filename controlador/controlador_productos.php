<?php
class ControladorProductos
{
    static public function ctrMostrarProductos($item, $valor)
    {
        $respuesta = ModeloProductos::mdlMostrarProductos($item, $valor);
        return $respuesta;
    }
    static public function ctrAgregarProducto()
    {
        if (isset($_POST["nombre_mercaderia"])) {
    
            $tabla = "mercaderia";
    
            // Verificar si se subió una imagen
            $rutaImagen = "";
            if (isset($_FILES["imagen_mercaderia"]["tmp_name"]) && !empty($_FILES["imagen_mercaderia"]["tmp_name"])) {
                $directorio = "vistas\assets\img"; // Directorio donde se guardarán las imágenes
    
                // Crear el directorio si no existe
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0755, true);
                }
    
                // Generar un nombre único para la imagen
                $nombreArchivo = uniqid() . "_" . basename($_FILES["imagen_mercaderia"]["name"]);
                $rutaImagen = $directorio . $nombreArchivo;
    
                // Mover la imagen al directorio
                if (!move_uploaded_file($_FILES["imagen_mercaderia"]["tmp_name"], $rutaImagen)) {
                    echo '<script>
                        fncSweetAlert(
                            "error",
                            "Error al subir la imagen",
                            ""
                        );
                        </script>';
                    return;
                }
            }
    
            $datos = array(
                "nombre_mercaderia" => $_POST["nombre_mercaderia"],
                "costo_mercaderia" => $_POST["costo_mercaderia"],
                "imagen_mercaderia" => $rutaImagen, // Ruta de la imagen
                "idtipo_mercaderia" => $_POST["idtipo_mercaderia"]
            );
    
            $url = ControladorPlantilla::url() . "productos";
            $respuesta = ModeloProductos::mdlAgregarProducto($tabla, $datos);
    
            if ($respuesta == "ok") {
                echo '<script>
                    fncSweetAlert(
                        "success",
                        "El producto se agregó correctamente",
                        "' . $url . '"
                    );
                    </script>';
            } else {
                echo '<script>
                    fncSweetAlert(
                        "error",
                        "Hubo un error al agregar el producto",
                        "' . $url . '"
                    );
                    </script>';
            }
        }
    }
    static public function ctrObtenerCategorias()
    {
        $tabla = "tipomercaderia";
        $categorias = ModeloProductos::mdlObtenerCategorias($tabla);
        return $categorias;
    }
    static public function ctrEditarProductos()
    {
        $tabla = "mercaderia";
    
        if (isset($_POST["idMercaderia"])) {
            // Manejo de la nueva imagen
            $rutaImagen = $_POST["imagen_actual"]; // Por defecto, conserva la imagen actual
            
            if (isset($_FILES["imagen_mercaderia"]["tmp_name"])) {
                // Directorio donde se guardan las imágenes
                $directorio = "vistas\assets\img";
                
                // Generar un nombre único para la nueva imagen
                $nombreImagen = uniqid() . "-" . basename($_FILES["imagen_mercaderia"]["name"]);
                $rutaImagen = $directorio . $nombreImagen;
    
                // Mover la imagen al directorio
                move_uploaded_file($_FILES["imagen_mercaderia"]["tmp_name"], $rutaImagen);

            }
    
            // Preparar los datos para actualizar en la base de datos
            $datos = array(
                "idMercaderia" => $_POST["idMercaderia"],
                "nombre_mercaderia" => $_POST["nombre_mercaderia"],
                "costo_mercaderia" => $_POST["costo_mercaderia"],
                "idtipo_mercaderia" => $_POST["idtipo_mercaderia"],
                "imagen_mercaderia" => $rutaImagen
            );
    
            // Llamar al modelo para actualizar
            $respuesta = ModeloProductos::mdlEditarProductos($tabla, $datos);
    
            // Verificar la respuesta
            if ($respuesta == "ok") {
                echo '<script>
                    fncSweetAlert(
                        "success",
                        "El producto se actualizó correctamente",
                        "' . ControladorPlantilla::url() . 'productos"
                    );
                    </script>';
            } else {
                echo '<script>
                    fncSweetAlert(
                        "error",
                        "Ocurrió un error al actualizar el producto",
                        "' . ControladorPlantilla::url() . 'productos"
                    );
                    </script>';
            }
        }
    }
    static public function ctrEliminarProductos()
    {

        if (isset($_GET["idMercaderia"])) {

            $url = ControladorPlantilla::url() . "productos";
            $tabla = "mercaderia";
            $dato = $_GET["idMercaderia"];

            $respuesta = ModeloProductos::mdlEliminarProductos($tabla, $dato);

            if ($respuesta == "ok"
            ) {
                echo '<script>
                 fncSweetAlert("success", "El cliente se eliminó correctamente", "' . $url . '");
                 </script>';
            }
        }
    }
    
    
}
