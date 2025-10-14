<?php

require_once 'conexion.php';

class ModeloUsuarios
{
    static public function mdlActualizarContrasena($idUsuario, $nuevaContrasena)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE usuarios SET contrasena = :contrasena WHERE id_usuario = :id");
        $stmt->bindParam(":contrasena", $nuevaContrasena, PDO::PARAM_STR);
        $stmt->bindParam(":id", $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
    }
    static public function mdlMostrarUsuarios($item, $valor)
    {
        if ($item != null) {
            try {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM usuarios WHERE $item = :$item");
                //enlace de parametros
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

                $stmt->execute();

                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        } else {

            try {
                $usuarios = Conexion::conectar()->prepare("SELECT * FROM usuarios");
                $usuarios->execute();

                return $usuarios->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        }
    }

    public static function mdlObtenerClientes()
    {
        try {
            $stmt = Conexion::conectar()->prepare("
            SELECT id_usuario, nombre_usuario 
            FROM usuarios 
            WHERE Rol_idRol = 2
        ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }


    public static function mdlBuscarUsuarios($filtro)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
            SELECT * FROM usuarios 
            WHERE nombre_usuario LIKE :filtro OR email LIKE :filtro
        ");
            $likeFiltro = "%$filtro%";
            $stmt->bindParam(':filtro', $likeFiltro, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }


    public static function mdlAgregarUsuario($nombre_usuario, $email, $contrasena, $rol_id)
    {
        try {
            $conexion = Conexion::conectar();
            $stmt = $conexion->prepare("
            INSERT INTO usuarios (nombre_usuario, email, contrasena, Rol_idRol)
            VALUES (:nombre_usuario, :email, :contrasena, :rol_id)
        ");

            $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
            $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $conexion->lastInsertId(); // ✅ devolvemos el ID del nuevo usuario
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }


    public static function mdlEditarUsuarios($tabla, $datos)
    {
        try {
            if (!empty($datos["contrasena"])) {
                $sql = "UPDATE $tabla SET nombre_usuario = :nombre, email = :email, contrasena = :contrasena, Rol_idRol = :rol WHERE id_usuario = :id";
            } else {
                $sql = "UPDATE $tabla SET nombre_usuario = :nombre, email = :email, Rol_idRol = :rol WHERE id_usuario = :id";
            }

            $stmt = Conexion::conectar()->prepare($sql);

            $stmt->bindParam(":nombre", $datos["nombre_usuario"], PDO::PARAM_STR);
            $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
            $stmt->bindParam(":rol", $datos["Rol_idRol"], PDO::PARAM_INT);
            $stmt->bindParam(":id", $datos["id_usuario"], PDO::PARAM_INT);

            if (!empty($datos["contrasena"])) {
                $stmt->bindParam(":contrasena", $datos["contrasena"], PDO::PARAM_STR);
            }

            return $stmt->execute() ? "ok" : "error";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }



    public static function mdlObtenerRoles($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public static function mdlAgregarVendedor($idUsuario)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
            INSERT INTO vendedor (Usuario_idUsuario) VALUES (:id)
        ");
            $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function mdlAgregarTecnico($idUsuario)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
            INSERT INTO tecnico (Usuario_idUsuario) VALUES (:id)
        ");
            $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function mdlVerificarEmail($email)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT idUsuario FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve false si no hay coincidencia
        } catch (PDOException $e) {
            return false;
        }
    }


    static public function correoExiste($conn, $correo)
    {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$correo]);
        return $stmt->fetchColumn() > 0;
    }

    static public function mdlEliminarUsuarios($tabla, $valor)
    {   

        try {
            $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_usuario = :id_usuario");
            $stmt->bindParam(":id_usuario", $valor, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            // Esto te ayuda a depurar errores de clave foránea u otros
            return "Error al eliminar: " . $e->getMessage();
        }
    }

}
