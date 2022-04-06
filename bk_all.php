<?php
    header('Content-Type: application/json');
    
    include "include/conexion.php";
    
    if($_GET){
        $id_cliente = strip_tags($_GET['id_cliente']);

        $plan = "SELECT p.name plan, p.price precio, p.download bajada, p.upload subida FROM usuarios_app AS u JOIN contracts AS cs ON u.uid_cliente = cs.client_id JOIN planes_velocidad AS p ON p.id = cs.plan_id WHERE u.id_cliente = '$id_cliente'";
        $contrato = "SELECT cs.id, cs.server_configuration_id, cs.fecha_corte, cs.fecha_suspension, cs.state, cs.ip, cs.mac_address FROM usuarios_app AS u JOIN contracts AS cs ON u.uid_cliente = cs.client_id WHERE u.id_cliente = '$id_cliente'";
        $cliente = "SELECT c.nombre, c.nit, c.direccion, c.celular, c.email FROM contactos AS c WHERE c.id = '$id_cliente'";
        $wifi = "SELECT * FROM wifi WHERE id_cliente = '$id_cliente' ORDER BY id DESC";
        $red = "SELECT * FROM wifi WHERE id_cliente = '$id_cliente' AND status = 0 ORDER BY id DESC";
        //$mikrotik = "SELECT nombre, ip, puerto_web, puerto_api, usuario, clave FROM mikrotik WHERE id = 4";
        $factura = "SELECT F.*, I.precio, I.impuesto, I.cant, I.ref FROM factura AS F JOIN items_factura AS I ON F.id = I.factura WHERE F.cliente = '$id_cliente' AND F.estatus = 1 ORDER BY F.id DESC LIMIT 1";
        $planes = "SELECT id, name, price, download, upload FROM planes_velocidad";

        $resultp = mysqli_fetch_assoc(mysqli_query($con, $plan));
        $resultc = mysqli_fetch_assoc(mysqli_query($con, $contrato));
        $resultcl = mysqli_fetch_assoc(mysqli_query($con, $cliente));
        $resultw = mysqli_fetch_assoc(mysqli_query($con, $wifi));
        $resultr = mysqli_fetch_assoc(mysqli_query($con, $red));
        $resultf = mysqli_fetch_assoc(mysqli_query($con, $factura));
        $resultps = mysqli_query($con, $planes);
        //$resultk = mysqli_fetch_assoc(mysqli_query($con, $mikrotik));
        
        if($resultp && $resultc){
            $json['success'] = 'true';
            $json['plan']    = (is_array($resultp)) ? array_map("utf8_encode", $resultp) : false;
            $json['planes']    = $resultps;
            $json['contrato'] = (is_array($resultc)) ? array_map("utf8_encode", $resultc) : false;
            $json['cliente'] = (is_array($resultcl)) ? array_map("utf8_encode", $resultcl) : false;
            $json['wifi'] = (is_array($resultw)) ? array_map("utf8_encode", $resultw) : false;
            $json['red'] = (is_array($resultr)) ? array_map("utf8_encode", $resultr) : false;
            $json['factura'] = (is_array($resultf)) ? array_map("utf8_encode", $resultf) : false;
            //$json['mikrotik'] = array_map("utf8_encode", $resultk);
            echo json_encode($json);
            exit;
        }else{
            $json['success'] = 'false';
            $json['mensaje'] = 'No existe ningun registro con los parámetros enviados';
            echo json_encode($json);
            exit;
        }
   }
?>