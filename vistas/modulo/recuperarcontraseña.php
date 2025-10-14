<!-- recuperarcontraseña.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg p-4">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Recuperar Contraseña</h3>
                <p class="text-muted text-center">Ingresa tu correo electrónico para restablecer tu contraseña</p>
                
                <form action="procesar_recuperacion.php" method="POST">
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required placeholder="tucorreo@ejemplo.com">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Enviar enlace de recuperación</button>
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
