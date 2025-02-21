<?php
$servicios = ControladorServicios::ctrMostrarServicio(null, null);
if ($servicios) {
    foreach ($servicios as $servicio) {
        // Tu código para mostrar los Servicios
    }
} else {
    echo "No hay Servicios para mostrar.";
}
?>
<div class="row">
    <div class="col-12 mt-3">
        <h1>Servicios</h1>
        <div class="card">

            <div class="card-header">
                <a href="agregar_servicio" class="btn btn-info">Agregar servicio</a>
            </div><!-- end card header -->
        </div>
    </div>
    <img class="mb-3" src="vistas\assets\img\Banner-soporte-tecnico.jpg" alt="">
</div>
<div class="productos-container">
    <div class="row justify-content-center">
        <?php foreach ($servicios as $servicio): ?>
            <div class="card" id="cardimagen">
                <?php if ($servicio['imagen_servicio']): ?>
                    <img class="img-fluid" id="imagenproducto" src="<?php echo $servicio['imagen_servicio']; ?>" alt="Imagen del servicio$servicio">
                <?php else: ?>
                    <img class="card-img-top" src="#" alt="Sin imagen">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $servicio["nombre_servicio"] ?></h5>
                    <b><?php echo '$', $servicio["costo_servicio"] ?></b>
                    <p class="card-text"><?php echo $servicio["comentario"] ?></p>
                    <a href="index.php?pagina=editar_servicio&idServicio=<?php echo $servicio["idServicio"] ?>"
                        class="btn btn-warning"><i class="align-middle me-0" data-feather="edit"></i></a>

                    <button class="btn btn-danger btnEliminarServicio" idServicio="<?php echo $servicio["idServicio"]; ?>"><i class="align-middle me-0" data-feather="trash-2"></i></button>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
$eliminar = new ControladorServicios();
$eliminar->ctrEliminarServicio();
?>