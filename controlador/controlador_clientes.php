<?php
class ControladorClientes
{
    static public function ctrMostrarClientes($item, $valor)
    {
        $respuesta = ModeloClientes::mdlMostrarClientes($item, $valor);
        return $respuesta;
    }
    static public function ctrAgregarClientes()
    {
        if (isset($_POST["nombre_cliente"])) {
    
            $tabla = "clientes";
    
            $datos = array(
                "nombre_cliente" => $_POST["nombre_cliente"],
                "dni_cliente" => $_POST["dni_cliente"],
                "correo_cliente" => $_POST["correo_cliente"],
                "telefono_cliente" => $_POST["telefono_cliente"],
                "fecha_nacimiento" => $_POST["fecha_nacimiento"],
                "fecha_creacion" => $_POST["fecha_creacion"]
            );
    
            $url = ControladorPlantilla::url() . "clientes";
            $respuesta = ModeloClientes::mdlAgregarCliente($tabla, $datos);
    
            if ($respuesta == "ok") {
                echo '<script>
                    fncSweetAlert(
                        "success",
                        "El cliente se agregó correctamente",
                        "' . $url . '"
                    );
                    </script>';
            } else {
                echo '<script>
                    fncSweetAlert(
                        "error",
                        "Hubo un error al agregar el cliente",
                        "' . $url . '"
                    );
                    </script>';
            }
        }
    }

    static public function ctrEditarClientes()
    {
        $tabla = "clientes";
    
        if (isset($_POST["idCliente"])) {

    
            // Preparar los datos para actualizar en la base de datos
            $datos = array(
                "idCliente" => $_POST["idCliente"],
                "nombre_cliente" => $_POST["nombre_cliente"],
                "dni_cliente" => $_POST["dni_cliente"],
                "correo_cliente" => $_POST["correo_cliente"],
                "telefono_cliente" => $_POST["telefono_cliente"],
                "fecha_nacimiento" => $_POST["fecha_nacimiento"]
            );
    
            // Llamar al modelo para actualizar
            $respuesta = ModeloClientes::mdlEditarCliente($tabla, $datos);
    
            // Verificar la respuesta
            if ($respuesta == "ok") {
                echo '<script>
                    fncSweetAlert(
                        "success",
                        "El cliente se actualizó correctamente",
                        "' . ControladorPlantilla::url() . 'clientes"
                    );
                    </script>';
            } else {
                echo '<script>
                    fncSweetAlert(
                        "error",
                        "Ocurrió un error al actualizar el cliente",
                        "' . ControladorPlantilla::url() . 'clientes"
                    );
                    </script>';
            }
        }
    }
    static public function ctrEliminarCliente()
    {

        if (isset($_GET["idCliente"])) {

            $url = ControladorPlantilla::url() . "clientes";
            $tabla = "clientes";
            $dato = $_GET["idCliente"];

            $respuesta = ModeloClientes::mdlEliminarCliente($tabla, $dato);

            if ($respuesta == "ok"
            ) {
                echo '<script>
                 fncSweetAlert("success", "El cliente se eliminó correctamente", "' . $url . '");
                 </script>';
            }
        }
    }
    
    
}
