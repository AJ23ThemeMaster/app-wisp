<?php
    include "include/conexion.php";
?>

<!DOCTYPE html>
<html lang="es-CO">
    <?php include('include/head.php');?>
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
        .modal .modal-dialog .modal-content .modal-body {
            padding: 35px 26px 0;
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
                                <h4 class="text-center mt-4">ASOCIAR CONTRATO</h4>
                                <form method="post" action="procesar_registro.php" class="pt-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-lg" id="nit" placeholder="Identficación" name="nit" required="" min="0" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                                    </div>
                                    <div class="form-group d-none">
                                        <input type="text" id="username" class="form-control form-control-lg" placeholder="Nombre de Usuario" name="username">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" id="pass" class="form-control form-control-lg" placeholder="Contraseña" required name="pass">
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <label class="form-check-label text-muted">
                                                <input type="checkbox" id="user-checkbox" name="user-checkbox" value="1">
                                                Acepto recibir material promocional y marketing
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn d-none" id="btn">REGISTRARSE</button>
                                        <a href="javascript:register();" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">REGISTRARSE</a>
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
        
        <div class="modal" tabindex="-1" id="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">VERIFICACIÓN OTP</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <form class="forms-sample text-center">
                                    <div class="form-group text-center">
                                        <label for="exampleInputUsername1">Ingrese el código enviado a su número celular o correo electrónico asociado</label>
                                        <input type="text" class="form-control" id="codigoOTP" name="codigoOTP" placeholder="Código OTP" maxlength="6" onkeypress="return event.charCode >= 48 && event.charCode <=57">
                                        <input type="hidden" class="form-control" id="id_cliente" name="id_cliente">
                                    </div>
                                    <a href="javascript:verificarOTP();" class="btn btn-primary">Validar Código</a>
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
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function register(){
                var nit = $('#nit').val();
                var pass = $('#pass').val();
                
                if(nit.length > 5){
                    cargando(true);
                    $.ajax({
                        type : 'POST',
                        url  : 'bk_register.php',
                        data : {nit:nit,pass:pass},
                        dataType: 'JSON',
                        success : function(data){
                            cargando(false);
                            if(data.success == 'false'){
                                Swal.fire({
                                    icon: data.icon,
                                    title: data.title,
                                    text: data.text,
                                })
                            }else{
                                $('#modal').modal({
                                    keyboard: false,
                                    backdrop: 'static'
                                })
                                //$("#modal").modal('show');
                                $('#id_cliente').val(data.id_cliente);
                            }
                        }
                    });
                }
            }
            
            function verificarOTP(){
                var id_cliente = $('#id_cliente').val();
                var codigoOTP = $('#codigoOTP').val();
                
                if(codigoOTP.length == 6){
                    cargando(true);
                    $.ajax({
                        type : 'POST',
                        url  : 'bk_OTP.php',
                        data : {id_cliente:id_cliente,codigoOTP:codigoOTP},
                        dataType: 'JSON',
                        success : function(data){
                            cargando(false);
                            if(data.success == 'false'){
                                cargando(false);
                                Swal.fire({
                                    icon: data.icon,
                                    title: data.title,
                                    text: data.text,
                                })
                            }else{
                                window.location.href = 'dashboard.php';
                            }
                        }
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'DISCULPE',
                        text: 'Está ingresando un código OTP incompleto, intente nuevamente',
                    })
                }
            }
        </script>
    </body>
</html>