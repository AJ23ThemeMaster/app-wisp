<?php
    $page = 'B';
    include "include/conexion.php";
    $seccion  = 'Gestiona tu Plan';
    session_start();
    if (isset($_SESSION['logueado']) && $_SESSION['logueado']) {
        $usuario_actual = $_SESSION['username'];
        $cliente_actual = "SELECT usuarios_app.id_cliente, concat_ws(' ', contactos.nombre, contactos.apellido1, contactos.apellido2) as nombre FROM usuarios_app JOIN contactos ON usuarios_app.id_cliente = contactos.id WHERE usuarios_app.user = '$usuario_actual'";
        $result_cliente = mysqli_query($con,$cliente_actual);
        $assoc_cliente  = mysqli_fetch_assoc($result_cliente);
        $cliente        = $assoc_cliente['id_cliente'];
        $nombre         = $assoc_cliente['nombre'];
        
        $planes = "SELECT id, name, price, download, upload FROM planes_velocidad";
        $planes = mysqli_query($con,$planes);
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
                background: <?=$color;?>;
                color: #ffffff;
            }
            .speed{
                color: #ffffff;
            }
            .speed:hover{
                color: #d4d4d4;
            }
            .fa-5x {
                font-size: 7em;
            }
            .modal .modal-dialog .modal-content .modal-header {
                padding: 10px 26px;
            }
            .modal-header .close {
                margin: -20px -26px -25px auto;
            }
            .modal .modal-dialog .modal-content .modal-body {
                padding: 20px 26px 5px;
            }
            .btn-primary, .wizard > .actions a {
                color: #fff;
                background-color: <?=$color;?>;
                border-color: <?=$color;?>;
            }
            .modal .modal-dialog .modal-content .modal-footer {
                padding: 5px;
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
                            <div class="col-md-3 mb-4 stretch-card transparent">
                                <div class="card card-dark-blue">
                                    <div class="card-body">
                                        <p class="mb-3">PLAN CONTRATADO</p>
                                        <p class="fs-20 mb-2"><span id="nombreplan" style="font-size: .5em!important;"></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4 stretch-card transparent">
                                <div class="card card-dark-blue">
                                    <div class="card-body">
                                        <p class="mb-4">VEL. BAJADA</p>
                                        <p class="fs-20 mb-2"><span id="bajadaplan"></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4 stretch-card transparent">
                                <div class="card card-dark-blue">
                                    <div class="card-body">
                                        <p class="mb-4">VEL. SUBIDA</p>
                                        <p class="fs-20 mb-2"><span id="subidaplan"></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4 stretch-card transparent">
                                <div class="card card-dark-blue">
                                    <div class="card-body">
                                        <p class="mb-4">PRECIO</p>
                                        <p class="fs-20 mb-2"><span id="precioplan"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4 stretch-card transparent">
                                <a href="<?=$speedtest;?>" target="_blank" class="speed text-center">
                                    <div class="card card-dark-blue">
                                        <div class="card-body text-center">
                                            <p class="fs-20 mb-2" style="font-size: 1.5em!important;">MIDE TU VELOCIDAD EN NUESTRO SPEEDTEST</p>
                                            <i class="fas fa-tachometer-alt fa-5x"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 mb-4 stretch-card transparent">
                                <a href="javascript:void();" data-toggle="modal" data-target="#modal_plan" class="speed text-center">
                                    <div class="card card-dark-blue">
                                        <div class="card-body text-center">
                                            <p class="fs-20 mb-2" style="font-size: 1.5em!important;">QUIERO CAMBIAR MI PLAN DE VELOCIDAD</p>
                                            <i class="fas fa-sync-alt fa-5x fa-spin"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="modal_plan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">CAMBIO DE PLAN</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group mb-1">
                                        <label for="exampleInputUsername1" class="font-weight-bold">PLAN ACTUAL<br><span id="detalle_plan" class="ml-3"></span><br></label>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1" class="font-weight-bold">PLAN A CAMBIAR</label>
                                        <select class="form-control" name="plan" id="plan">
                                            <option selected disabled>Seleccione</option>
                                            <?php while($plan = mysqli_fetch_array($planes)){ ?>
                                                <option value="<?php echo $plan['id']; ?>">$<?php echo number_format($plan['price'],2,",","."); ?> | <?php echo $plan['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" class="form-control" id="id_cliente" name="id_cliente" value="<?=$cliente;?>">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:solicitar_OTP()" class="btn btn-primary">CONTINUAR</a>
                                    <a href="javascript:cambiarPlan();" class="btn btn-primary d-none">Realizar Cambio</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php include('include/footer.php'); ?>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="modal_otp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">INTRODUZCA EL CÓDIGO OTP</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputUsername1" class="font-weight-bold">INDIQUE EL CÓDIGO OTP</label>
                            <input type="text" class="form-control" id="otp" name="otp" autocomplete="off">
                            <input type="hidden" class="form-control" id="c_otp" name="c_otp">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:validarOTP();" class="btn btn-primary">Validar Código</a>
                    </div>
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
                        
                        document.getElementById('nombreplan').innerHTML = data.plan.plan;
                        document.getElementById('precioplan').innerHTML = '$ '+number_format(data.plan.precio, '2', ',', '.');
                        document.getElementById('subidaplan').innerHTML = data.plan.subida;
                        document.getElementById('bajadaplan').innerHTML = data.plan.bajada;
                        
                        document.getElementById('detalle_plan').innerHTML = data.plan.plan+' | $'+number_format(data.plan.precio, '2', ',', '.');
                        
                        /*if(data.contrato.state == 'enabled'){
                            document.getElementById('stateplan').innerHTML = 'HABILITADO';
                            $('#stateplan').attr('style','color: #fff');
                        }else{
                            document.getElementById('stateplan').innerHTML = 'DESHABILITADO';
                            $('#stateplan').attr('style','color: #fff');
                        }*/
                        //getPlanes(data.contrato.server_configuration_id);
                    }
                });
                return false;
            });
            
            function solicitar_OTP(){
                cargando(true);
                $.ajax({
                    type : 'POST',
                    url  : 'bk_plan.php',
                    data : {usuario:<?=$cliente;?>, option:'OTP'},
                    dataType: 'JSON',
                    success : function(data){
                        cargando(false);
                        if(data.type == 'error'){
                            Swal.fire({
                                icon: data.type,
                                title: data.title,
                                text: data.mensaje,
                            })
                        }else{
                            $('#modal_otp').modal({
                                keyboard: false,
                                backdrop: 'static'
                            });
                            $('#otp').val('');
                            $('#c_otp').val(data.otp);
                            $("#modal_plan").modal('hide');
                        }
                    }
                });
            }
            
            function validarOTP(){
                if($('#otp').val() === $('#c_otp').val()){
                    //$('#modal_otp').modal('hide');
                    cambiarPlan();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'CÓDIGO INVÁLIDO',
                        html: 'Disculpe, el código OTP ingresado no es válido, verifique e intente nuevamente',
                        showConfirmButton: true,
                        timer: 5000
                    });
                }
            }
            
            function cambiarPlan(){
                cargando(true);
                var id_plan = $('#plan').val();
                var id_cliente = <?=$cliente;?>;
                $.ajax({
                    type : 'POST',
                    url  : 'bk_plan.php',
                    data : {id_cliente:id_cliente, id_plan:id_plan},
                    dataType: 'JSON',
                    success : function(data){
                        if(data.success == 'true'){
                            $("#modal_plan").modal('hide');
                            setTimeout(location.reload(), 5000);
                        }
                        cargando(false);
                        Swal.fire({
                            title: data.title,
                            icon: data.icon,
                            showCancelButton: false,
                            showConfirmButton: false,
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Aceptar',
                            timer: 10000
                        });
                    }
                });
                return false;
            }
        </script>
</body>

</html>

