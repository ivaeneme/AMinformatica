<?php
// modelo/modelo_cliente.php
class ModeloClientes
{
    static public function mdlMostrarClientes( $item, $valor)
    {
        if ($item != null) {
            try {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM clientes WHERE $item = :$item");
                //enlace de parametros
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

                $stmt->execute();

                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        } else {

            try {
                $usuarios = Conexion::conectar()->prepare("SELECT * FROM clientes");
                $usuarios->execute();

                return $usuarios->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }
    }

    //Por una cuestion de logica, esta función quedará sin uso//
    static public function mdlAgregarClientes($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre_cliente, dni_cliente, correo_cliente, telefono_cliente, fecha_nacimiento, fecha_creacion, Usuario_idUsuario)
    VALUES (:nombre_cliente, :dni_cliente, :correo_cliente, :telefono_cliente, :fecha_nacimiento, :fecha_creacion, :Usuario_idUsuario)");

        $stmt->bindParam(":nombre_cliente", $datos["nombre_cliente"], PDO::PARAM_STR);
        $stmt->bindParam(":dni_cliente", $datos["dni_cliente"], PDO::PARAM_STR);
        $stmt->bindParam(":correo_cliente", $datos["correo_cliente"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono_cliente", $datos["telefono_cliente"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_creacion", $datos["fecha_creacion"], PDO::PARAM_STR);
        $stmt->bindParam(":Usuario_idUsuario", $datos["Usuario_idUsuario"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            
            return print_r($stmt->errorInfo());
        }

        $stmt = null; 
    }

    static public function correoExiste($conn, $correo)
    {
        $sql = "SELECT idCliente FROM clientes WHERE correo_cliente = :correo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":correo", $correo);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    static public function crearCliente($conn, $datos)
    {
        $sql = "INSERT INTO clientes 
        (nombre_cliente, dni_cliente, correo_cliente, telefono_cliente, fecha_nacimiento, fecha_creacion, Usuario_idUsuario)
        VALUES (:nombre, :dni, :correo, :telefono, :fecha_nac, :fecha_creacion, :usuario_id)";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":nombre", $datos['nombre_cliente']);
        $stmt->bindParam(":dni", $datos['dni_cliente']);
        $stmt->bindParam(":correo", $datos['correo_cliente']);
        $stmt->bindParam(":telefono", $datos['telefono_cliente']);
        $stmt->bindParam(":fecha_nac", $datos['fecha_nacimiento']);
        $stmt->bindParam(":fecha_creacion", $datos['fecha_creacion']);
        $stmt->bindParam(":usuario_id", $datos['Usuario_idUsuario'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ["success" => true];
        } else {
            return ["success" => false, "error" => $stmt->errorInfo()[2]];
        }
    }


    static public function mdlEditarClientes($tabla, $datos)
    {
        try {
            $clientes = Conexion::conectar()->prepare("
                UPDATE $tabla 
                SET 
                    nombre_cliente = :nombre_cliente, 
                    dni_cliente = :dni_cliente, 
                    correo_cliente = :correo_cliente,
                    telefono_cliente = :telefono_cliente,
                    fecha_nacimiento = :fecha_nacimiento 
                WHERE idCliente = :idCliente
            ");

            $clientes->bindParam(":nombre_cliente", $datos["nombre_cliente"], PDO::PARAM_STR);
            $clientes->bindParam(":dni_cliente", $datos["dni_cliente"], PDO::PARAM_STR); // Corregido el nombre del parámetro
            $clientes->bindParam(":correo_cliente", $datos["correo_cliente"], PDO::PARAM_STR);
            $clientes->bindParam(":telefono_cliente", $datos["telefono_cliente"], PDO::PARAM_STR);
            $clientes->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);
            $clientes->bindParam(":idCliente", $datos["idCliente"], PDO::PARAM_INT);

            if ($clientes->execute()) {
                return "ok";
            } else {
                return print_r(Conexion::conectar()->errorInfo());
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    static public function mdlEliminarCliente($tabla, $valor)
    {
        $stmt = Conexion::conectar()->prepare("DELETE  FROM $tabla WHERE idCliente = :idCliente");

        $stmt->bindParam(":idCliente", $valor, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    public static function mdlAsegurarClienteParaUsuario($conn, $usuario)
    {
        // 1. Verificar si ya existe cliente asociado
        $stmt = $conn->prepare("SELECT idCliente FROM clientes WHERE Usuario_idUsuario = ?");
        $stmt->execute([$usuario["id_usuario"]]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            return $cliente["idCliente"];
        }

        // 2. Si no existe, crear cliente básico
        $sql = "INSERT INTO clientes (
                nombre_cliente, dni_cliente, correo_cliente, telefono_cliente,
                fecha_nacimiento, fecha_creacion, Usuario_idUsuario
            ) VALUES (
                :nombre, '', :correo, '', '1900-01-01', NOW(), :usuario_id
            )";

        $stmtInsert = $conn->prepare($sql);
        $stmtInsert->bindParam(":nombre", $usuario["nombre_usuario"]);
        $stmtInsert->bindParam(":correo", $usuario["email"]);
        $stmtInsert->bindParam(":usuario_id", $usuario["id_usuario"]);

        if ($stmtInsert->execute()) {
            return $conn->lastInsertId();
        } else {
            return null; // o lanzar excepción si querés
        }
    }

}
