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
            background-color: <?=$color;?>;
            border-color: <?=$color;?>;
        }
        .btn-primary:hover, .wizard > .actions a:hover {
            color: #fff;
            background-color: #333;
            border-color: #333;
        }
        .content-wrapper {
            background: <?=$color;?>;
        }
        .text-primary, .list-wrapper .completed .remove {
            color: <?=$color;?> !important;
        }
        </style>
    </head>

    <body>
        <div class="loader"></div>
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
                                <p class="pt-2">Por favor indique su identificación para validar la información y proceder al envío del código OTP vía SMS y correo electrónico.</p>
                                <form method="post" action="bk_reset.php" class="pt-3">
                                    <div class="form-group">
                                        <input type="number" class="form-control form-control-lg" id="usuario" placeholder="Identificación" name="usuario" min="0">
                                    </div>
                                    <div class="mt-3">
                                        <a href="javascript:solicitar_OTP()" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">CONTINUAR</a>
                                    </div>
                                    <div class="text-center mt-4 font-weight-light">
                                        ¿Ya tienes una cuenta en <?=$title;?>?<br><a href="./" class="text-primary">Inicia Sesión</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                            <input type="hidden" class="form-control" id="id_cliente" name="id_cliente">
                            <input type="hidden" class="form-control" id="c_otp" name="c_otp">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:validarOTP();" class="btn btn-primary">Validar Código</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="modal_contrasena" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">CAMBIO DE CONTRASEÑA</h5>
                    </div>
                    <div class="modal-body">
                        <form id="reset_pass" action="bk_reset.php">
                        <div class="form-group">
                            <label for="exampleInputUsername1" class="font-weight-bold">INDIQUE SU NUEVA CONTRASEÑA</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                            <input type="hidden" class="form-control" id="id_cliente_new" name="id_cliente_new">
                            <input type="hidden" class="form-control" id="option" name="option" value="update">
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:cambiarContrasena();" class="btn btn-primary">Realizar Cambio de Contraseña</a>
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
        
        <script>
            function solicitar_OTP(){
                cargando(true);
                if($("#usuario").val().length > 5){
                    $.ajax({
                        type : 'POST',
                        url  : 'bk_reset.php',
                        data : {usuario:$("#usuario").val(), option:'OTP'},
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
                                $('#id_cliente').val(data.id_cliente);
                                $('#id_cliente_new').val(data.id_cliente);
                                $('#otp').val('');
                                $('#c_otp').val(data.otp);
                            }
                        }
                    });
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'ALERTA',
                        html: 'Complete la información solicitada'
                    });
                    cargando(false);
                }
            }
            
            function validarOTP(){
                if($('#otp').val() === $('#c_otp').val()){
                    $('#modal_otp').modal('hide');
                    $('#modal_contrasena').modal({
                        keyboard: false,
                        backdrop: 'static'
                    });
                }else{
                    Swal.fire({
                        type: 'danger',
                        title: 'CÓDIGO INVÁLIDO',
                        html: 'Disculpe, el código OTP ingresado no es válido, verifique e intente nuevamente',
                        showConfirmButton: true,
                        timer: 5000
                    });
                }
            }
            
            function cambiarContrasena(){
                $(".loader").show();
                if($('#password').val().length > 0){
                    $.post($("#reset_pass").attr('action'), $("#reset_pass").serialize(), function(data) {
                        Swal.fire({
                            icon: data['type'],
                            title: data['title'],
                            html: data['mensaje'],
                            showConfirmButton: false,
                            timer: 4000
                        });
                        if(data['type'] == 'success'){
                            setTimeout( function() { location.href = "index.php"; }, 2000 );
                        }
                        $(".loader").hide();
                    }, 'json');
                }else{
                    $(".loader").hide();
                    Swal.fire({
                        icon: 'warning',
                        title: 'ALERTA',
                        html: 'Complete la información solicitada'
                    });
                }
            }
        </script>
    </body>
</html>