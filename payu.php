<?php
    $page = 'C';
    include "include/conexion.php";
    session_start();
    if (isset($_SESSION['logueado']) && $_SESSION['logueado']) {
        $usuario_actual = $_SESSION['username'];
        $cliente_actual = "SELECT usuarios_app.id_cliente, concat_ws(' ', contactos.nombre, contactos.apellido1, contactos.apellido2) as nombre FROM usuarios_app JOIN contactos ON usuarios_app.id_cliente = contactos.id WHERE usuarios_app.user = '$usuario_actual'";
        $result_cliente = mysqli_query($con,$cliente_actual);
        $assoc_cliente  = mysqli_fetch_assoc($result_cliente);
        $cliente        = $assoc_cliente['id_cliente'];
        $nombre         = $assoc_cliente['nombre'];
        
        $pasarelas = "SELECT * FROM integracion WHERE tipo = 'PASARELA' AND status = 1 AND app = 1 AND nombre = 'PayU'";
        $pasarela = mysqli_fetch_assoc(mysqli_query($con, $pasarelas));
        
        $ApiKey           = $pasarela['api_key'];
        $merchant_id      = $_REQUEST['merchantId'];
        $referenceCode    = $_REQUEST['referenceCode'];
        $TX_VALUE         = $_REQUEST['TX_VALUE'];
        $New_value        = number_format($TX_VALUE, 1, '.', '');
        $currency         = $_REQUEST['currency'];
        $transactionState = $_REQUEST['transactionState'];
        $firma_cadena     = "$ApiKey~$merchant_id~$referenceCode~$New_value~$currency~$transactionState";
        $firmacreada      = md5($firma_cadena);
        $firma            = $_REQUEST['signature'];
        $reference_pol    = $_REQUEST['reference_pol'];
        $cus              = $_REQUEST['cus'];
        $extra1           = $_REQUEST['description'];
        $pseBank          = $_REQUEST['pseBank'];
        $lapPaymentMethod = $_REQUEST['lapPaymentMethod'];
        $transactionId    = $_REQUEST['transactionId'];
        
        if ($_REQUEST['transactionState'] == 4 ) {
            $estadoTx = "TRANSACCIÓN APROBADA";
        }else if ($_REQUEST['transactionState'] == 6 ) {
            $estadoTx = "TRANSACCIÓN RECHAZADA";
        }else if ($_REQUEST['transactionState'] == 104 ) {
            $estadoTx = "ERROR";
        }else if ($_REQUEST['transactionState'] == 7 ) {
            $estadoTx = "PAGO PENDIENTE";
        }else {
            $estadoTx=$_REQUEST['mensaje'];
        }
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
                                            <h2 class="text-primary font-extra mb-3">Respuesta de la Transacción</h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 offset-md-2">
                                            <?php if (strtoupper($firma) == strtoupper($firmacreada)) { ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td class="font-weight-bold w-50">Factura Nro</td>
                                                            <td class="w-50"><?php echo substr($referenceCode,4); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold w-50">Monto</td>
                                                            <td class="w-50"><?php echo $currency; ?> <?php echo number_format($TX_VALUE); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold w-50">Descripción</td>
                                                            <td class="w-50"><?php echo ($extra1); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold w-50">ID de la transacción</td>
                                                            <td class="w-50"><?php echo $transactionId; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold w-50">Referencia de venta</td>
                                                            <td><?php echo $reference_pol; ?></td>
                                                        </tr>
                                                        <?php if($pseBank != null) { ?>
                                                        <tr>
                                                            <td class="font-weight-bold w-50">cus</td>
                                                            <td><?php echo $cus; ?> </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold w-50">Banco</td>
                                                            <td><?php echo $pseBank; ?> </td>
                                                        </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <td class="font-weight-bold w-50">Estado de la transacción</td>
                                                            <td class="w-50"><?php echo $estadoTx; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php }else{ ?>
                                            <div class="text-center mt-3">
                                                <h3 class="text-uppercase mb-2">Error validando la firma digital.</h3>
                                                <h3>Por favor comuníquese con la administración de <?=$name_empresa;?></h3>
                                            </div>
                                            <?php } ?>
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
                <?php if (strtoupper($firma) == strtoupper($firmacreada)) { ?>
                    <?php if ($_REQUEST['transactionState'] == 4) { ?>
                        cargando(true);
                        var reference = '<?php echo substr($referenceCode,4); ?>';
                        var saldo = '<?php echo $TX_VALUE*1; ?>';
                        var transactionId = '<?php echo $transactionId; ?>';
                        var status = '<?php echo $_REQUEST['transactionState'];?>';

                        $.ajax({
                            type : 'POST',
                            url  : 'bk_pay.php',
                            data : {reference:reference, saldo:saldo, status:status, transactionId:transactionId, pasarela:'PayU'},
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
                    <?php } ?>

                    <?php if ($_REQUEST['transactionState'] == 6 || $_REQUEST['transactionState'] == 104 || $_REQUEST['transactionState'] == 7) { ?>
                        Swal.fire({
                            title: '<?php echo $estadoTx; ?>',
                            text: '<?=$name_empresa;?>',
                            icon: 'warning',
                            showCancelButton: false,
                            showConfirmButton: false,
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Aceptar',
                            timer: 10000
                        });
                    <?php } ?>
                <?php }else{ ?>
                    Swal.fire({
                        title: 'ERROR VALIDANDO LA FIRMA DIGITAL',
                        text: 'Por favor comuníquese con la administración de <?=$name_empresa;?>',
                        icon: 'error',
                        showCancelButton: false,
                        showConfirmButton: false,
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Aceptar',
                        timer: 10000
                    });
                <?php } ?>
                return false;
            });
        </script>
    </body>
</html>