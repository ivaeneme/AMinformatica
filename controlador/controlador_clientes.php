<?php
include_once __DIR__ . '/../modelo/conexion.php';
include_once __DIR__ . '/../modelo/modelo_clientes.php';

class ControladorClientes
{
    static public function ctrMostrarClientes($item, $valor)
    {
        return ModeloClientes::mdlMostrarClientes($item, $valor);
    }

    static public function ctrAgregarClientes()
    {
        $tabla = "clientes";
        $dato = array(
            "nombre_cliente" => $_POST["nombre_cliente"],
            "dni_cliente" => $_POST["dni_cliente"],
            "correo_cliente" => $_POST["correo_cliente"],
            "telefono_cliente" => $_POST["telefono_cliente"],
            "fecha_nacimiento" => $_POST["fecha_nacimiento"],
            "fecha_creacion" => $_POST["fecha_creacion"],
        );
        $respuesta = ModeloClientes::mdlAgregarClientes($tabla, $dato);
        // Verificar la respuesta
        if ($respuesta == "ok") {
            echo '<script>
                    fncSweetAlert(
                        "success",
                        "El cliente se agregó correctamente",
                        "' . ControladorPlantilla::url() . 'clientes"
                    );
                    </script>';
        } else {
            echo '<script>
                    fncSweetAlert(
                        "error",
                        "Ocurrió un error al agregar el cliente",
                        "' . ControladorPlantilla::url() . 'clientes"
                    );
                    </script>';
        }
    }

    static public function ctrEliminarClientes()
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

    static public function ctrEditarClientes()
    {
        $tabla = "clientes";
        
        if (isset($_POST['idCliente'])) {
            // Preparar los datos para actualizar en la base de datos
            $datos = array(
                "idCliente" => $_POST["idCliente"],
                "nombre_cliente" => $_POST["nombre_cliente"],
                "dni_cliente" => $_POST["dni_cliente"],
                "correo_cliente" => $_POST["correo_cliente"],
                "telefono_cliente" => $_POST["telefono_cliente"],
                "fecha_nacimiento" => $_POST["fecha_nacimiento"],

            );
            // Llamar al modelo para actualizar
            
            $respuesta = ModeloClientes::mdlEditarClientes($tabla, $datos);
            

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

    static public function ctrCrearClientes()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $conn = Conexion::conectar();

            $correo = $_POST["correo_cliente"];

            // Verificar si el correo ya existe en usuarios
            if (ModeloUsuarios::correoExiste($conn, $correo)) {
                echo "<script>
                    alert('Ya existe una cuenta con ese correo.');
                    window.location.href = '../index.php';
                </script>";
                exit();
            }

            try {
                $conn->beginTransaction();

                // 1. Crear usuario con rol cliente
                $stmtUsuario = $conn->prepare("INSERT INTO usuarios (Rol_idRol, nombre_usuario, email, contrasena) VALUES (?, ?, ?, ?)");
                $stmtUsuario->execute([
                    2, // Rol cliente
                    $_POST["nombre_cliente"],
                    $correo,
                    password_hash(trim($_POST["contrasena"]), PASSWORD_DEFAULT)
                ]);

                $idUsuario = $conn->lastInsertId();

                // 2. Crear cliente vinculado al usuario
                $datos = [
                    "nombre_cliente" => $_POST["nombre_cliente"],
                    "dni_cliente" => $_POST["dni_cliente"],
                    "correo_cliente" => $correo,
                    "telefono_cliente" => $_POST["telefono_cliente"],
                    "fecha_nacimiento" => $_POST["fecha_nacimiento"],
                    "fecha_creacion" => date("Y-m-d H:i:s"),
                    "Usuario_idUsuario" => $idUsuario
                ];

                $resultado = ModeloClientes::crearCliente($conn, $datos);

                if ($resultado["success"]) {
                    $conn->commit();

                    // 🔐 (Opcional) Iniciar sesión automáticamente
                    session_start();
                    $_SESSION["iniciarSesion"] = "ok";
                    $_SESSION["id_usuario"] = $idUsuario;
                    $_SESSION["Rol_idRol"] = 2;
                    $_SESSION["nombre_usuario"] = $_POST["nombre_cliente"];

                    // Buscar el idCliente
                    $stmt = $conn->prepare("SELECT idCliente FROM clientes WHERE Usuario_idUsuario = ?");
                    $stmt->execute([$idUsuario]);
                    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($cliente) {
                        $_SESSION["id_cliente"] = $cliente["idCliente"];
                    }

                    echo '<script>
                    fncSweetAlert(
                        "success",
                        "Cuenta creada con exito",
                        "' . ControladorPlantilla::url() . '"
                    );
                    </script>';;
                    exit();
                } else {
                    $conn->rollBack();
                    throw new Exception($resultado["error"]);
                }
            } catch (Exception $e) {
                $conn->rollBack();
                echo "<script>
                    alert('Error al crear cliente: " . $e->getMessage() . "');
                    window.location.href = 'index.php?pagina=registro_clientes';
                </script>";
            }
        }
    }
}
