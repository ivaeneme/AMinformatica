<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg p-4">
            <div class="card-body">
                <h3 class="text-center mb-4">Cambiar Contraseña</h3>

                <form action="index.php?controlador=usuarios&accion=cambiarContrasena" method="POST">
                    <div class="mb-3">
                        <label for="contrasena_actual" class="form-label">Contraseña actual</label>
                        <input type="password" class="form-control" id="contrasena_actual" name="contrasena_actual" required>
                    </div>

                    <div class="mb-3">
                        <label for="nueva_contrasena" class="form-label">Nueva contraseña</label>
                        <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_contrasena" class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="index.php?pagina=perfil" class="text-decoration-none">Volver al perfil</a>
                </div>
            </div>
        </div>
    </div>
</div>