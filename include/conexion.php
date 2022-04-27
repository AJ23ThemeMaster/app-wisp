<?php
    $host     = "localhost";
    $username = "networks_root";
    $db       = "networks_root";
    $password = "networks_root";

    $con      = mysqli_connect($host,$username,$password,$db);

    ## EMPRESA ##

    $sql = "SELECT * FROM empresas WHERE id = '1'";
    $empresa = mysqli_fetch_assoc(mysqli_query($con, $sql));

    $name_empresa = $empresa['nombre'];
    $color        = $empresa['color'];
    $title        = $empresa['nombre_app'];
    $title_corto  = $empresa['nombre_app'];
    $img_logo     = 'https://'.$_SERVER['HTTP_HOST'].'/images/logo.png';
    $redirect     = 'https://'.$_SERVER['HTTP_HOST'].'/wompi.php';

    $empresa      = '<div>NIT '.$empresa['nit'].'-'.$empresa['dv'].'</div><div>'.$empresa['direccion'].'</div><div>'.$empresa['telefono'].'</div><div>'.$empresa['email'].'</div>';

    ## EMPRESA ##

    ## INTEGRACIONES ##

    $sql = "SELECT * FROM integracion WHERE tipo = 'SMS' AND lectura = '1' AND status = '1'";
    $sms = mysqli_fetch_assoc(mysqli_query($con, $sql));

    $sms_login     = $empresa['user'];
    $sms_password  = $empresa['pass'];

    ## INTEGRACIONES ##

    $seccion       = 'Dashboard';

    $empresa_email = 'info@networksoft.online';
    $email_pass    = 'networks_root';
    $nom_empresa   = 'NET';

    $whatsapp      = '';
    $speedtest     = '';
?>