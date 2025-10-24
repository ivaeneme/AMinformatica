<?php
$url = ControladorPlantilla::url();
?>

<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%;">
        <div class="text-center mb-4">
            <h2>Crear una nueva cuenta</h2>
            <p>Completá tus datos para registrarte como cliente</p>
        </div>

        <form id="form-registro" action="index.php?pagina=registro_clientes" method="POST">
            <div class="mb-3">
                <label for="nombre_cliente" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" required>
            </div>

            <div class="mb-3">
                <label for="dni_cliente" class="form-label">DNI</label>
                <input type="text" class="form-control" name="dni_cliente" id="dni_cliente" required pattern="\d+">
            </div>

            <div class="mb-3">
                <label for="correo_cliente" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="correo_cliente" id="correo_cliente" required>
            </div>

            <div class="mb-3">
                <label for="telefono_cliente" class="form-label">Teléfono (+54)</label>
                <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" placeholder="ej: +543454222244" required>
            </div>

            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contrasena" id="contrasena" required>
            </div>

            <div class="mb-3">
                <label for="confirmar_contrasena" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" id="confirmar_contrasena" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
                <p class="mt-3">¿Ya tenés una cuenta?
                    <a href="index.php?pagina=login" class="btn btn-sm btn-outline-secondary">Iniciar sesión</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
// Validación de coincidencia de contraseñas
document.getElementById("form-registro").addEventListener("submit", function(e) {
    const pass = document.getElementById("contrasena").value;
    const confirm = document.getElementById("confirmar_contrasena").value;
    if (pass !== confirm) {
        e.preventDefault();
        alert("Las contraseñas no coinciden.");
    }
});
</script>
