<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Establecer nueva contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg p-4">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Nueva Contraseña</h3>
                <p class="text-muted text-center">Establece tu nueva contraseña</p>

                <form action="procesar_nueva_contraseña.php" method="POST">
                    <!-- Puedes incluir el token como campo oculto -->
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

                    <div class="mb-3">
                        <label for="nueva_contraseña" class="form-label">Nueva contraseña</label>
                        <input type="password" class="form-control" id="nueva_contraseña" name="nueva_contraseña" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_contraseña" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Guardar nueva contraseña</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="login.php" class="text-decoration-none">Volver al inicio de sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
