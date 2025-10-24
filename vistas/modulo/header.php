<div class="topbar-custom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <!-- Botón del menú -->
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
            </ul>

            <!-- Menú del usuario -->
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li class="dropdown notification-list topbar-dropdown">
                    <?php if (isset($_SESSION["nombre_usuario"])): ?>
                        <!-- Usuario logueado -->
                        <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="pro-user-name ms-1">
                                Hola, <?php echo htmlspecialchars($_SESSION["nombre_usuario"]); ?>! <i class="fa-solid fa-angle-down"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                            <div class="dropdown-divider"></div>
                            <?php
                            if (isset($_SESSION['Rol_idRol'])) {
                                switch ($_SESSION['Rol_idRol']) {
                                    case 1: // Vendedor
                                        echo '<p class="dropdown-item">Hola, soy vendedor.</p>';
                                        break;
                                    case 2: // Cliente
                                        echo '<p class="dropdown-item">Hola, soy cliente.</p>';
                                        break;
                                    case 3: // Técnico
                                        echo '<p class="dropdown-item">Hola, soy técnico.</p>';
                                        break;
                                    default:
                                        echo '<p class="dropdown-item">Hola, soy admin.</p>';
                                        break;
                                }
                            }
                            ?>
                            <a href="<?php echo $url; ?>cambiarcontrasena" class="dropdown-item notify-item">
                                <span>Cambiar contraseña <i class="fa-solid fa-square-pen"></i></span>
                            </a>
                            <a href="<?php echo $url; ?>salir" class="dropdown-item notify-item">
                                <span>Salir <i class="fa-solid fa-right-from-bracket"></i></span>
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Usuario no logueado -->
                        <a href="<?php echo $url; ?>login" class="nav-link nav-user me-0 text-decoration-none text-muted">
                            <span class="pro-user-name ms-1">Iniciar sesión <i class="fa-solid fa-right-to-bracket"></i></span>
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</div>