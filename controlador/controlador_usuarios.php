<?php

class ControladorUsuarios
{
    /*=============================================
INGRESO DE USUARIO
=============================================*/
    static public function ctrIngresoUsuario()
    {
        if (isset($_POST["email"]) && isset($_POST["contrasena"])) {

            if (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email"])) {

                $valor = $_POST["email"];
                $respuesta = ModeloUsuarios::mdlMostrarUsuarios("email", $valor);

                if ($respuesta && password_verify(trim($_POST["contrasena"]), $respuesta["contrasena"])) {

                    // 🔐 Iniciar sesión
                    $_SESSION["iniciarSesion"] = "ok";
                    $_SESSION["id_usuario"] = $respuesta["id_usuario"];
                    $_SESSION["nombre_usuario"] = $respuesta["nombre_usuario"];
                    $_SESSION["Rol_idRol"] = $respuesta["Rol_idRol"];
                    // $_SESSION["nombre_rol"] = $respuesta["nombre_rol"];

                    // 🔁 Si es cliente, buscar su idCliente
                    if ($respuesta["Rol_idRol"] == 2) {
                        $pdo = Conexion::conectar();
                        $idCliente = ModeloClientes::mdlAsegurarClienteParaUsuario($pdo, $respuesta);

                        if ($idCliente) {
                            $_SESSION["id_cliente"] = $idCliente;
                        } else {
                            echo "<script>
                                alert('Error al asociar cliente. Contacte al administrador.');
                                window.location.href = 'index.php';
                            </script>";
                            exit;
                        }
                    }


                    echo '<script>
                        fncSweetAlert("loading", "Ingresando...", "");
                        window.location = "' . ControladorPlantilla::url() . '";
                    </script>';
                } else {
                    echo '<div class="alert alert-danger mt-3" role="alert">Correo o contraseña incorrectos.</div>';
                }
            }
        }
    }

    public function recuperarContrasena()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);

            $usuario = ModeloUsuarios::mdlMostrarUsuarios('email', $email);
            if (!$usuario) {
                echo "<script>alert('El correo no está registrado.');</script>";
                return;
            }

            $token = rand(100000, 999999);
            $expira = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            ModeloUsuarios::mdlGuardarTokenCorreo($email, $token, $expira);


            require_once "utils/correo_helper.php";
            if (CorreoHelper::enviarTokenRecuperacion($email, $usuario['nombre_usuario'], $token)) {
                echo '<script>fncSweetAlert("success", "Se ha enviado un correo con el código de recuperación.", "index.php?controlador=usuarios&accion=verificarToken");</script>';
            } else {
                echo '<script>fncSweetAlert("error", "Error al enviar el correo. Intente más tarde.");</script>';
            }
        } else {
            include "vistas/modulo/recuperarcontrasena.php";
        }
    }

    // ==========================================
    // Paso 2: Verificar token
    // ==========================================
    public function verificarToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $token = trim($_POST['token']);

            $usuario = ModeloUsuarios::mdlMostrarUsuarios('email', $email);

            if (!$usuario) {
                echo "<script>alert('Correo no encontrado.');</script>";
                return;
            }

            // Validar token y expiración
            if ($usuario['token_correo'] === $token && strtotime($usuario['token_correo_expira']) > time()) {
                $_SESSION['recuperar_email'] = $email;
                echo '<script>fncSweetAlert("success", "Código verificado correctamente.", "index.php?controlador=usuarios&accion=nuevaContrasena");</script>';
            } else {
                echo '<script>fncSweetAlert("error", "El código es incorrecto o ha expirado.");</script>';
            }
        } else {
            include "vistas/modulo/verificar_token.php";
        }
    }


    // ==========================================
    // Paso 3: Establecer nueva contraseña
    // ==========================================
    public function nuevaContrasena()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_SESSION['recuperar_email'] ?? null;
            $nueva = $_POST['nueva_contrasena'] ?? '';
            $confirmar = $_POST['confirmar_contrasena'] ?? '';

            if (!$email) {
                echo '<script>fncSweetAlert("warning", "No hay un proceso de recuperación activo.", "index.php?controlador=usuarios&accion=recuperarContrasena");</script>';
                return;
            }

            if ($nueva !== $confirmar) {
                echo '<script>fncSweetAlert("error", "Las contraseñas no coinciden.");</script>';                
                return;
            }

            $hash = password_hash($nueva, PASSWORD_DEFAULT);
            ModeloUsuarios::mdlActualizarContrasenaPorCorreo($email, $hash);

            // Limpiar token
            ModeloUsuarios::mdlGuardarTokenCorreo($email, null, null);
            unset($_SESSION['recuperar_email']);

             echo '<script>fncSweetAlert("success", "Contraseña actualizada correctamente. Ya puedes iniciar sesión.", "index.php?pagina=login");</script>';
        } else {
            include "vistas/modulo/resetcontrasena.php";
        }
    }


    public function cambiarContrasena()
    {
        // Si el formulario fue enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idUsuario = $_SESSION['id_usuario'] ?? null;
            $actual = $_POST['contrasena_actual'] ?? '';
            $nueva = $_POST['nueva_contrasena'] ?? '';
            $confirmar = $_POST['confirmar_contrasena'] ?? '';

            if (!$idUsuario) {
                echo "<script>
                alert('Debes iniciar sesión para cambiar la contraseña.');
                window.location = 'index.php?pagina=login';
            </script>";
                exit;
            }

            // Validar que las contraseñas coincidan
            if ($nueva !== $confirmar) {
                echo "<script>
                alert('Las contraseñas no coinciden.');
                window.location = 'index.php?pagina=cambiarcontrasena';
            </script>";
                exit;
            }

            // Obtener contraseña actual
            $usuario = ModeloUsuarios::mdlMostrarUsuarios("id_usuario", $idUsuario);

            if (!$usuario || !password_verify($actual, $usuario['contrasena'])) {
                echo "<script>
                alert('La contraseña actual es incorrecta.');
                window.location = 'index.php?pagina=cambiarcontrasena';
            </script>";
                exit;
            }

            // Encriptar y actualizar
            $hash = password_hash($nueva, PASSWORD_DEFAULT);
            ModeloUsuarios::mdlActualizarContrasena($idUsuario, $hash);

            echo "<script>
            alert('Contraseña actualizada correctamente.');
            window.location = 'index.php';
        </script>";
        } else {
            include "vistas/modulo/cambiarcontrasena.php";
        }
    }

    static public function ctrMostrarUsuarios($item, $valor)
    {
        $respuesta = ModeloUsuarios::mdlMostrarUsuarios($item, $valor);
        return $respuesta;
    }

    public static function ctrBuscarUsuarios($filtro)
    {
        return ModeloUsuarios::mdlBuscarUsuarios($filtro);
    }



    public static function ctrAgregarUsuarios()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_usuario = trim($_POST['nombre_usuario']);
            $email = trim($_POST['email']);
            $telefono= $_POST['telefono'];
            $contrasena = $_POST['contrasena'];
            $rol_id = $_POST['Rol_idRol'];

            if (!empty($nombre_usuario) && !empty($email) && !empty($contrasena) && !empty($rol_id)) {

                $existeEmail = ModeloUsuarios::mdlVerificarEmail($email);
                if ($existeEmail) {
                    $_SESSION['error'] = "El correo electrónico ya está registrado.";
                    header("Location: index.php?pagina=agregar_usuario");
                    exit;
                }

                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $nuevoId = ModeloUsuarios::mdlAgregarUsuario($nombre_usuario, $email, $telefono, $hash, $rol_id);

                if ($nuevoId) {
                    // ✅ Si es vendedor (rol 1)
                    if ($rol_id == 1) {
                        ModeloUsuarios::mdlAgregarVendedor($nuevoId);
                    }

                    // ✅ Si es técnico (rol 3)
                    if ($rol_id == 3) {
                        ModeloUsuarios::mdlAgregarTecnico($nuevoId);
                    }

                    $_SESSION['success'] = "Usuario agregado correctamente.";
                    header("Location: index.php?pagina=usuarios");
                    exit;
                } else {
                    $_SESSION['error'] = "Error al agregar el usuario.";
                    header("Location: index.php?pagina=agregar_usuario");
                    exit;
                }
            } else {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header("Location: index.php?pagina=agregar_usuario");
                exit;
            }
        }
    }



    static public function ctrEditarUsuarios()
    {

        $tabla = "usuarios";

        if (isset($_POST["id_usuario"])) {
            if (!empty($_POST["contrasena"])) {
                $encriptarcontra = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
            } else {
                $encriptarcontra = $_POST["contrasena"];
            }
            $datos = array(

                "email" => $_POST["email"],
                "contrasena" => $encriptarcontra,
                "nombre_usuario" => $_POST["nombre_usuario"],
                "Rol_idRol" => $_POST["Rol_idRol"],
                "id_usuario" => $_POST["id_usuario"]

            );


            $url = ControladorPlantilla::url() . "usuarios";
            $respuesta = ModeloUsuarios::mdlEditarUsuarios($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
                fncSweetAlert(
                "success",
                "El usuario se actualizó correctamente",
                "' . $url . '"
                );
                </script>';
            }
        }
    }

    static public function ctrEliminarUsuarios()
    {

        if (isset($_GET["id_usuario"]) && is_numeric($_GET["id_usuario"])) {
            $idUsuario = intval($_GET["id_usuario"]);
            $tabla = "usuarios";
            $url = "index.php?pagina=usuarios";


            $respuesta = ModeloUsuarios::mdlEliminarUsuarios($tabla, $idUsuario);

            if ($respuesta === "ok") {
                header("Location: $url");
                exit;
            } else {
                echo "<h3>Error al eliminar: $respuesta</h3>";
                // Para depurar más:
                var_dump($respuesta);
                exit;
            }
        } else {
            echo "<h3>ID de usuario no válido</h3>";
            var_dump($_GET);
            exit;
        }
    }

    public static function ctrObtenerRoles()
    {
        return ModeloUsuarios::mdlObtenerRoles("rol");
    }

    
    static public function correoExiste($conn, $correo)
    {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$correo]);
        return $stmt->fetchColumn() > 0;
    }

}
