<body class="bg-light">

  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-lg border-0 p-4">
        <div class="card-body">
          
          <div class="text-center mb-4">
            <i class="bi bi-envelope-lock-fill text-primary" style="font-size: 3rem;"></i>
            <h3 class="mt-2">Recuperar Contraseña</h3>
            <p class="text-muted mb-0">
              Ingresá tu correo electrónico y te enviaremos un código de verificación.
            </p>
          </div>

          <form action="index.php?controlador=usuarios&accion=recuperarContrasena" method="POST" autocomplete="off">
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Correo electrónico</label>
              <input 
                type="email" 
                class="form-control" 
                name="email" 
                id="email" 
                placeholder="ejemplo@correo.com" 
                required
              >
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary fw-semibold py-2">
                <i class="bi bi-send-fill me-1"></i> Enviar código
              </button>
            </div>
          </form>

          <div class="mt-4 text-center">
            <a href="index.php?pagina=login" class="text-decoration-none text-secondary">
              <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>

</body>
