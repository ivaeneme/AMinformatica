<?php

class ControladorServicios {
    static public function ctrMostrarServicio($item, $valor)
    {
        $respuesta= ModeloServicios::mdlMostrarServicios($item,$valor);
        return $respuesta;
    }

        static public function ctrBuscarServicios($filtro)
    {
        return ModeloServicios::mdlBuscarServicios($filtro);
    }

    
    static public function ctrAgregarServicio()
    {
        if (isset($_POST["nombre_servicio"])) {
    
            $tabla = "servicio";
    
            // Verificar si se subió una imagen
            $rutaImagen = "";
            if (isset($_FILES["imagen_servicio"]["tmp_name"]) && !empty($_FILES["imagen_servicio"]["tmp_name"])) {
                $directorio = "vistas/assets/img/"; // Directorio donde se guardarán las imágenes
    
                // Crear el directorio si no existe
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0755, true);
                }
    
                // Generar un nombre único para la imagen
                $nombreArchivo = uniqid() . "_" . basename($_FILES["imagen_servicio"]["name"]);
                $rutaImagen = $directorio . $nombreArchivo;
    
                // Mover la imagen al directorio
                if (!move_uploaded_file($_FILES["imagen_servicio"]["tmp_name"], $rutaImagen)) {
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
                "nombre_servicio" => $_POST["nombre_servicio"],
                "costo_servicio" => $_POST["costo_servicio"],
                "imagen_servicio" => $rutaImagen, // Ruta de la imagen
                "comentario" => $_POST["comentario"],
                "tipo" => $_POST["tipo"]
            );
            echo ($datos);
            $url = ControladorPlantilla::url() . "servicios";
            $respuesta = ModeloServicios::mdlAgregarServicio($tabla, $datos);
    
            if ($respuesta == "ok") {
                echo '<script>
                    fncSweetAlert(
                        "success",
                        "El servicio se agregó correctamente",
                        "' . $url . '"
                    );
                    </script>';
            } else {
                echo '<script>
                    fncSweetAlert(
                        "error",
                        "Hubo un error al agregar el servicio",
                        "' . $url . '"
                    );
                    </script>';
            }
        }
    }
    static public function ctrEditarServicio()
    {
        $tabla = "servicio";

        if (isset($_POST["idServicio"])) {
            // Manejo de la nueva imagen
            $rutaImagen = $_POST["imagen_actual"]; // Por defecto, conserva la imagen actual

            if (isset($_FILES["imagen_servicio"]["tmp_name"])) {
                // Directorio donde se guardan las imágenes
                $directorio = "vistas\assets\img";

                // Generar un nombre único para la nueva imagen
                $nombreImagen = uniqid() . "-" . basename($_FILES["imagen_servicio"]["name"]);
                $rutaImagen = $directorio . $nombreImagen;

                if (move_uploaded_file($_FILES["imagen_servicio"]["tmp_name"], $rutaImagen)) {
                    // La imagen se subió correctamente
                } else {
                    $rutaImagen = $_POST["imagen_actual"]; // Usa la imagen actual si no se pudo subir
                }
            }
    
            // Preparar los datos para actualizar en la base de datos
            $datos = array(
                "idServicio" => $_POST["idServicio"],
                "nombre_servicio" => $_POST["nombre_servicio"],
                "costo_servicio" => $_POST["costo_servicio"],
                "tipo" => $_POST["tipo"],
                "comentario" => $_POST["comentario"],
                "imagen_servicio" => $rutaImagen
            );
    
            // Llamar al modelo para actualizar
            $respuesta = ModeloServicios::mdlEditarServicio($tabla, $datos);
    
            // Verificar la respuesta
            if ($respuesta == "ok") {
                echo '<script>
                    fncSweetAlert(
                        "success",
                        "El servicio se actualizó correctamente",
                        "' . ControladorPlantilla::url() . 'servicios"
                    );
                    </script>';
            } else {
                echo '<script>
                    fncSweetAlert(
                        "error",
                        "Ocurrió un error al actualizar el servicio",
                        "' . ControladorPlantilla::url() . 'servicios"
                    );
                    </script>';
            }
        }
    }
    static public function ctrEliminarServicio()
    {

        if (isset($_GET["idServicio"])) {

            $url = ControladorPlantilla::url() . "servicios";
            $tabla = "servicio";
            $dato = $_GET["idServicio"];
            

            $respuesta = ModeloServicios::mdlEliminarServicio($tabla, $dato);
            
            if ($respuesta == "ok"
            ) {
                echo '<script>
                 fncSweetAlert("success", "El servicio se eliminó correctamente", "' . $url . '");
                 </script>';
            }
        }
    }
}