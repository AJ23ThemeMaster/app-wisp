<?php

    if ((isset($_POST['id_cliente']) && !empty($_POST['id_cliente']))) {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            include "include/conexion.php";
            //var_dump($_POST);exit;

            $id_cliente    = $_POST['id_cliente'];
            $nombre        = strip_tags($_POST['nombre_radi']);
            $nit           = strip_tags($_POST['nit_radi']);
            $celular       = strip_tags($_POST['celular_radi']);
            $email         = strip_tags($_POST['email_radi']);
            $direccion     = strip_tags($_POST['direccion_radi']);
            $observaciones = strip_tags($_POST['observaciones_radi']);
            $contrato      = strip_tags($_POST['contrato_radi']);
            $ip            = strip_tags($_POST['ip_radi']);
            $mac           = strip_tags($_POST['mac_radi']);
            $fecha         = date('Y-m-d');
            $codigo        = rand(0, 99999);

            $query = "INSERT INTO radicados (cliente, identificacion, fecha, nombre, telefono, correo, direccion, contrato, desconocido, servicio, estatus, codigo, empresa, ip, mac_address, creado) VALUES ('$id_cliente', '$nit', '$fecha', '$nombre', '$celular', '$email', '$direccion', '$contrato', '$observaciones', '5', '0', '$codigo' , '1', '$ip' ,'$mac', '2')";
            mysqli_query($con,$query);

            $json['success'] = 'true';
            $json['text']    = 'Pronto nuestro equipo de soporte canalizará su caso para darle respuesta.';
            $json['title']   = 'RADICADO CREADO';
            $json['icon']    = 'success';
            echo json_encode($json);
            exit;
        }else{
            $json['success'] = 'false';
            $json['radicado']= 'false';
            $json['icon']    = 'error';
            $json['text']    = mb_strtoupper('Ha ocurrido un error inesperado, intente de nuevo');
            $json['title']   = 'DISCULPE';
            echo json_encode($json);
            exit;
        }
    }else{
        $json['success'] = 'false';
        $json['radicado']= 'false';
        $json['icon']    = 'error';
        $json['text']    = mb_strtoupper('Ha ocurrido un error inesperado, intente de nuevo');
        $json['title']   = 'DISCULPE';
        echo json_encode($json);
        exit;
    }
?>