<?php
    header('Content-Type: application/json');
    date_default_timezone_set('America/Bogota');
    
    include "include/conexion.php";
    
    if($_POST){
        $fecha = date('Y-m-d');
        $id_cliente = strip_tags($_POST['id_cliente']);
        $nombre_antiguo = strip_tags($_POST['nombre_antiguo']);
        $nombre_nuevo = strip_tags($_POST['nombre_nuevo']);
        $contrasena_antiguo = strip_tags($_POST['contrasena_antiguo']);
        $contrasena_nuevo = strip_tags($_POST['contrasena_nuevo']);
        $oculta = strip_tags($_POST['red_oculta']);
        
        //INFO CONTRATO
            $query = "SELECT * FROM contracts WHERE client_id = '$id_cliente'";
            $result_query = mysqli_query($con,$query);
            $assoc_e = mysqli_fetch_assoc($result_query);
            $ip  = $assoc_e['ip'];
            $mac = $assoc_e['mac_address'];
        
        //REGISTRO DEL WIFI
            $query = "INSERT INTO wifi (id_cliente, red_antigua, red_nueva, pass_antigua, pass_nueva, ip, mac, fecha, oculta) VALUES ('$id_cliente', '$nombre_antiguo', '$nombre_nuevo', '$contrasena_antiguo', '$contrasena_nuevo', '$ip', '$mac', '$fecha', '$oculta')";
            $result = mysqli_query($con,$query);
            
            if($result){
                $json['success'] = 'true';
                $json['text'] = mb_strtoupper('Su contraseña ha sido cambiada exitosamente, verá reflejado los cambios en máximo dos horas laborales');
                echo json_encode($json);
                exit;
            }else{
                $json['success'] = 'false';
                $json['text'] = mb_strtoupper('Ha ocurrido un error, por favor contacte a un asesor para mayores detalles');
                echo json_encode($json);
                exit;
            }
            
    }else{
        $json['success'] = 'false';
        $json['text'] = mb_strtoupper('Ha ocurrido un error, por favor contacte a un asesor para mayores detalles');
        echo json_encode($json);
        exit;
    }
?>