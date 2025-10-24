
<body class="bg-light">
<div class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-lg p-4">
      <div class="card-body">
        <h3 class="text-center mb-4">Nueva Contraseña</h3>

        <form action="index.php?controlador=usuarios&accion=actualizarContrasenaSMS" method="POST">
          <input type="hidden" name="telefono" value="<?php echo htmlspecialchars($_POST['telefono']); ?>">

          <div class="mb-3">
            <label for="nueva" class="form-label">Nueva contraseña</label>
            <input type="password" class="form-control" id="nueva" name="nueva" required>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-success">Actualizar contraseña</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
