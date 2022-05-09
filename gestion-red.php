<?php
    $page = 'D';
    include "include/conexion.php";
    $seccion  = 'Gestiona tu Red';
    session_start();
    if (isset($_SESSION['logueado']) && $_SESSION['logueado']) {
        $usuario_actual = $_SESSION['username'];
        $cliente_actual = "SELECT usuarios_app.id_cliente, contactos.nombre FROM usuarios_app JOIN contactos ON usuarios_app.id_cliente = contactos.id WHERE usuarios_app.user = '$usuario_actual'";
        $result_cliente = mysqli_query($con,$cliente_actual);
        $assoc_cliente  = mysqli_fetch_assoc($result_cliente);
        $cliente        = $assoc_cliente['id_cliente'];
        $nombre         = $assoc_cliente['nombre'];
        
        $wifi = "SELECT * FROM wifi WHERE id_cliente = ".$cliente." ORDER BY id DESC";
        $result_wifi = mysqli_query($con,$wifi);
        $assoc_wifi = mysqli_fetch_assoc($result_wifi);
        $pass_new = $assoc_wifi['pass_nueva'];
        $red_new = $assoc_wifi['red_nueva'];
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
                background-color: <?= $color;?>;
                border-color: <?= $color;?>;
            }
            .modal .modal-dialog .modal-content .modal-footer {
                padding: 5px;
            }
            label {
                font-weight: bold;
            }
            @media (min-width: 992px){
                .modal-lg, .modal-xl {
                    max-width: 70%;
                }
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
                            <div class="col-md-4 mb-4 offset-md-2 stretch-card transparent" id="div_wifi">
                                <a href="javascript:void();" data-toggle="modal" data-target="#modal_wifi" class="speed text-center" id="btn_wifi">
                                    <div class="card card-dark-blue">
                                        <div class="card-body text-center">
                                            <p class="fs-20 mb-2" style="font-size: 1.5em!important;">QUIERO CAMBIAR LA CONTRASEÑA WIFI</p>
                                            <i class="fas fa-sync-alt fa-5x fa-spin"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-4 stretch-card transparent">
                                <a href="javascript:ping();" class="speed text-center">
                                    <div class="card card-dark-blue">
                                        <div class="card-body text-center">
                                            <p class="fs-20 mb-2" style="font-size: 1.5em!important;">DIAGNOSTICO DE DAÑOS</p>
                                            <i class="fas fa-network-wired fa-5x"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-4 stretch-card transparent d-none" id="div_radicados">
                                <a href="javascript:void();" data-toggle="modal" data-target="#modal_listado" class="speed text-center" id="">
                                    <div class="card card-dark-blue">
                                        <div class="card-body text-center">
                                            <p class="fs-20 mb-2" style="font-size: 1.5em!important;">ÚLTIMO RADICADO GENERADO</p>
                                            <i class="fas fa-ticket-alt fa-5x"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="modal_wifi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">CAMBIO DE CONTRASEÑA WIFI</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <form id="solicitud_wifi" method="POST" onsubmit="event.preventDefault();" action="bk_wifi.php">
                                            <input class="d-none" type="hidden" name="pass_new" id="pass_new" value="<?php echo $pass_new; ?>">
                                            <input class="d-none" type="hidden" name="red_new" id="red_new" value="<?php echo $red_new; ?>">
                                            <input type="hidden" class="form-control" id="id_cliente" name="id_cliente" value="<?=$cliente;?>">
                                            <div class="row row-group m-4">
                                                <div class="col-md-6">
                                                    <label>Nombre de Red Antigua</label>
                                                    <input type="text" class="form-control" id="nombre_antiguo" name="nombre_antiguo" autocomplete="off" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Nombre de Red Nueva</label>
                                                    <input type="text" class="form-control" id="nombre_nuevo" name="nombre_nuevo" autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="row row-group m-4">
                                                <div class="col-md-4">
                                                    <label>Contraseña Antigua</label>
                                                    <input type="password" class="form-control" id="contrasena_antiguo" name="contrasena_antiguo" autocomplete="off" maxlength="64" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Contraseña Nueva</label>
                                                    <input type="password" class="form-control" id="contrasena_nuevo" name="contrasena_nuevo" autocomplete="off" maxlength="64" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Confirme la Contraseña</label>
                                                    <input type="password" class="form-control" id="confirmar_contrasena" autocomplete="off" maxlength="64" required>
                                                </div>
                                                <div class="col-md-6 offset-md-3 text-center">
                                                    <button class="btn btn-primary" type="button" onclick="mostrarContrasena()" style="margin-top: 32px;">Mostrar Contraseñas</button>
                                                </div>
                                            </div>
                                            <div class="row row-group m-4">
                                                <div class="col-md-4 offset-md-4">
                                                    <label>Red Oculta</label>
                                                    <select class="form-control" name="red_oculta" id="red_oculta" required onchange="verificar_red(this.value);">
                                                        <option value="0" selected>No</option>
                                                        <option value="1">Si</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:solicitud_wifi();" class="btn btn-primary">Realizar Cambio</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal_radicado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="font-weight: bold;">SERVICIO DE INTERNET CAÍDO</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <form id="solicitud_radicado" method="POST" onsubmit="event.preventDefault();" action="bk_radicados.php">
                                            <input type="hidden" class="form-control" id="id_cliente" name="id_cliente" value="<?=$cliente;?>">
                                            <input type="hidden" class="form-control" id="nit_radi" name="nit_radi">
                                            <input type="hidden" class="form-control" id="contrato_radi" name="contrato_radi">
                                            <input type="hidden" class="form-control" id="ip_radi" name="ip_radi">
                                            <input type="hidden" class="form-control" id="mac_radi" name="mac_radi">
                                            <div class="row row-group m-4">
                                                <div class="col-md-12">
                                                    <p>Su servicio de internet se encuentra afectado, desde el siguiente formulario podrá crear un radicado para solicitar el soporte respectivo.</p>
                                                </div>

                                                <div class="col-md-6 mt-3">
                                                    <label>Cliente</label>
                                                    <input type="text" class="form-control" id="nombre_radi" name="nombre_radi" autocomplete="off" required>
                                                </div>

                                                <div class="col-md-6 mt-3">
                                                    <label>Celular</label>
                                                    <input type="text" class="form-control" id="celular_radi" name="celular_radi" autocomplete="off" required>
                                                </div>

                                                <div class="col-md-6 mt-3">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" id="email_radi" name="email_radi" autocomplete="off" required>
                                                </div>

                                                <div class="col-md-6 mt-3">
                                                    <label>Dirección</label>
                                                    <input type="text" class="form-control" id="direccion_radi" name="direccion_radi" autocomplete="off" required>
                                                </div>

                                                <div class="col-md-12 mt-3">
                                                    <label>Observaciones</label>
                                                    <textarea class="form-control" id="observaciones_radi" name="observaciones_radi" autocomplete="off" required rows="5"></textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                    <a href="javascript:solicitud_radicado();" class="btn btn-primary">Crear Radicado</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal_listado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="font-weight: bold;">ÚLTIMO RADICADO GENERADO</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body p-0">
                                    <div class="row">
                                        <div class="col-md-12 stretch-card">
                                            <div class="card">
                                                <div class="card-body" style="padding: 3%;">
                                                    <div class="row">
                                                        <div class="col-3 text-center" style="border: solid <?= $color;?> 1px;border-radius: 10px 0 0 10px;padding: 1%;">
                                                            <label class="text-uppercase">Fecha</label>
                                                            <p>
                                                                <span class="ml-2" id="radicado_Fecha"></span>
                                                            </p>
                                                        </div>
                                                        <div class="col-3 text-center" style="border: solid <?= $color;?> 1px;padding: 1%;">
                                                            <label class="text-uppercase">Código</label>
                                                            <p>
                                                                <span class="ml-2" id="radicado_Nro"></span>
                                                            </p>
                                                        </div>
                                                        <div class="col-3 text-center" style="border: solid <?= $color;?> 1px;padding: 1%;">
                                                            <label class="text-uppercase">Servicio</label>
                                                            <p>
                                                                <span class="ml-2" id="radicado_Servicio"></span>
                                                            </p>
                                                        </div>
                                                        <div class="col-3 text-center" style="border: solid <?= $color;?> 1px;border-radius: 0 10px 10px 0;padding: 1%;">
                                                            <label class="text-uppercase">Estado</label>
                                                            <p>
                                                                <span class="ml-2" id="radicado_Estado"></span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-12 mt-3" style="border: solid <?= $color;?> 1px; border-radius: 10px;padding: 1%;">
                                                            <label class="text-uppercase">Observaciones</label>
                                                            <p>
                                                                <span class="ml-2" id="radicado_Observaciones"></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
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
                        if(data.wifi){
                            if(data.wifi.status == 1){
                                $('#div_wifi, #div_link').addClass('d-none');
                                $('#div_link_wifi').removeClass('d-none');

                                $('#btn_wifi').removeAttr('data-target').attr('data-target', '');

                                Swal.fire({
                                    icon: 'warning',
                                    title: 'NOTIFICACIÓN',
                                    html: 'Ud posee una solicitud de cambio de contraseña WIFI en proceso.'
                                });

                            }else{
                                $('#div_wifi, #div_link').removeClass('d-none');
                                $('#div_link_wifi').addClass('d-none');
                            }
                        }

                        if(data.red == null){
                            $('#div_link_wifi').addClass('d-none');
                        }else{
                            $('#div_link_wifi').removeClass('d-none');
                        }

                        $("#nombre_radi").val(data.cliente.nombre);
                        $("#celular_radi").val(data.cliente.celular);
                        $("#email_radi").val(data.cliente.email);
                        $("#direccion_radi").val(data.cliente.direccion);
                        $("#nit_radi").val(data.cliente.nit);

                        $("#contrato_radi").val(data.contrato.id);
                        $("#ip_radi").val(data.contrato.ip);
                        $("#mac_radi").val(data.contrato.mac_address);

                        if(data.radicados){
                            $("#div_radicados").removeClass('d-none');
                            $("#div_wifi").removeClass('offset-md-2');
                            $("#radicado_Nro").text(data.radicados.codigo);
                            $("#radicado_Observaciones").text(data.radicados.desconocido);

                            var dateAr = data.radicados.fecha.split('-');
                            var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
                            var dateAr = data.radicados.created_at.split(' ');

                            $("#radicado_Fecha").text(newDate+' '+dateAr[1]);

                            if(data.radicados.estatus == 0 || data.radicados.estatus == 2){
                                $("#radicado_Estado").text('Pendiente').addClass('text-danger');
                            }else{
                                $("#radicado_Estado").text('Solventado').addClass('text-success');
                            }

                            if(data.radicados.servicio == 2){
                                $("#radicado_Servicio").text('Daños');
                            }else if(data.radicados.servicio == 4){
                                $("#radicado_Servicio").text('Instalaciones');
                            }else if(data.radicados.servicio == 5){
                                $("#radicado_Servicio").text('Soporte');
                            }else if(data.radicados.servicio == 6){
                                $("#radicado_Servicio").text('Traslados');
                            }else if(data.radicados.servicio == 7){
                                $("#radicado_Servicio").text('Reconexión');
                            }else if(data.radicados.servicio == 8){
                                $("#radicado_Servicio").text('Suspensión temporal');
                            }else if(data.radicados.servicio == 9){
                                $("#radicado_Servicio").text('Cambio de clave');
                            }else if(data.radicados.servicio == 10){
                                $("#radicado_Servicio").text('Retiro de Servicio');
                            }
                        }
                    }
                });
                return false;
            });

            $('#message').keydown(function () {
                var max = 20;
                var len = $(this).val().length;
                if (len >= max) {
                    $('#mensaje_ayuda').text('Has llegado al límite');// Aquí enviamos el mensaje a mostrar
                    $('#mensaje_ayuda').addClass('text-danger');
                    $('#message').addClass('is-invalid');
                    $('#inputsubmit').addClass('disabled');
                    document.getElementById('inputsubmit').disabled = true;
                }else{
                    var ch = max - len;
                    $('#mensaje_ayuda').text(ch + ' carácteres restantes');
                    $('#mensaje_ayuda').removeClass('text-danger');
                    $('#message').removeClass('is-invalid');
                    $('#inputsubmit').removeClass('disabled');
                    document.getElementById('inputsubmit').disabled = false;
                }
            });

            function solicitud_wifi(){
                var max = 64;
                var min = 8;

                if($('#pass_new').val().length > 0){
                    if($('#pass_new').val() === $('#contrasena_antiguo').val()){
                        if($('#contrasena_nuevo').val() === $('#confirmar_contrasena').val()){
                            if($('#contrasena_nuevo').val().length >= min && $('#contrasena_nuevo').val().length <= max){
                                $("#btn_cambiar").addClass('disabled');
                                if($("#id_cliente").val()>0){
                                    $(".loader").show();
                                    $.post($("#solicitud_wifi").attr('action'), $("#solicitud_wifi").serialize(), function(data) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'COMPLETADO',
                                            html: data['text'],
                                            showConfirmButton: false,
                                            timer: 4000
                                        });
                                        setTimeout( function() { location.href = "dashboard.php"; }, 2000 );
                                        $(".loader").hide();
                                    }, 'json');
                                }else{
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'ERROR',
                                        html: 'Estamos presentando inconvenientes, la página será recargada para que intente nuevamente el proceso'
                                    });
                                    setTimeout( function() { location.href = "wifi.php"; }, 2000 );
                                }
                            }else{
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'ERROR',
                                    html: 'La contraseña permitida debe tener un rango entre 8 y 64 dígitos, intente nuevamete'
                                });
                            }
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'ERROR',
                                html: 'Las contraseñas indicadas no coinciden, intente nuevamete'
                            });
                        }
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            html: 'La contraseña anterior no coincide con la almacenada, intente nuevamete'
                        });
                    }
                }else{
                    if($('#contrasena_nuevo').val() === $('#confirmar_contrasena').val()){
                        if($('#contrasena_nuevo').val().length >= min && $('#contrasena_nuevo').val().length <= max){
                            $("#btn_cambiar").addClass('disabled');
                            if($("#id_cliente").val()>0){
                                $(".loader").show();
                                $.post($("#solicitud_wifi").attr('action'), $("#solicitud_wifi").serialize(), function(data) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'COMPLETADO',
                                        html: data['text'],
                                        showConfirmButton: false,
                                        timer: 4000
                                    });
                                    setTimeout( function() { location.href = "dashboard.php"; }, 2000 );
                                    $(".loader").hide();
                                }, 'json');
                            }else{
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'ERROR',
                                    html: 'Estamos presentando inconvenientes, la página será recargada para que intente nuevamente el proceso'
                                });
                                setTimeout( function() { location.href = "wifi.php"; }, 2000 );
                            }
                        }else{
                            Swal.fire({
                                icon: 'warning',
                                title: 'ERROR',
                                html: 'La contraseña permitida debe tener un rango entre 8 y 64 dígitos, intente nuevamete'
                            });
                        }
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            html: 'Las contraseñas indicadas no coinciden, intente nuevamete'
                        });
                    }
                }
            }

            function verificar_red(option) {
                if (option == 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ALERTA',
                        html: 'Señor usuario si usted coloca red oculta recuerde que esta ya no le aparecerá en redes disponibles y le tocará agregarla manualmente.'
                    });
                }
            }

            function mostrarContrasena(){
                var contrasena_antiguo = document.getElementById("contrasena_antiguo");
                var contrasena_nuevo = document.getElementById("contrasena_nuevo");
                var confirmar_contrasena = document.getElementById("confirmar_contrasena");
                if(contrasena_antiguo.type == "password"){
                    contrasena_antiguo.type = "text";
                    contrasena_nuevo.type = "text";
                    confirmar_contrasena.type = "text";
                }else{
                    contrasena_antiguo.type = "password";
                    contrasena_nuevo.type = "password";
                    confirmar_contrasena.type = "password";
                }
            }

            function solicitud_radicado(){
                if($("#id_cliente").val()>0){
                    $(".loader").show();
                    $.post($("#solicitud_radicado").attr('action'), $("#solicitud_radicado").serialize(), function(data) {
                        $("#modal_radicado").modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'COMPLETADO',
                            html: data['text'],
                            showConfirmButton: false,
                            timer: 4000
                        });
                        setTimeout( function() { location.href = "dashboard.php"; }, 2000 );
                        $(".loader").hide();
                    }, 'json');
                }else{

                }
            }
        </script>
    </body>
</html>
