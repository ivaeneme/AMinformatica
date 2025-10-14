<?php
if (!isset($_SESSION["Rol_idRol"])) $_SESSION["Rol_idRol"] = 2; // cliente por defecto

if (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {
    $filtro = trim($_GET['buscar']);
    $productos = ControladorProductos::ctrBuscarProductos($filtro);
} else {
    $productos = ControladorProductos::ctrMostrarProductos(null, null);
}

?>

<div class="row">
    <div class="col-12 mt-3">
        <h1>Mercaderia</h1>
        <div class="card">

            <div class="card-header">
                <?php if ($_SESSION["Rol_idRol"] == 1 || $_SESSION["Rol_idRol"] == 4): ?>
                    <a href="agregar_productos" class="btn btn-info">Agregar mercaderia</a>
                <?php endif; ?>
            </div><!-- end card header -->

        </div>
        <div class="col-4 mt-3">
            <!-- Buscador -->
            <form method="GET" action="" class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o marca..." value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button>
                <?php if (isset($_GET['buscar']) && $_GET['buscar'] !== ''): ?>
                    <a href="index.php" class="btn btn-secondary">Limpiar</a>
                <?php endif; ?>
            </form>
        </div>

    </div>
</div>

<?php if ($productos): ?>
    <div class="productos-container">
        <div class="row justify-content-center">
            <?php foreach ($productos as $producto): ?>
                <div class="card" id="cardimagen">
                    <?php if ($producto['imagen_mercaderia']): ?>
                        <img class="img-fluid" id="imagenproducto" src="<?php echo $producto['imagen_mercaderia']; ?>" alt="Imagen del producto">
                    <?php else: ?>
                        <img class="card-img-top" src="#" alt="Sin imagen">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $producto["nombre_mercaderia"] ?></h5>
                        <i card-text><?php echo $producto["marca"] ?></i>
                        <p class="card-text"><?php echo '$', $producto["costo_mercaderia"] ?>
                            <?php if ($_SESSION["Rol_idRol"] == 2 && isset($_SESSION["id_usuario"])): ?>
                                <?php if ($producto['stock_mercaderia'] > 0): ?>
                        <form method="POST" action="index.php?controlador=carrito&accion=agregar" class="d-flex flex-column align-items-start">
                            <input type="hidden" name="id" value="<?= $producto['idMercaderia'] ?>">
                            <input type="hidden" name="tipo" value="producto">

                            <label for="cantidad_<?= $producto['idMercaderia'] ?>">Cantidad:</label>
                            <input
                                type="number"
                                name="cantidad"
                                id="cantidad_<?= $producto['idMercaderia'] ?>"
                                value="1"
                                min="1"
                                max="<?= $producto['stock_mercaderia'] ?>"
                                class="form-control mb-2"
                                style="width: 80px;">

                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-cart-plus"></i> Agregar al carrito
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="badge bg-danger">Sin stock</span>
                    <?php endif; ?>
                <?php endif; ?>





                <?php if ($_SESSION["Rol_idRol"] == 1 || $_SESSION["Rol_idRol"] == 4): ?>
                    <p class="card-text"><?php echo 'Stock: ', $producto["stock_mercaderia"] ?>
                        <br>
                        <br>
                        <a href="index.php?pagina=editar_productos&idMercaderia=<?php echo $producto["idMercaderia"] ?>"
                            class="btn btn-warning"><i class="align-middle me-0" data-feather="edit"></i></a>

                        <button class="btn btn-danger btnEliminarProductos" idMercaderia="<?php echo $producto["idMercaderia"]; ?>">
                            <i class="align-middle me-0" data-feather="trash-2"></i>
                        </button>
                    <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <p class="text-center">No hay Productos para mostrar.</p>
<?php endif; ?>

<?php
if ($_SESSION["Rol_idRol"] == 1 || $_SESSION["Rol_idRol"] == 4) {
    $eliminar = new ControladorProductos();
    $eliminar->ctrEliminarProductos();
}
?>