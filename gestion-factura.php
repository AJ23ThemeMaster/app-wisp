<?php
    $page = 'C';
    include "include/conexion.php";
    $seccion  = 'Gestiona tu Factura';
    session_start();
    if (isset($_SESSION['logueado']) && $_SESSION['logueado']) {
        $usuario_actual = $_SESSION['username'];
        $cliente_actual = "SELECT usuarios_app.id_cliente, concat_ws(' ', contactos.nombre, contactos.apellido1, contactos.apellido2) as nombre FROM usuarios_app JOIN contactos ON usuarios_app.id_cliente = contactos.id WHERE usuarios_app.user = '$usuario_actual'";
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
                        <div id="invoice" class="d-none">
                            <div class="toolbar hidden-print d-none">
                                <div class="text-right">
                                    <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Imprimir</button>
                                </div>
                                <hr>
                            </div>
                            <div class="invoice overflow-auto">
                                <div style="min-width: 600px">
                                    <header>
                                        <div class="row">
                                            <div class="col">
                                                <a href="javascript:void(0);">
                                                    <img src="images/logo.png" data-holder-rendered="true" width="25%">
                                                </a>
                                            </div>
                                            <div class="col company-details">
                                                <?=$empresa;?>
                                            </div>
                                        </div>
                                    </header>
                                    <main>
                                        <div class="row contacts">
                                            <div class="col invoice-to">
                                                <div class="text-gray-light d-none">INVOICE TO:</div>
                                                <h3 class="to" id="clientenombre"></h3>
                                                <div id="clientenit"></div>
                                                <div id="clientedireccion"></div>
                                                <div id="clientecelular"></div>
                                                <div id="clienteemail"></div>
                                            </div>
                                            <div class="col invoice-details">
                                                <h1 class="invoice-id">Nro. <span id="facturacodigo"></span></h1>
                                                <div class="date"></div>
                                                <div class="date">Emisión: <span id="facturafecha"></span></div>
                                                <div class="date">Vencimiento: <span id="facturavencimiento"></span></div>
                                                <div class="date">Suspensión: <span id="facturasuspension"></span></div>
                                            </div>
                                        </div>
                                        
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">DESCRIPCIÓN</th>
                                                    <th class="text-center">PRECIO</th>
                                                    <th class="text-center">IMPUESTO</th>
                                                    <th class="text-center">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        <h3 id="facturaref"></h3>
                                                    </td>
                                                    <td class="text-center unit">$<span id="facturaprecio"></span></td>
                                                    <td class="text-center qty"><span id="facturaimpuesto"></span>%</td>
                                                    <td class="text-center total">$<span id="facturatotal"></span></td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3">SUBTOTAL</td>
                                                    <td class="text-center">$<span id="subtotal"></span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">IMP (<span id="porcentaje"></span>%)</td>
                                                    <td class="text-center">$<span id="impuesto"></span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">TOTAL</td>
                                                    <td class="text-center">$<span id="total"></span></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <center>
                                            <hr>
                                            <form action="https://checkout.wompi.co/p/" method="GET" id="form-wompi" class="d-none">
                                                <input type="hidden" name="public-key" id="public_key_wompi" />
                                                <input type="hidden" name="currency" value="COP" />
                                                <input type="hidden" name="amount-in-cents" id="amount-in-cents" />
                                                <input type="hidden" name="reference" id="reference"/>
                                                <input type="hidden" name="redirect-url" id="redirect_url_wompi" />
                                                <button class="btn btn-success" type="submit">Pagar con Wompi</button>
                                            </form>
                                            <button class="btn btn-success d-none" onclick="confirmar('form-wompi', 'WOMPI');" id="btn_wompi">Pagar con Wompi</button>

                                            <form method="post" action="https://checkout.payulatam.com/ppp-web-gateway-payu/" id="form-payu" class="d-none">
                                                <input id="merchantId"      name="merchantId"      type="hidden"  value="">
                                                <input id="accountId"       name="accountId"       type="hidden"  value="">
                                                <input id="description"     name="description"     type="hidden"  value="">
                                                <input id="referenceCode"   name="referenceCode"   type="hidden"  value="">
                                                <input id="amount"          name="amount"          type="hidden"  value="">
                                                <input id="tax"             name="tax"             type="hidden"  value="0">
                                                <input id="taxReturnBase"   name="taxReturnBase"   type="hidden"  value="0">
                                                <input id="currency"        name="currency"        type="hidden"  value="COP">
                                                <input id="signature"       name="signature"       type="hidden"  value="">
                                                <input id="test"            name="test"            type="hidden"  value="1">
                                                <input id="buyerFullName"   name="buyerFullName"   type="hidden"  value="">
                                                <input id="telephone"       name="telephone"       type="hidden"  value="">
                                                <input id="buyerEmail"      name="buyerEmail"      type="hidden"  value="">
                                                <input id="responseUrl"     name="responseUrl"     type="hidden"  value="">
                                                <input id="confirmationUrl" name="confirmationUrl" type="hidden"  value="">
                                                <input name="Submit"          type="submit"  value="Enviar">
                                            </form>
                                            <button class="btn btn-success d-none" onclick="confirmar('form-payu', 'PayU');" id="btn_payu">Pagar con PayU</button>

                                            <form id="form-epayco" class="d-none">
                                                <script
                                                src="https://checkout.epayco.co/checkout.js"
                                                class="epayco-button"
                                                data-epayco-currency="cop"
                                                data-epayco-country="co"
                                                data-epayco-test="true"
                                                data-epayco-external="true"
                                                data-epayco-response="https://ejemplo.com/respuesta.html"
                                                data-epayco-confirmation="https://ejemplo.com/confirmacion"
                                                data-epayco-methodconfirmation="post"
                                                id="script_epayco">
                                                </script>
                                            </form>
                                            <button class="btn btn-success d-none" onclick="confirmar('form-epayco', 'ePayco');" id="btn_epayco">Pagar con ePayco</button>

                                            <button class="btn btn-success d-none" onclick="confirmar('form-combopay', 'ComboPay');" id="btn_combopay">Pagar con ComboPay</button>
                                            <a class="d-none" id="a_combopay"></a>
                                        </center>
                                    </main>
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
        <script src="js/jquery.md5.js"></script>

        <script type="text/javascript">
            $('#printInvoice').click(function(){
                Popup($('.invoice')[0].outerHTML);
                function Popup(data){
                    window.print();
                    return true;
                }
            });

            $(document).ready(function(){
                var id_cliente = <?=$cliente;?>;
                $.ajax({
                    type : 'GET',
                    url  : 'bk_all.php',
                    data : {id_cliente:id_cliente},
                    dataType: 'JSON',
                    success : function(data){
                        if(data.factura){
                            $("#invoice").removeClass('d-none');
                            document.getElementById('clientenombre').innerHTML    = data.cliente.nombre;
                            document.getElementById('clientenit').innerHTML       = '(CC) '+data.cliente.nit;
                            document.getElementById('clientedireccion').innerHTML = data.cliente.direccion;
                            document.getElementById('clienteemail').innerHTML     = data.cliente.email;
                            document.getElementById('clientecelular').innerHTML   = data.cliente.celular;

                            document.getElementById('facturacodigo').innerHTML      = data.factura.codigo;
                            document.getElementById('facturafecha').innerHTML       = data.factura.fecha;
                            document.getElementById('facturavencimiento').innerHTML = data.factura.vencimiento;
                            document.getElementById('facturasuspension').innerHTML  = data.factura.suspension;
                            document.getElementById('facturaref').innerHTML         = data.factura.ref;
                            document.getElementById('facturaprecio').innerHTML      = number_format(data.plan.precio, '2', ',', '.');
                            document.getElementById('facturaimpuesto').innerHTML    = data.factura.impuesto;
                            document.getElementById('facturatotal').innerHTML       = number_format(((parseFloat(data.factura.precio) * parseFloat(data.factura.impuesto))/100)+parseFloat(data.factura.precio), '2', ',', '.');

                            document.getElementById('porcentaje').innerHTML  = data.factura.impuesto;
                            document.getElementById('subtotal').innerHTML    = number_format(data.plan.precio, '2', ',', '.');
                            document.getElementById('impuesto').innerHTML    = number_format(((parseFloat(data.factura.precio) * parseFloat(data.factura.impuesto))/100), '2', ',', '.');
                            document.getElementById('total').innerHTML       = number_format(((parseFloat(data.factura.precio) * parseFloat(data.factura.impuesto))/100)+parseFloat(data.factura.precio), '2', ',', '.');

                            $("#reference").val('<?=$nom_empresa;?>-'+data.factura.codigo);
                            $("#amount-in-cents").val(((parseFloat(data.factura.precio) * parseFloat(data.factura.impuesto))/100)+parseFloat(data.factura.precio)+'00');
                        }else{
                            $("#invoice").html('').html('<div class="row"><div class="col-md-6 offset-md-3 mb-4 stretch-card transparent"><div class="card card-dark-blue"><div class="card-body text-center"><p class="fs-20 mb-2" style="font-size: 1.5em!important;">NO POSEE NINGUNA FACTURA GENERADA</p></div></div></div></div>');
                            $("#invoice").removeClass('d-none');
                        }

                        if(data.factura){
                            $.each(data.pasarelas, function(index, value){
                                if(value.nombre == 'WOMPI'){
                                    $("#public_key_wompi").val(value.api_key);
                                    $("#redirect_url_wompi").val('https://'+window.location.hostname+'/wompi.php');
                                    $("#btn_wompi").removeClass('d-none');
                                }else if(value.nombre == 'PayU'){
                                    var amount = (((parseFloat(data.factura.precio) * parseFloat(data.factura.impuesto))/100)+parseFloat(data.factura.precio)*1);
                                    $("#merchantId").val(value.merchantId);
                                    $("#accountId").val(value.accountId);
                                    $("#description").val('Factura '+data.factura.codigo);
                                    $("#referenceCode").val('<?=$nom_empresa;?>-'+data.factura.codigo);
                                    $("#amount").val(amount);
                                    $("#tax").val(number_format(((parseFloat(data.factura.precio) * parseFloat(data.factura.impuesto))/100), '2', '.', ''));
                                    $("#buyerFullName").val(data.cliente.nombre);
                                    $("#buyerEmail").val(data.cliente.email);
                                    $("#telephone").val(data.cliente.celular);
                                    $("#responseUrl").val('https://'+window.location.hostname+'/payu.php');
                                    var str = window.location.hostname;
                                    $("#confirmationUrl").val('https://'+str.slice(4)+'/software/api/pagos/payu');
                                    $("#btn_payu").removeClass('d-none');

                                    $("#signature").val($.md5(value.api_key+"~"+value.merchantId+"~<?=$nom_empresa;?>-"+data.factura.codigo+"~"+amount*1+"~COP"));
                                }else if(value.nombre == 'ePayco'){
                                    var amount = (((parseFloat(data.factura.precio) * parseFloat(data.factura.impuesto))/100)+parseFloat(data.factura.precio)*1);
                                    var str = window.location.hostname;

                                    $("#script_epayco").attr('data-epayco-key', value.api_key)
                                    .attr('data-epayco-amount', amount)
                                    .attr('data-epayco-name', '<?=$nom_empresa;?>-'+data.factura.codigo)
                                    .attr('data-epayco-description', '<?=$nom_empresa;?>-'+data.factura.codigo)
                                    .attr('data-epayco-email-billing', data.cliente.email)
                                    .attr('data-epayco-name-billing', data.cliente.nombre)
                                    .attr('data-epayco-address-billing', data.cliente.direccion)
                                    .attr('data-epayco-mobilephone-billing', data.cliente.celular)
                                    .attr('data-epayco-number-doc-billing', data.cliente.nit)
                                    .attr('data-epayco-response', 'https://'+window.location.hostname+'/epayco.php')
                                    .attr('data-epayco-confirmation', 'https://'+str.slice(4)+'/software/api/pagos/epayco');
                                    $("#btn_epayco").removeClass('d-none');
                                }else if(value.nombre == 'ComboPay'){
                                    var token = {
                                        "url": "https://api.combopay.co/api/oauth/token?grant_type=password&client_secret="+value.merchantId+"&username="+value.user+"&password="+value.pass+"&client_id="+value.accountId,
                                        "method": "POST",
                                        "timeout": 0,
                                    };
                                    $.ajax(token).done(function (response) {
                                        if(response.access_token){
                                            var amount = (((parseFloat(data.factura.precio) * parseFloat(data.factura.impuesto))/100)+parseFloat(data.factura.precio)*1);
                                            var str = window.location.hostname;

                                            if(data.cliente.tip_iden==3){ var tip_iden = 'CC'; }else if(data.cliente.tip_iden==6){ var tip_iden = 'NIT'; }

                                            var link = {
                                                "url": "https://api.combopay.co/api/invoice-company-customer?value="+amount+"&description="+data.factura.codigo+"&invoice=<?=$nom_empresa;?>-"+data.factura.codigo+"&url_data_return=https://"+str.slice(4)+"/software/api/pagos/combopay&url_client_redirect=https://"+str+"/gestion-factura.php&name="+data.cliente.nombre+"&document_type="+tip_iden+"&customer_phone_number="+data.cliente.celular+"&email="+data.cliente.email+"&document="+data.cliente.nit+"&customer_address="+data.cliente.direccion,
                                                "method": "POST",
                                                "timeout": 0,
                                                "headers": {
                                                    "Authorization": "Bearer "+response.access_token+""
                                                },
                                            };

                                            $.ajax(link).done(function (response) {
                                                if(response.payment_link){
                                                    $("#btn_combopay").removeClass('d-none');
                                                    $("#a_combopay").attr('href', response.payment_link);
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
                return false;
            });

            function confirmar(form, mensaje, submensaje='¿Desea continuar?'){
                Swal.fire({
                    icon: 'question',
                    title: 'Será redireccionado a la pasarela de pago '+mensaje,
                    text: submensaje,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    confirmButtonColor: '#00ce68',
                    cancelButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        cargando(true);
                        if(form == 'form-epayco'){
                            $(".epayco-button-render").click();
                        }else if(form == 'form-combopay'){
                            $("#a_combopay")[0].click();
                        }else{
                            document.getElementById(form).submit();
                        }
                    }
                })
            }
        </script>
    </body>
</html>