<?php
 class ControladorPlantilla{
   public function ctrMostrarPlantilla (){
        include 'vistas/plantilla.php';
    }
    static public function url(){
        return 'http://localhost/aminformatica/';
    }
 }