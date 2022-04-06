<?php
    $page = 'A';
    include "include/conexion.php";
    session_start();
    if (isset($_SESSION['logueado']) && $_SESSION['logueado']) {
        $usuario_actual = $_SESSION['username'];
        $cliente_actual = "SELECT usuarios_app.id_cliente, contactos.nombre FROM usuarios_app JOIN contactos ON usuarios_app.id_cliente = contactos.id WHERE usuarios_app.user = '$usuario_actual'";
        $result_cliente = mysqli_query($con,$cliente_actual);
        $assoc_cliente  = mysqli_fetch_assoc($result_cliente);
        $cliente        = $assoc_cliente['id_cliente'];
        $nombre         = $assoc_cliente['nombre'];
    }else{
        header("Location: ./");
    }
?>
<!DOCTYPE html>
<html lang="es-CO">
    <?php include('include/head.php'); ?>
    <head>
        <style>
            .card.card-tale:hover, .card.card-dark-blue:hover {
                background: #333;
            }
            .card.card-tale, .card.card-dark-blue {
                background: <?= $color;?>;
                color: #ffffff;
                min-width: 350px;
            }
            .card-people {
                text-align: center;
            }
            .card-people img {
                width: 75%;
            }
        </style>
    </head>
    
    <body>
        <div class="loader"></div>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <?php include('include/navbar.php');?>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <?php include('include/menu.php');?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row">
                            <div class="col-md-12 grid-margin">
                                <div class="row">
                                    <div class="col-12 mb-xl-0">
                                        <h3 class="font-weight-bold">Bienvenido a <?= $title; ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 grid-margin stretch-card">
                                <div class="card tale-bg">
                                    <div class="card-people mt-auto">
                                        <img src="images/dashboard/people.svg" alt="people">
                                        <div class="weather-info">
                                            <div class="d-flex">
                                                <div class="ml-2">
                                                    <h4 class="location font-weight-bold"><span id="nombre_cliente"></span></h4>
                                                    <h6 class="font-weight-normal">(CC) <span id="nit_cliente"></span><br><span id="tel_cliente"></span><br><span id="email_cliente"></span><br><span id="direccion_cliente"></span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 grid-margin transparent">
                                <div class="row">
                                    <div class="col-md-12 mb-4 stretch-card transparent text-center">
                                        <a href="gestion-plan.php">
                                            <div class="card card-tale">
                                                <div class="card-body">
                                                    <h4 class="mb-4">GESTIONA TU PLAN</h4>
                                                    <p class="fs-30 mb-2"><i class="fas fa-chart-line"></i></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-12 mb-4 stretch-card transparent text-center">
                                        <a href="gestion-factura.php">
                                            <div class="card card-dark-blue">
                                                <div class="card-body">
                                                    <h4 class="mb-4">GESTIONA TU FACTURA</h4>
                                                    <p class="fs-30 mb-2"><i class="fas fa-file-invoice-dollar"></i></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-12 mb-4 stretch-card transparent text-center">
                                        <a href="gestion-red.php">
                                            <div class="card card-dark-blue">
                                                <div class="card-body">
                                                    <h4 class="mb-4">GESTIONA TU RED</h4>
                                                    <p class="fs-30 mb-2"><i class="fas fa-network-wired"></i></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php include('include/footer.php'); ?>
                </div>
            </div>
        </div>
        
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <script src="vendors/chart.js/Chart.min.js"></script>
        <script src="vendors/datatables.net/jquery.dataTables.js"></script>
        <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
        <script src="js/dataTables.select.min.js"></script>
        <script src="js/off-canvas.js"></script>
        <script src="js/hoverable-collapse.js"></script>
        <script src="js/template.js"></script>
        <script src="js/settings.js"></script>
        <script src="js/todolist.js"></script>
        <script src="js/dashboard.js"></script>
        <script src="js/Chart.roundedBarCharts.js"></script>
  
        <script type="text/javascript">
            $(document).ready(function(){
            var id_cliente = <?=$cliente;?>;
            $.ajax({
                type : 'GET',
                url  : 'bk_all.php',
                data : {id_cliente:id_cliente},
                dataType: 'JSON',
                success : function(data){
                    document.getElementById('nombre_cliente').innerHTML = data.cliente.nombre;
                    document.getElementById('nit_cliente').innerHTML = data.cliente.nit;
                    document.getElementById('email_cliente').innerHTML = data.cliente.email;
                    document.getElementById('direccion_cliente').innerHTML = data.cliente.direccion;
                    document.getElementById('tel_cliente').innerHTML = data.cliente.celular;
                    
                    /*document.getElementById('nombreplan').innerHTML = data.plan.plan;
                    
                    if(data.wifi){
                        if(data.wifi.status == 1){
                            $('#div_wifi, #div_link').addClass('d-none');
                            $('#div_link_wifi').removeClass('d-none');
                        }else{
                            $('#div_wifi, #div_link').removeClass('d-none');
                            $('#div_link_wifi').addClass('d-none');
                        }
                    }
                    
                    if(data.factura){
                        document.getElementById('codigofactura').innerHTML = 'Nro. '+data.factura.codigo;
                        document.getElementById('preciofactura').innerHTML = '$ '+number_format (((parseFloat(data.factura.precio) * parseFloat(data.factura.impuesto))/100)+parseFloat(data.factura.precio), '2', ',', '.')+' COP';
                        
                        if(data.factura.estatus == 1){
                            document.getElementById('estatusfactura').innerHTML = 'SIN PAGO ASOCIADO';
                        }else{
                            document.getElementById('estatusfactura').innerHTML = 'PAGADA';
                        }
                    }else{
                        document.getElementById('codigofactura').innerHTML = 'NINGUNA';
                        document.getElementById('preciofactura').innerHTML = '';
                    }
                    
                    if(data.red == null){
                        $('#div_link_wifi').addClass('d-none');
                    }else{
                        $('#div_link_wifi').removeClass('d-none');
                        document.getElementById('nombre_red').innerHTML = data.red.red_nueva;
                        document.getElementById('pass_red').innerHTML = data.red.pass_nueva;
                    }*/
                }
            });
            return false;
        });
        </script>
</body>

</html>

