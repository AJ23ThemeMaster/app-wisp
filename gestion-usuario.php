<?php
    $page = 'A';
    include "include/conexion.php";
    $seccion  = 'Gestiona tu Información';
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
                background: <?=$color;?>;
                color: #ffffff;
            }
            .card.card-tale, .card.card-dark-border {
                border: solid 2px <?=$color;?>;
                color: <?=$color;?>;
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
            <?php include('include/navbar.php');?>
            <div class="container-fluid page-body-wrapper">
                <?php include('include/menu.php');?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card card-dark-border">
                                    <div class="card-body text-left">
                                        <h6 class="font-weight-bold">Nombre: </h6>
                                        <h6 class="font-weight-normal mb-3"><span id="nombre_cliente"></span></h6>
                                        <h6 class="font-weight-bold">Identificación: </h6>
                                        <h6 class="font-weight-normal mb-3"><span id="nit_cliente"></span></h6>
                                        <h6 class="font-weight-bold">Celular: </h6>
                                        <h6 class="font-weight-normal mb-3"><span id="tel_cliente"></span></h6>
                                        <h6 class="font-weight-bold">Email: </h6>
                                        <h6 class="font-weight-normal mb-3"><span id="email_cliente"></span></h6>
                                        <h6 class="font-weight-bold">Dirección: </h6>
                                        <h6 class="font-weight-normal"><span id="direccion_cliente"></span></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card card-dark-border">
                                    <div class="card-body text-center">
                                        <a href="javascript:void();" data-toggle="modal" data-target="#modal_cliente" class="speed text-center">
                                            <div class="card card-dark-blue">
                                                <div class="card-body text-center">
                                                    <p class="fs-20 mb-2" style="font-size: 1.5em!important;">QUIERO ACTUALIZAR MIS DATOS</p>
                                                    <i class="fas fa-user-edit fa-5x"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="modal_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">ACTUALIZAR MIS DATOS</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" id="id_cliente" name="id_cliente" value="<?=$cliente;?>">
                                        <div class="row row-group">
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" autocomplete="off" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Identificación</label>
                                                <input type="number" class="form-control" id="nit" name="nit" autocomplete="off" required>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="font-weight-bold">Celular</label>
                                                <input type="number" class="form-control" id="celular" name="celular" autocomplete="off" required>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="font-weight-bold">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" autocomplete="off" required>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label class="font-weight-bold">Dirección</label>
                                                <textarea class="form-control" id="direccion" name="direccion" required  rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:solicitar_OTP()" class="btn btn-primary">Actualizar Datos</a>
                                    <a href="javascript:actualizarDatos();" class="btn btn-primary d-none">Actualizar Datos</a>
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
                        document.getElementById('nombre_cliente').innerHTML = data.cliente.nombre;
                        document.getElementById('nit_cliente').innerHTML = data.cliente.nit;
                        document.getElementById('email_cliente').innerHTML = data.cliente.email;
                        document.getElementById('direccion_cliente').innerHTML = data.cliente.direccion;
                        document.getElementById('tel_cliente').innerHTML = data.cliente.celular;

                        $('#nombre').val(data.cliente.nombre);
                        $('#nit').val(data.cliente.nit);
                        $('#celular').val(data.cliente.celular);
                        $('#email').val(data.cliente.email);
                        $('#direccion').val(data.cliente.direccion);
                    }
                });
                return false;
            });
            
            function solicitar_OTP(){
                cargando(true);
                $.ajax({
                    type : 'POST',
                    url  : 'bk_user.php',
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
                            $("#modal_cliente").modal('hide');
                        }
                    }
                });
            }
            
            function validarOTP(){
                if($('#otp').val() === $('#c_otp').val()){
                    actualizarDatos();
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
            
            function actualizarDatos(){
                cargando(true);
                var id_plan = $('#plan').val();
                var id_cliente = <?=$cliente;?>;
                $.ajax({
                    type : 'POST',
                    url  : 'bk_user.php',
                    data : {
                        id_cliente:id_cliente,
                        nombre:$('#nombre').val(),
                        nit:$('#nit').val(),
                        celular:$('#celular').val(),
                        email:$('#email').val(),
                        direccion:$('#direccion').val(),
                        option:'ACTUALIZAR'
                    },
                    dataType: 'JSON',
                    success : function(data){
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
                        if(data.success == 'true'){
                            $("#modal_cliente").modal('hide');
                            setTimeout(location.reload(), 8000);
                        }
                    }
                });
                return false;
            }
        </script>
</body>

</html>

