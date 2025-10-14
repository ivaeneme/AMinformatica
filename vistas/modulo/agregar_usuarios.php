<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Agregar Nuevo Usuario</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="index.php?controlador=usuarios&accion=agregarUsuarios">
                
                <!-- Nombre de usuario -->
                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Nombre de usuario</label>
                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>

                <!-- Rol -->
                <div class="mb-3">
                    <label for="Rol_idRol" class="form-label">Rol</label>
                    <select class="form-select" id="Rol_idRol" name="Rol_idRol" required>
                        <option value="">Seleccione un rol</option>
                        <option value="1">Vendedor</option>
                        <option value="2">Cliente</option>
                        <option value="3">Tecnico</option>
                        <option value="4">Admin</option>
                    </select>
                </div>

                <!-- Botón de envío -->
                <button type="submit" class="btn btn-success">Guardar usuario</button>
                <a href="index.php?pagina=usuarios" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
