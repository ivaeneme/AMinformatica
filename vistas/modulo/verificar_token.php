<div class="container mt-5" style="max-width: 500px;">
  <div class="card shadow-lg border-0">
    <div class="card-body p-4">
      <h3 class="text-center mb-4">
        <i class="bi bi-shield-lock-fill text-primary me-2"></i>
        Verificar código de recuperación
      </h3>

      <p class="text-muted text-center mb-4">
        Ingresá el <strong>código de 6 dígitos</strong> que enviamos a tu correo electrónico.
      </p>

      <form method="POST" action="index.php?controlador=usuarios&accion=verificarToken" autocomplete="off">
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

        <div class="mb-4">
          <label for="token" class="form-label fw-semibold">Código de verificación</label>
          <input 
            type="text" 
            class="form-control text-center fw-bold fs-5 tracking-widest" 
            name="token" 
            id="token" 
            maxlength="6" 
            minlength="6"
            placeholder="######" 
            required
          >
          <div class="form-text text-center">El código expira en 10 minutos.</div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
          <i class="bi bi-check-circle-fill me-1"></i>
          Verificar código
        </button>
      </form>

      <div class="text-center mt-3">
        <a href="index.php?controlador=usuarios&accion=recuperarContrasena" class="text-decoration-none">
          <i class="bi bi-arrow-left"></i> Volver a recuperar contraseña
        </a>
      </div>
    </div>
  </div>
</div>
