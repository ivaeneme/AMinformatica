<?php if (!isset($_SESSION["Rol_idRol"])) $_SESSION["Rol_idRol"] = 2; ?>

<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="index.php" class="img-fluid">
                    <span class="logo-sm">
                        <img src="" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="vistas/assets/img/aminformatica.png" alt="" height="80">
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title">Menu</li>

                <!-- Todos los roles pueden ver productos -->
                <li>
                    <a href="productos">
                        <i class="fa-solid fa-gamepad"></i>
                        <span> Productos </span>
                    </a>
                </li>

                <!-- Todos los roles pueden ver servicios -->
                <li>
                    <a href="servicios">
                        <i class="fa-solid fa-wrench"></i>
                        <span> Servicios </span>
                    </a>
                </li>

                <!-- Clientes: solo vendedor y admin -->
                <?php if ($_SESSION["Rol_idRol"] == 1 || $_SESSION["Rol_idRol"] == 4): ?>
                    <li>
                        <a href="clientes">
                            <i class="fa-solid fa-circle-user"></i>
                            <span> Clientes </span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Tecnico: servicios pendientes -->
                <?php if ($_SESSION["Rol_idRol"] == 3): ?>
                    <li>
                        <a href="index.php?controlador=carrito&accion=tareasTecnico">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <span> Servicios Pendientes </span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Admin: Usuarios -->
                <?php if ($_SESSION["Rol_idRol"] == 4): ?>
                    <li>
                        <a href="usuarios">
                            <i class="fa-solid fa-users-gear"></i>
                            <span> Usuarios </span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Carrito solo visible para clientes -->
                <?php if (
                    isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok" &&
                    isset($_SESSION["Rol_idRol"]) && $_SESSION["Rol_idRol"] == 2
                ): ?>
                    <?php
                    $carritoCantidad = isset($_SESSION['carrito']) ? array_sum(array_column($_SESSION['carrito'], 'cantidad')) : 0;
                    ?>
                    <li>
                        <a href="index.php?controlador=carrito&accion=ver">
                            <i class="fa fa-shopping-cart"></i>
                            <span> Mi Carrito (<?php echo $carritoCantidad; ?>)</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- historial de presupuesto del cliente -->
                <?php if (
                    isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] === "ok" &&
                    isset($_SESSION["Rol_idRol"]) && $_SESSION["Rol_idRol"] == 2
                ): ?>
                    <li>
                        <a href="index.php?controlador=carrito&accion=historial">
                            <i class="fa fa-file-invoice"></i>
                            <span>Mis presupuestos</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- presupuestos visibles para el vendedor o admin -->
                <?php if (
                    isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] === "ok" &&
                    in_array($_SESSION["Rol_idRol"], [1, 4]) // 1: vendedor, 4: admin
                ): ?>
                    <li>
                        <a href="index.php?controlador=carrito&accion=gestionar">
                            <i class="fa fa-file-alt"></i>
                            <span>Presupuestos</span>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>