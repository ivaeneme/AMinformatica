<?php

class Conexion
{
 const HOST = "localhost";
 const DBNAME = "metodologia";
 const USER = "root";
 const PASSWORD = "";
 static public function conectar()
 {
 try {
 $link = new PDO("mysql:host=" . self::HOST . ";dbname=" . self::DBNAME, self::USER,
self::PASSWORD);
 $link->exec("set names utf8");
 return $link;
 } catch (PDOException $e) {
 die("Error en la conexión a la base de datos: " . $e->getMessage());
 }
 }
}