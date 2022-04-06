<?php
    session_start();
    if (isset($_SESSION['logueado']) && $_SESSION['logueado']) {
        header("Location: dashboard.php");
    }

    include "include/conexion.php";
?>
 
<!DOCTYPE html>
<html lang="es-CO">
    <?php include('include/head.php'); ?>
    <head>
        <style>
        .btn-primary, .wizard > .actions a {
            color: #fff;
            background-color: <?= $color;?>;
            border-color: <?= $color;?>;
        }
        .btn-primary:hover, .wizard > .actions a:hover {
            color: #fff;
            background-color: #333;
            border-color: #333;
        }
        .content-wrapper {
            background: <?= $color;?>;
        }
        .text-primary, .list-wrapper .completed .remove {
            color: <?=$color;?> !important;
        }
        </style>
    </head>

    <body>
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper d-flex align-items-center auth px-0">
                    <div class="row w-100 mx-0">
                        <div class="col-lg-4 mx-auto">
                            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                                <div class="brand-logo text-center mb-0">
                                    <img src="images/logo.png" alt="logo">
                                </div>
                                <h4 class="text-center mt-4">Bienvenido a <?=$title;?></h4>
                                <form method="post" action="login.php" class="pt-3">
                                    <div class="form-group">
                                        <input type="number" class="form-control form-control-lg" id="user" placeholder="Identificación" name="user" min="0">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-lg" id="pass" placeholder="Contraseña" name="pass">
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">INICIAR SESIÓN</button>	 
                                    </div>
                                    <div class="my-2 d-flex justify-content-between align-items-center text-center">
                                        <center><a href="reset-contrasena.php" class="auth-link text-primary">¿Olvidó su contraseña?</a></center>
                                    </div>
                                    <div class="text-center mt-4 font-weight-light">
                                        <strong>¿No tiene cuenta en <?=$title;?>?</strong><br><a href="register.php" class="text-primary">Asociar Contrato aquí</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <script src="js/off-canvas.js"></script>
        <script src="js/hoverable-collapse.js"></script>
        <script src="js/template.js"></script>
        <script src="js/settings.js"></script>
        <script src="js/todolist.js"></script>
    </body>
</html>