<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Todos los Derechos Reservados © <?= utf8_encode($name_empresa);?></span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Desarrollado por <a href="https://networkingenieria.com/" target="_blank">Network Ingenieria Colombia S.A.S</a> <i class="ti-heart text-danger ml-1"></i></span>
    </div>
</footer>

<style type="text/css">
    .float{
        position:fixed;
        width:60px;
        height:60px;
        bottom:25px;
        left:25px;
        background-color: #25D366;
        color:#FFF;
        border-radius:50px;
        text-align:center;
        font-size:30px;
        box-shadow: 2px 2px 3px #999;
        z-index:1000;
    }
    .float:hover {
        text-decoration: none;
        color: white;
        background-color: #075E54;
    }
    .my-float{
        margin-top:16px;
    }
    .call{
        position:fixed;
        width:60px;
        height:60px;
        bottom:95px;
        left:25px;
        background-color: <?=$color;?>;
        color:#FFF;
        border-radius:50px;
        text-align:center;
        font-size:30px;
        box-shadow: 2px 2px 3px #999;
        z-index:1000;
    }
    .call:hover {
        text-decoration: none;
        color: white;
        background-color: #333;
    }
    .my-call{
        margin-top:16px;
    }
</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<a href="https://wa.me/<?= $whatsapp;?>" class="float" target="_blank">
    <i class="fa fa-whatsapp my-float"></i>
</a>

<a href="tel:<?= $telefono;?>" class="call" target="_blank">
    <i class="fa fa-phone my-call"></i>
</a>

<script>
    function number_format (number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
        
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    
    function ping(){
        cargando(true);
        var id_cliente = <?=$cliente;?>;
        $.ajax({
            type : 'POST',
            url  : 'bk_ping.php',
            data : {id_cliente:id_cliente},
            dataType: 'JSON',
            success : function(data){
                cargando(false);
                if(data.radicado){
                    $("#modal_radicado").modal('show');
                }else{
                    Swal.fire({
                        title: data.title,
                        icon: data.icon,
                        showCancelButton: false,
                        showConfirmButton: false,
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Aceptar'
                    });
                }
            }
        });
        return false;
    }
</script>