<?php

class ControladorUsuarios
{

    static public function ctrIngresoUsuario()
    {
        if (isset($_POST["email"]) && isset($_POST["contrasena"])) {

            // Validar el formato del correo electrónico
            if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST["email"])) {

                $item = "email";
                $valor = strtolower(trim($_POST["email"]));

                // Buscar usuario en la base de datos
                $respuesta = ModeloUsuarios::mdlMostrarUsuarios($item, $valor);
                $usuarios= ModeloUsuarios::mdlMostrarUsuarios(null,null);
                foreach ($usuarios as $usuario) {
                    if (!password_get_info($usuario["contrasena"])["algo"]) {
                        // Encripta la contraseña solo si no está encriptada
                        $hash = password_hash($usuario["contrasena"], PASSWORD_BCRYPT);
            
                        ModeloUsuarios::mdlActualizarContrasena($usuario["id_usuario"], $hash);
                        echo "Contraseña del usuario con ID " . $usuario["id_usuario"] . " actualizada.<br>";
                    }
                }


                if ($respuesta) {
                    // Verificar la contraseña
                    if (password_verify(trim($_POST["contrasena"]), $respuesta["contrasena"])) {

                        echo '<script>
                        fncSweetAlert("loading", "Ingresando..", "")
                        </script>';

                        // Iniciar sesión
                        $_SESSION["iniciarSesion"] = "ok";
                        $_SESSION["id_usuario"] = $respuesta["id_usuario"];
                        $_SESSION["nombre_usuario"] = $respuesta["nombre_usuario"];
                        $_SESSION['Rol_idRol'] = $respuesta['Rol_idRol']; // Este es el ID del rol
                        $_SESSION['nombre_rol'] = $respuesta['nombre_rol']; // Nombre descriptivo del rol

                        echo '<script>
                        window.location = "' . ControladorPlantilla::url() . 'productos ";
                        </script>';
                    } else {
                        // Contraseña incorrecta
                        echo '<div class="alert alert-danger mt-3" role="alert">Contraseña incorrecta.</div>';
                        echo 'Contraseña ingresada: ' . trim($_POST["contrasena"]) . '<br>';
                        echo 'Hash almacenado: ' . $respuesta["contrasena"] . '<br>';

                    }
                } else {
                    // Usuario no encontrado
                    echo '<div class="alert alert-danger mt-3" role="alert">El correo no está registrado.</div>';
                }
            } else {
                // Formato de correo inválido
                echo '<div class="alert alert-danger mt-3" role="alert">Formato de correo electrónico inválido.</div>';
            }
        }
    }
    static public function ctrMostrarUsuarios($item,$valor){
        $respuesta = ModeloUsuarios::mdlMostrarUsuarios($item, $valor);
        return $respuesta;
    }
}
