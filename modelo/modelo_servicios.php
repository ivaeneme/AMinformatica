<?php
require_once 'conexion.php';

class ModeloServicios
{
    static public function mdlMostrarServicios($item, $valor)
    {
        try {
            if ($item != null) {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM servicio WHERE $item = :$item");
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM servicio;");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return []; // Devuelve un array vacío en caso de error
        }
    }
    static public function mdlAgregarServicio($tabla, $datos)
    {
        try {
            // Consulta para insertar en la tabla correspondiente
            $stmt = Conexion::conectar()->prepare(
                "INSERT INTO $tabla (nombre_servicio, costo_servicio, imagen_servicio, comentario, tipo) 
                 VALUES (:nombre_servicio, :costo_servicio, :imagen_servicio, :comentario, :tipo)"
            );

            // Vincular los parámetros con los datos
            $stmt->bindParam(":nombre_servicio", $datos["nombre_servicio"], PDO::PARAM_STR);
            $stmt->bindParam(":costo_servicio", $datos["costo_servicio"], PDO::PARAM_STR);
            $stmt->bindParam(":imagen_servicio", $datos["imagen_servicio"], PDO::PARAM_STR);
            $stmt->bindParam(":comentario", $datos["comentario"], PDO::PARAM_STR);
            $stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);

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
    static public function mdlEditarServicio($tabla, $datos)
    {
        try {
            $servicio = Conexion::conectar()->prepare("
                UPDATE $tabla 
                SET 
                    nombre_servicio = :nombre_servicio, 
                    costo_servicio = :costo_servicio, 
                    tipo = :tipo,
                    comentario = :comentario,
                    imagen_servicio = :imagen_servicio 
                WHERE idServicio = :idServicio
            ");

            $servicio->bindParam(":nombre_servicio", $datos["nombre_servicio"], PDO::PARAM_STR);
            $servicio->bindParam(":costo_servicio", $datos["costo_servicio"], PDO::PARAM_STR); // Corregido el nombre del parámetro
            $servicio->bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);
            $servicio->bindParam(":comentario", $datos["comentario"], PDO::PARAM_STR);
            $servicio->bindParam(":imagen_servicio", $datos["imagen_servicio"], PDO::PARAM_STR);
            $servicio->bindParam(":idServicio", $datos["idServicio"], PDO::PARAM_INT);

            if ($servicio->execute()) {
                return "ok";
            } else {
                return print_r(Conexion::conectar()->errorInfo());
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    static public function mdlEliminarServicio($tabla, $dato)
    {

        $stmt = Conexion::conectar()->prepare("DELETE  FROM $tabla WHERE idServicio = :idServicio");

        $stmt->bindParam(":idServicio", $dato, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }
}
