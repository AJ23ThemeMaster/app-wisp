<?php
    include "include/conexion.php";

    $sql = "SELECT * FROM empresas WHERE id = '1'";
    $query = mysqli_fetch_assoc(mysqli_query($con, $sql));
    $actualizar = $query['datos_app'];
?>

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="./"><img src="images/logo.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="./"><img src="images/logo.png" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-search d-none d-lg-block">
                <div class="input-group d-none">
                    <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                        <span class="input-group-text" id="search">
                            <i class="icon-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
                </div>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <b><?= utf8_encode($nombre); ?></b>
                    <img src="./images/no-user-image.png" alt="profile"/>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <?php
                        if($actualizar){
                        echo '<a class="dropdown-item" href="gestion-usuario.php"><i class="fas fa-user-edit text-primary"></i>Actualizar Datos</a>';
                        }
                    ?>
                    <a class="dropdown-item" href="logout.php">
                        <i class="fas fa-power-off text-primary"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
    </div>
</nav>