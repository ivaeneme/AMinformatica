<?php
require_once 'controlador/controlador_plantilla.php';

require_once 'controlador/controlador_productos.php';
require_once 'modelo/modelo_productos.php';

require_once 'controlador/controlador_usuarios.php';
require_once 'modelo/modelo_usuarios.php';

require_once 'controlador/controlador_servicios.php';
require_once 'modelo/modelo_servicios.php';

require_once 'controlador/controlador_clientes.php';
require_once 'modelo/modelo_clientes.php';

require_once 'controlador/controlador_usuarios.php';
require_once 'modelo/modelo_usuarios.php';



$plantilla = new ControladorPlantilla();
$plantilla -> ctrMostrarPlantilla();
?>
