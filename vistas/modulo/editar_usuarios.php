<?php
if (!isset($_SESSION["Rol_idRol"]) || $_SESSION["Rol_idRol"] != 4) {
    echo "<h2 class='text-center text-danger'>Acceso restringido. Solo para administradores.</h2>";
    exit();
}

if (!isset($_GET["id_usuario"])) {
    echo "<h3 class='text-danger'>ID de usuario no proporcionado.</h3>";
    exit();
}

$usuario = ControladorUsuarios::ctrMostrarUsuarios("id_usuario", $_GET["id_usuario"]);
$roles = ControladorUsuarios::ctrObtenerRoles(); // Función que vamos a crear si no existe
?>

<div class="container mt-5">
    <h2>Editar Usuario</h2>
    <form method="POST" action="index.php?pagina=usuarios&accion=editarUsuario">
        <input type="hidden" name="id_usuario" value="<?php echo $usuario["id_usuario"]; ?>">

        <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre_usuario" value="<?php echo $usuario["nombre_usuario"]; ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="email" value="<?php echo $usuario["email"]; ?>" required>
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña (dejar en blanco para mantener)</label>
            <input type="password" class="form-control" name="contrasena">
        </div>
        
        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select name="Rol_idRol" class="form-control" required>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?php echo $rol["idRol"]; ?>" <?php echo $usuario["Rol_idRol"] == $rol["idRol"] ? "selected" : ""; ?>>
                        <?php echo ucfirst($rol["nombre_rol"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="index.php?pagina=usuarios" class="btn btn-secondary">Cancelar</a>
    </form>
</div>