<?php
require_once "utils/twilio_sms.php";

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

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["telefono"])) {
            $telefono = trim($_POST["telefono"]);
            $resultado = ModeloUsuarios::mdlGenerarTokenSMS($telefono);

            if ($resultado) {
                $sms = new TwilioSMS();
                $sms->enviarCodigo($telefono, $resultado["token"]);

                echo "<script>
                alert('Se envió un código de recuperación a tu número $telefono');
                window.location = 'index.php?pagina=verificar_codigo';
              </script>";
            } else {
                echo "<script>
                alert('El número no está registrado.');
                window.location = 'index.php?pagina=recuperarcontrasena';
              </script>";
            }
        } else {
            include "vistas/modulo/recuperarcontrasena.php";
        }
    }



    public function verificarCodigoSMS()
    {
        require_once "modelo/modelo_usuarios.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["telefono"]) && isset($_POST["codigo"])) {
            $telefono = trim($_POST["telefono"]);
            $codigo = trim($_POST["codigo"]);

            $usuario = ModeloUsuarios::mdlVerificarTokenSMS($telefono, $codigo);

            if ($usuario) {
                // Código correcto → mostrar formulario para nueva contraseña
                include "vistas/modulo/resetcontrasena_sms.php";
            } else {
                echo "<script>
                alert('El código ingresado no es válido o expiró.');
                window.location = 'index.php?pagina=verificar_codigo';
            </script>";
            }
        } else {
            include "vistas/modulo/verificar_codigo.php";
        }
    }



    public function actualizarContrasenaSMS()
    {
        require_once "modelo/modelo_usuarios.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["telefono"])) {
            $telefono = $_POST["telefono"];
            $nueva = password_hash($_POST["nueva"], PASSWORD_DEFAULT);

            $conexion = Conexion::conectar();
            $stmt = $conexion->prepare("UPDATE usuarios SET contrasena = ? WHERE telefono = ?");
            $stmt->execute([$nueva, $telefono]);

            echo "<script>
            alert('Contraseña actualizada correctamente.');
            window.location = 'index.php?pagina=login';
        </script>";
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
