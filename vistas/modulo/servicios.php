<?php
if (!isset($_SESSION["id_rol"])) $_SESSION["id_rol"] = 2; // cliente por defecto

if (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {
    $filtro = trim($_GET['buscar']);
    $servicios = ControladorServicios::ctrBuscarServicios($filtro);
} else {
    $servicios = ControladorServicios::ctrMostrarServicio(null, null);
}
?>

<div class="row justify-content-center">
    <div class="col-12 mt-3">
        <h1>Servicios</h1>
        <div class="card">

            <div class="card-header">
                <?php if ($_SESSION["Rol_idRol"] == 1 || $_SESSION["Rol_idRol"] == 4): ?>
                    <a href="agregar_servicio" class="btn btn-info">Agregar servicio</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-4 mt-3 ">
            <!-- Buscador -->
            <form method="GET" action="" class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre " value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button>
                <?php if (isset($_GET['buscar']) && $_GET['buscar'] !== ''): ?>
                    <a href="index.php" class="btn btn-secondary">Limpiar</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php if ($servicios): ?>
    <div class="servicios-container">
        <div class="row justify-content-center">
            <?php foreach ($servicios as $servicio): ?>
                <div class="card" id="cardimagen">
                    <?php if ($servicio['imagen_servicio']): ?>
                        <img class="img-fluid" id="imagenservicio" src="<?php echo $servicio['imagen_servicio']; ?>" alt="Imagen del producto">
                    <?php else: ?>
                        <img class="card-img-top" src="#" alt="Sin imagen">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $servicio["nombre_servicio"] ?></h5>
                        <p class="card-text"><?php echo $servicio["comentario"] ?></p>
                        <p class="card-text"><?php echo '$', $servicio["costo_servicio"] ?></p>
                        <?php if (isset($_SESSION['iniciarSesion']) && $_SESSION["Rol_idRol"] == 2): ?>
                            <form method="POST" action="index.php?controlador=carrito&accion=agregar" class="d-flex flex-column align-items-start">
                                <input type="hidden" name="controlador" value="carrito">
                                <input type="hidden" name="accion" value="agregar">
                                <input type="hidden" name="id" value="<?= $servicio['idServicio'] ?>">
                                <input type="hidden" name="tipo" value="servicio">

                                <input type="hidden" name="cantidad" value="1">

                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-cart-plus"></i> Agregar al carrito
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if ($_SESSION["Rol_idRol"] == 1 || $_SESSION["Rol_idRol"] == 4): ?>
                            <a href="index.php?pagina=editar_servicio&idServicio=<?php echo $servicio["idServicio"] ?>"
                                class="btn btn-warning"><i class="align-middle me-0" data-feather="edit"></i></a>

                            <button class="btn btn-danger btnEliminarServicio" idServicio="<?php echo $servicio["idServicio"]; ?>">
                                <i class="align-middle me-0" data-feather="trash-2"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <p class="text-center">No hay Servicios para mostrar.</p>
<?php endif; ?>

<?php
if ($_SESSION["Rol_idRol"] == 1 || $_SESSION["Rol_idRol"] == 4) {
    $eliminar = new ControladorServicios();
    $eliminar->ctrEliminarServicio();
}
?>