<body class="bg-light">

  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-lg border-0 p-4">
        <div class="card-body">

          <div class="text-center mb-4">
            <i class="bi bi-key-fill text-success" style="font-size: 3rem;"></i>
            <h3 class="mt-2">Establecer nueva contraseña</h3>
            <p class="text-muted mb-0">
              Ingresá y confirmá tu nueva contraseña para completar el proceso.
            </p>
          </div>

          <form method="POST" action="index.php?controlador=usuarios&accion=nuevaContrasena" autocomplete="off">
            <div class="mb-3">
              <label for="nueva_contrasena" class="form-label fw-semibold">Nueva contraseña</label>
              <input 
                type="password" 
                class="form-control" 
                name="nueva_contrasena" 
                id="nueva_contrasena" 
                placeholder="Ingresa una nueva contraseña" 
                minlength="6" 
                required
              >
              <div class="form-text">Debe tener al menos 6 caracteres.</div>
            </div>

            <div class="mb-4">
              <label for="confirmar_contrasena" class="form-label fw-semibold">Confirmar contraseña</label>
              <input 
                type="password" 
                class="form-control" 
                name="confirmar_contrasena" 
                id="confirmar_contrasena" 
                placeholder="Repite la contraseña" 
                minlength="6" 
                required
              >
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success fw-semibold py-2">
                <i class="bi bi-check-circle-fill me-1"></i> Actualizar contraseña
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
