
<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-lg p-4">
      <div class="card-body">
        <h3 class="text-center mb-4">Verificar Código SMS</h3>
        <p class="text-muted text-center">
          Ingresá el código de 6 dígitos que recibiste en tu celular.
        </p>

        <form action="index.php?controlador=usuarios&accion=verificarCodigoSMS" method="POST">
          <div class="mb-3">
            <label for="telefono" class="form-label">Número de celular</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required placeholder="+5491122334455">
          </div>

          <div class="mb-3">
            <label for="codigo" class="form-label">Código recibido</label>
            <input type="text" class="form-control" id="codigo" name="codigo" maxlength="6" required>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Verificar código</button>
          </div>
        </form>

        <div class="mt-3 text-center">
          <a href="index.php?pagina=recuperarcontrasena" class="text-decoration-none">Volver</a>
        </div>
      </div>
    </div>
  </div>
</div>


