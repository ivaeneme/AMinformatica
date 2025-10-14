<?php
session_start();

// Requiere todos los archivos necesarios
require_once 'controlador/controlador_plantilla.php';

require_once 'controlador/controlador_productos.php';
require_once 'modelo/modelo_productos.php';

require_once 'controlador/controlador_usuarios.php';
require_once 'modelo/modelo_usuarios.php';

require_once 'controlador/controlador_servicios.php';
require_once 'modelo/modelo_servicios.php';

require_once "controlador/controlador_clientes.php";
require_once 'modelo/modelo_clientes.php';

require_once 'controlador/controlador_carrito.php';
require_once 'modelo/modelo_carrito.php';

require_once 'controlador/controlador_factura.php';

// 🔀 Ruteo por GET
$controlador = $_GET['controlador'] ?? 'plantilla';
$accion = $_GET['accion'] ?? 'mostrar';

switch ($controlador) {
    case 'carrito':
        $carrito = new ControladorCarrito();
        if ($accion === 'agregar') {
            $carrito->agregar();
            exit;
        } elseif ($accion === 'borrar') {
            $carrito->borrar();
            exit;
        }
        break;

    case 'usuarios':
        if ($accion === 'eliminar') {
            ControladorUsuarios::ctrEliminarUsuarios();
            exit;
        } elseif ($accion === 'agregarUsuarios') {
            ControladorUsuarios::ctrAgregarUsuarios();
            exit;
        } elseif ($accion === 'editarUsuario') {
            ControladorUsuarios::ctrEditarUsuarios();
            exit;
        }
        break;

}


$plantilla = new ControladorPlantilla();
$plantilla->ctrMostrarPlantilla();
