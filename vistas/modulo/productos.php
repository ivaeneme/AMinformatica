<?php
$productos = ControladorProductos::ctrMostrarProductos(null, null);
if ($productos) {
    foreach ($productos as $producto) {
        // Tu código para mostrar los Productos
    }
} else {
    echo "No hay Productos para mostrar.";
}
?>
<div class="row">
    <div class="col-12 mt-3">
        <h1>Productos</h1>
        <div class="card">

            <div class="card-header">
                <a href="agregar_productos" class="btn btn-info">Agregar producto</a>
            </div><!-- end card header -->
        </div>
    </div>
</div>
<div class="productos-container">
    <div class="row justify-content-center">
        <?php foreach ($productos as $producto): ?>
            <div class="card" id="cardimagen">
                <?php if ($producto['imagen_mercaderia']): ?>
                    <img class="img-fluid" id="imagenproducto" src="<?php echo $producto['imagen_mercaderia']; ?>" alt="Imagen del producto" >
                <?php else: ?>
                    <img class="card-img-top" src="#" alt="Sin imagen">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $producto["nombre_mercaderia"]?></h5>
                    <p class="card-text"><?php echo '$',$producto["costo_mercaderia"]?></p>
                    <a href="index.php?pagina=editar_productos&idMercaderia=<?php echo $producto["idMercaderia"] ?>"
                    class="btn btn-warning"><i class="align-middle me-0" data-feather="edit"></i></a>

                    <button class="btn btn-danger btnEliminarProductos" idMercaderia="<?php echo $producto["idMercaderia"]; ?>"><i class="align-middle me-0" data-feather="trash-2"></i></button>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php 
$eliminar = new ControladorProductos();
$eliminar->ctrEliminarProductos();
?>
