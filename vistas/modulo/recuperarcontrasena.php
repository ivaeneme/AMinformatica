

<body class="bg-light">

    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg p-4">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Recuperar Contraseña</h3>
                    
                <form action="index.php?controlador=usuarios&accion=recuperarContrasena" method="POST">
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Número de celular</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="telefono" 
                            name="telefono" 
                            placeholder="+543454331341" 
                            required>
                        <div class="form-text">
                            Incluí el prefijo internacional. Ejemplo: <strong>+5491122334455</strong>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Enviar código por SMS
                        </button>
                    </div>
                </form>

                    <div class="mt-3 text-center">
                        <a href="index.php?pagina=login" class="text-decoration-none">Volver al inicio de sesión</a>
                    </div>
                    <?php if (isset($_SESSION["alerta"])): ?>
                        <div class="alert alert-<?= $_SESSION["alerta"]["tipo"]; ?> mt-3 text-center">
                            <?= $_SESSION["alerta"]["mensaje"]; ?>
                        </div>
                        <?php unset($_SESSION["alerta"]); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
