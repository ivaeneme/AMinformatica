<?php
require_once 'conexion.php';

class ModeloClientes
{
    static public function mdlMostrarClientes($item, $valor)
    {
        try {
            if ($item != null) {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM clientes WHERE $item = :$item");
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM clientes");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return []; // Devuelve un array vacío en caso de error
        }
    }
    static public function mdlAgregarCliente($tabla, $datos)
    {
        try {
            // Consulta para insertar en la tabla correspondiente
            $stmt = Conexion::conectar()->prepare(
                "INSERT INTO $tabla (nombre_cliente, dni_cliente, correo_cliente, telefono_cliente, fecha_nacimiento, fecha_creacion) 
                 VALUES (:nombre_cliente, :dni_cliente, :correo_cliente, :telefono_cliente, :fecha_nacimiento, :fecha_creacion)"
            );

            // Vincular los parámetros con los datos
            $stmt->bindParam(":nombre_cliente", $datos["nombre_cliente"], PDO::PARAM_STR);
            $stmt->bindParam(":dni_cliente", $datos["dni_cliente"], PDO::PARAM_INT);
            $stmt->bindParam(":correo_cliente", $datos["correo_cliente"], PDO::PARAM_STR);
            $stmt->bindParam(":telefono_cliente", $datos["telefono_cliente"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);
            $stmt->bindParam(":fecha_creacion", $datos["fecha_creacion"], PDO::PARAM_STR);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                return "ok";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    static public function mdlEditarCliente($tabla, $datos)
    {
        try {
            $cliente = Conexion::conectar()->prepare("
                UPDATE $tabla 
                SET 
                    nombre_cliente = :nombre_cliente, 
                    dni_cliente = :dni_cliente,
                    correo_cliente = :correo_cliente, 
                    telefono_cliente = :telefono_cliente, 
                    fecha_nacimiento = :fecha_nacimiento 
                WHERE idCliente = :idCliente
            ");

            $cliente->bindParam(":nombre_cliente", $datos["nombre_cliente"], PDO::PARAM_STR);
            $cliente->bindParam(":dni_cliente", $datos["dni_cliente"], PDO::PARAM_INT); // Corregido el nombre del parámetro
            $cliente->bindParam(":telefono_cliente", $datos["telefono_cliente"], PDO::PARAM_INT);
            $cliente->bindParam(":correo_cliente", $datos["correo_cliente"], PDO::PARAM_STR);
            $cliente->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);
            $cliente->bindParam(":idCliente", $datos["idCliente"], PDO::PARAM_INT);

            if ($cliente->execute()) {
                return "ok";
            } else {
                return print_r(Conexion::conectar()->errorInfo());
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    static public function mdlEliminarCliente($tabla, $dato)
    {

        $stmt = Conexion::conectar()->prepare("DELETE  FROM $tabla WHERE idCliente = :idCliente");

        $stmt->bindParam(":idCliente", $dato, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }
}
