<?php
    $page = 'C';
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
            #invoice{
                padding: 0px;
            }
            
            .btn-info {
                color: #fff;
                background-color: <?= $color;?>;
                border-color: <?= $color;?>;
            }
            
            .invoice {
                position: relative;
                background-color: #FFF;
                min-height: 600px;
                padding: 15px;
                border: solid 1px #d8d8d8;
            }
            
            .invoice header {
                padding: 10px 0;
                margin-bottom: 20px;
                border-bottom: 1px solid <?= $color;?>
            }
            
            .invoice .company-details {
                text-align: right
            }
            
            .invoice .company-details .name {
                margin-top: 0;
                margin-bottom: 0
            }
            
            .invoice .contacts {
                margin-bottom: 20px
            }
            
            .invoice .invoice-to {
                text-align: left
            }
            
            .invoice .invoice-to .to {
                margin-top: 0;
                margin-bottom: 0
            }
            
            .invoice .invoice-details {
                text-align: right
            }
            
            .invoice .invoice-details .invoice-id {
                margin-top: 0;
                color: <?= $color;?>
            }
            
            .invoice main {
                padding-bottom: 0px
            }
            
            .invoice main .thanks {
                margin-top: -100px;
                font-size: 2em;
                margin-bottom: 50px
            }
            
            .invoice main .notices {
                padding-left: 6px;
                border-left: 6px solid <?= $color;?>
            }
            
            .invoice main .notices .notice {
                font-size: 1.2em
            }
            
            .invoice table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 20px
            }
            
            .invoice table td,.invoice table th {
                padding: 15px;
                background: #eee;
                border-bottom: 1px solid #fff
            }
            
            .invoice table th {
                white-space: nowrap;
                font-weight: 400;
                font-size: 16px
            }
            
            .invoice table td h3 {
                margin: 0;
                font-weight: 400;
                color: <?= $color;?>;
                font-size: 1.2em
            }
            
            .invoice table .qty,.invoice table .total,.invoice table .unit {
                text-align: right;
                font-size: 1.2em
            }
            
            .invoice table .no {
                color: #fff;
                font-size: 1.6em;
                background: <?= $color;?>
            }
            
            .invoice table .unit {
                background: #ddd
            }
            
            .invoice table .total {
                background: <?= $color;?>;
                color: #fff
            }
            
            .invoice table tbody tr:last-child td {
                border: none
            }
            
            .invoice table tfoot td {
                background: 0 0;
                border-bottom: none;
                white-space: nowrap;
                text-align: right;
                padding: 10px 20px;
                font-size: 1.2em;
                border-top: 1px solid #aaa
            }
            
            .invoice table tfoot tr:first-child td {
                border-top: none
            }
            
            .invoice table tfoot tr:last-child td {
                color: <?= $color;?>;
                font-size: 1.4em;
                border-top: 1px solid <?= $color;?>
            }
            
            .invoice table tfoot tr td:first-child {
                border: none
            }
            
            .invoice footer {
                width: 100%;
                text-align: center;
                color: #777;
                border-top: 1px solid #aaa;
                padding: 8px 0
            }
            
            @media print {
                .invoice {
                    font-size: 11px!important;
                    overflow: hidden!important
                }
            
                .invoice footer {
                    position: absolute;
                    bottom: 10px;
                    page-break-after: always
                }
            
                .invoice>div:last-child {
                    page-break-before: always
                }
            }
            .card.card-tale:hover, .card.card-dark-blue:hover {
                background: <?= $color;?>;
            }
            .card.card-tale, .card.card-dark-blue {
                background: <?= $color;?>;
                color: #ffffff;
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
                        <div class="container">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row justify-content-center" id="form-factura">
                                        <div class="col-lg-12 text-center" data-aos="fade-up" data-aos-delay="00">
                                            <h2 class="text-primary font-extra">Respuesta de la Transacción</h2>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 offset-md-2">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td class="bold w-50">Factura Nro</td>
                                                            <td class="w-50" id="referencia"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="bold w-50">Monto</td>
                                                            <td class="w-50" id="monto"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="bold w-50">Descripción</td>
                                                            <td class="w-50" class="" id="x_description"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="bold w-50">Status</td>
                                                            <td class="w-50" class="" id="status"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
                cargando(true);
                url = 'https://secure.epayco.co/validation/v1/reference/<?=$_GET['ref_payco'];?>';
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function(data) {
                        var theDiv = document.getElementById("monto");
                        var content = document.createTextNode(data.data.x_amount+" COP");
                        theDiv.appendChild(content);

                        var theDiv = document.getElementById("x_description");
                        var content = document.createTextNode('Pago de Factura '+data.data.x_description.split('-')[1]);
                        theDiv.appendChild(content);

                        if(data.data.x_transaction_state == "Aceptada"){
                            var theDiv2 = document.getElementById("status");
                            var content2 = document.createTextNode("Aceptada");
                            theDiv2.appendChild(content2);
                        }else if(data.data.x_transaction_state == "Rechazada"){
                            var theDiv2 = document.getElementById("status");
                            var content2 = document.createTextNode("Rechazada");
                            theDiv2.appendChild(content2);
                        }else if(data.data.x_transaction_state == "Fallida"){
                            var theDiv2 = document.getElementById("status");
                            var content2 = document.createTextNode("Fallida");
                            theDiv2.appendChild(content2);
                        }else if(data.data.x_transaction_state == "Pendiente"){
                            var theDiv2 = document.getElementById("status");
                            var content2 = document.createTextNode("Pendiente");
                            theDiv2.appendChild(content2);
                        }else{
                            var theDiv2 = document.getElementById("status");
                            var content2 = document.createTextNode("Error Desconocido");
                            theDiv2.appendChild(content2);
                        }

                        var theDiv3 = document.getElementById("referencia");
                        var content3 = document.createTextNode(data.data.x_description);
                        theDiv3.appendChild(content3);

                        var reference = data.data.x_description;
                        var saldo = data.data.x_amount;
                        var transactionId = data.data.x_transaction_id;
                        var status = data.data.x_transaction_state;

                        var reference = data.data.x_description.split('-')[1];
                        $("#referencia").html(reference);

                        if(data.data.x_transaction_state == "Aceptada"){
                            $.ajax({
                                type : 'POST',
                                url  : 'bk_pay.php',
                                data : {reference:reference, saldo:saldo, status:status, transactionId:transactionId, pasarela: 'ePayco'},
                                success : function(data){
                                    Swal.fire({
                                        title: data.title,
                                        icon: data.icon,
                                        showCancelButton: false,
                                        showConfirmButton: false,
                                        cancelButtonColor: '#d33',
                                        cancelButtonText: 'Aceptar',
                                        timer: 10000
                                    });
                                    cargando(false);
                                }
                            });
                        }else if(data.data.x_transaction_state == "Fallida"){
                            Swal.fire({
                                title: 'Transacción Fallida',
                                icon: 'error',
                                showCancelButton: false,
                                showConfirmButton: false,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'Aceptar',
                                timer: 10000
                            });
                            cargando(false);
                        }else if(data.data.x_transaction_state == "Rechazada"){
                            Swal.fire({
                                title: 'Transacción Rechazada',
                                icon: 'error',
                                showCancelButton: false,
                                showConfirmButton: false,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'Aceptar',
                                timer: 10000
                            });
                            cargando(false);
                        }else if(data.data.x_transaction_state == "PENDING"){
                            Swal.fire({
                                title: 'Transacción Pendiente',
                                icon: 'warning',
                                showCancelButton: false,
                                showConfirmButton: false,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'Aceptar',
                                timer: 10000
                            });
                            cargando(false);
                        }else{
                            Swal.fire({
                                title: 'Error Desconocido',
                                icon: 'error',
                                showCancelButton: false,
                                showConfirmButton: false,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'Aceptar',
                                timer: 10000
                            });
                            cargando(false);
                        }
                    }
                });
                return false;
            });
        </script>
    </body>
</html>