<?php
session_start();
$url = ControladorPlantilla::url();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Am Informatica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />
    <meta name="author" content="Zoyothemes" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="vistas\assets\css\style.css">

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="<?php echo $url; ?>vistas/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Datatables css -->
    <link href="<?php echo $url; ?>vistas/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $url; ?>vistas/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $url; ?>vistas/assets/libs/datatables.net-keytable-bs5/css/keyTable.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $url; ?>vistas/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $url; ?>vistas/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />

    <!-- Vendor -->
    <script src="<?php echo $url; ?>vistas/assets/libs/jquery/jquery.min.js"></script>
    <script src="<?php echo $url; ?>vistas/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $url; ?>vistas/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="<?php echo $url; ?>vistas/assets/libs/node-waves/waves.min.js"></script>
    <script src="<?php echo $url; ?>vistas/assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="<?php echo $url; ?>vistas/assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
    <script src="<?php echo $url; ?>vistas/assets/libs/feather-icons/feather.min.js"></script>

    <!-- Datatables js -->
    <script src="<?php echo $url; ?>vistas/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>

    <!-- dataTables.bootstrap5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?php echo $url; ?>vistas/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="<?php echo $url; ?>vistas/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>


    <!-- Datatable Demo App Js -->
    <script src="<?php echo $url; ?>vistas/assets/js/pages/datatable.init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="<?php echo $url; ?>vistas/assets/js/alerts.js"></script>

    <script src="<?php echo $url; ?>vistas/assets/js/eliminar.js"></script>

</head>

<!-- body start -->

<body data-menu-color="dark" data-sidebar="default">

    <!-- Begin page -->
    <div id="app-layout">

        <!-- Topbar Start -->
        <?php include 'modulo/header.php' ?>
        <!-- end Topbar -->

        <!-- Left Sidebar Start -->
        <?php include 'modulo/menu.php' ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            
            <?php

            if (isset($_GET["pagina"])) {

                $rutas = explode('/', $_GET["pagina"]);

                //echo "<pre>";
                //print_r($rutas);
                //echo "</pre>";
                if (
                    $rutas[0] == "productos" ||
                    $rutas[0] == "agregar_productos" ||
                    $rutas[0] == "editar_productos" ||
                    $rutas[0] == "salir" ||
                    $rutas[0] == "servicios" ||
                    $rutas[0] == "agregar_servicio" ||
                    $rutas[0] == "editar_servicio" ||
                    $rutas[0] == "login" ||
                    $rutas[0] == "agregar_planes" ||
                    $rutas[0] == "editar_planes" ||
                    $rutas[0] == "usuarios" ||
                    $rutas[0] == "agregar_usuarios" ||
                    $rutas[0] == "editar_usuarios" ||
                    $rutas[0] == "agregar_clientes" ||
                    $rutas[0] == "editar_clientes" ||
                    $rutas[0] == "clientes"

                ) {

                    include "vistas/modulo/" . $rutas[0] . ".php";
                    
                } else {

                    include "vistas/modulo/404.php";
                }
            }
            else {
                ?>
                <div class="row justify-content-center">
                    <img src="vistas\assets\img\aminformatica.png" style="width:500px;">
                    <h5 class="text-center">Pirovano 109, Concordia, Entre Rios.</h5>
                    
                </div>
                
                <?php
            }

            ?>
            <br>
            <br>
            
            
            <!-- Footer Start -->
            <?php include 'modulo/footer.php' ?>
            <!-- end Footer -->

        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>


    <!-- App js-->
    <script src="<?php echo $url; ?>vistas/assets/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/392059e7b7.js" crossorigin="anonymous"></script>

</body>

</html>