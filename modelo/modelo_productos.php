<?php
require_once 'modelo/conexion.php';
class ModeloProductos
{
    static public function mdlMostrarProductos($item, $valor)
    {
        try {
            if ($item != null) {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM mercaderia WHERE $item = :$item");
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM mercaderia as m INNER JOIN tipomercaderia as t WHERE m.idtipo_mercaderia = t.idtipo_mercaderia;");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return []; // Devuelve un array vacío en caso de error
        }
    }
    
    static public function mdlBuscarProductos($filtro)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
            SELECT * FROM mercaderia AS m
            INNER JOIN tipomercaderia AS t ON m.idtipo_mercaderia = t.idtipo_mercaderia
            WHERE m.nombre_mercaderia LIKE :filtro OR m.marca LIKE :filtro
        ");
            $stmt->bindValue(":filtro", "%" . $filtro . "%", PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    static public function mdlAgregarProducto($tabla, $datos)
    {
        try {
            // Consulta para insertar en la tabla correspondiente
            $stmt = Conexion::conectar()->prepare(
                "INSERT INTO $tabla (idMercaderia, nombre_mercaderia, costo_mercaderia, imagen_mercaderia, idtipo_mercaderia, stock_mercaderia, marca) 
                 VALUES (:idMercaderia, :nombre_mercaderia, :costo_mercaderia, :imagen_mercaderia, :idtipo_mercaderia, :stock_mercaderia, :marca)"
            );

            // Vincular los parámetros con los datos
            $stmt->bindParam(":idMercaderia", $datos["idMercaderia"], PDO::PARAM_INT);
            $stmt->bindParam(":nombre_mercaderia", $datos["nombre_mercaderia"], PDO::PARAM_STR);
            $stmt->bindParam(":costo_mercaderia", $datos["costo_mercaderia"], PDO::PARAM_STR);
            $stmt->bindParam(":imagen_mercaderia", $datos["imagen_mercaderia"], PDO::PARAM_STR);
            $stmt->bindParam(":idtipo_mercaderia", $datos["idtipo_mercaderia"], PDO::PARAM_INT);
            $stmt->bindParam(":stock_mercaderia", $datos["stock_mercaderia"], PDO::PARAM_INT);
            $stmt->bindParam(":marca", $datos["marca"], PDO::PARAM_STR);

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
    static public function mdlObtenerCategorias($tabla)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT idtipo_mercaderia, nombre_tipo FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    static public function mdlEditarProductos($tabla, $datos)
    {
        try {
            $producto = Conexion::conectar()->prepare("
                UPDATE $tabla 
                SET 
                    nombre_mercaderia = :nombre_mercaderia, 
                    costo_mercaderia = :costo_mercaderia, 
                    idtipo_mercaderia = :idtipo_mercaderia, 
                    imagen_mercaderia = :imagen_mercaderia,
                    marca = :marca, 
                    stock_mercaderia = :stock_mercaderia
                WHERE idMercaderia = :idMercaderia
            ");

            $producto->bindParam(":nombre_mercaderia", $datos["nombre_mercaderia"], PDO::PARAM_STR);
            $producto->bindParam(":costo_mercaderia", $datos["costo_mercaderia"], PDO::PARAM_STR); // Corregido el nombre del parámetro
            $producto->bindParam(":idtipo_mercaderia", $datos["idtipo_mercaderia"], PDO::PARAM_INT);
            $producto->bindParam(":imagen_mercaderia", $datos["imagen_mercaderia"], PDO::PARAM_STR);
            $producto->bindParam(":marca", $datos["marca"], PDO::PARAM_STR);
            $producto->bindParam(":stock_mercaderia", $datos["stock_mercaderia"], PDO::PARAM_INT);
            $producto->bindParam(":idMercaderia", $datos["idMercaderia"], PDO::PARAM_INT);


            if ($producto->execute()) {
                return "ok";
            } else {
                return print_r($producto->errorInfo());
            }
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    static public function mdlEliminarProductos($tabla, $dato)
    {

        $stmt = Conexion::conectar()->prepare("DELETE  FROM $tabla WHERE idMercaderia = :idMercaderia");

        $stmt->bindParam(":idMercaderia", $dato, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    public function listarDisponibles()
    {
        $conexion = Conexion::conectar(); // ✅
        $consulta = $conexion->prepare("SELECT * FROM mercaderia WHERE stock_mercaderia > 0");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerPorId($id)
    {
        $conexion = Conexion::conectar(); // ✅
        $consulta = $conexion->prepare("SELECT * FROM mercaderia WHERE idMercaderia = ?");
        $consulta->execute([$id]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarStock($idMercaderia, $cantidad)
    {
        try {
            $pdo = Conexion::conectar();

            // Primero obtener el stock actual
            $stmt = $pdo->prepare("SELECT stock_mercaderia FROM mercaderia WHERE idMercaderia = ?");
            $stmt->execute([$idMercaderia]);
            $stockActual = $stmt->fetchColumn();

            if ($stockActual === false) {
                return "Producto no encontrado";
            }

            if ($stockActual < $cantidad) {
                return "Stock insuficiente";
            }

            // Actualizar stock restando la cantidad
            $stmtUpdate = $pdo->prepare("UPDATE mercaderia SET stock_mercaderia = stock_mercaderia - ? WHERE idMercaderia = ?");
            $stmtUpdate->execute([$cantidad, $idMercaderia]);

            return "ok";
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

}
