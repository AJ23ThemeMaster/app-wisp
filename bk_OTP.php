<?php
    $sessionTime = 365 * 24 * 60 * 60;
    session_set_cookie_params($sessionTime);
    session_start();
    if ((isset($_POST['id_cliente']) && !empty($_POST['id_cliente'])) && (isset($_POST['codigoOTP']) && !empty($_POST['codigoOTP']))) {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            include "include/conexion.php";
            $id_cliente = $_POST['id_cliente'];
            $codigoOTP  = $_POST['codigoOTP'];
            $fecha      = date("Y-m-d h:i:s");
            
            $info_cliente = "SELECT * FROM usuarios_app WHERE id_cliente = '$id_cliente' AND codigo = '$codigoOTP'";
            $result = mysqli_query($con,$info_cliente);
            $assoc_info = mysqli_fetch_assoc($result);
            $user = $assoc_info['user'];
            
            if($user){
                $query = "UPDATE usuarios_app SET validacion = '1', fecha = '$fecha' WHERE id_cliente = '$id_cliente'";
                mysqli_query($con,$query);
                
                $_SESSION['username'] = $user;
                $_SESSION['logueado'] = true;
                
                $json['success'] = 'true';
                $json['user']    = $user;
                echo json_encode($json);
                exit;
            }else{
                $json['success'] = 'false';
                $json['icon']    = 'error';
                $json['text']    = 'El código OTP ingresado no es igual al enviado vía SMS';
                $json['title']   = 'DISCULPE';
                echo json_encode($json);
                exit;
            }
        }
    }else{
    	$json['success'] = 'false';
        $json['icon']    = 'error';
        $json['text']    = 'Ha ocurrido un error inesperado, intente de nuevo';
        $json['title']   = 'DISCULPE';
        echo json_encode($json);
        exit;
    }
?>