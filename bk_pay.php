<?php
    header('Content-Type: application/json');
    date_default_timezone_set('America/Bogota');
    
    include "include/conexion.php";
    include "include/routeros_api.class.php";
            
    $API = new RouterosAPI();
    $data = new StdClass();
    
    if($_POST){
        $saldo         = strip_tags($_POST['saldo']);
        $reference     = strip_tags($_POST['reference']);
        $transactionId = strip_tags($_POST['transactionId']);
        $fecha         = date('Y-m-d');
        
        if(!empty($_POST['status'])){
            $status = strip_tags($_POST['status']);
        }
        
        if($status != 'APPROVED'){
            $json['success'] = 'false';
            $json['title']   = 'Ha ocurrido un error, comuniquese con la administración del sistema';
            $json['icon']    = 'error';
            echo json_encode($json);
            exit;
        }
        
        //FACTURA
        $query = "SELECT * FROM factura WHERE codigo = '$reference'";
        $result_query = mysqli_query($con,$query);
        $assoc_f = mysqli_fetch_assoc($result_query);
        $factura = $assoc_f['id'];
        $cliente = $assoc_f['cliente'];
        $estatus = $assoc_f['estatus'];
        
        if($estatus == 1){
            //CONSECUTIVO DE CAJA
            $query = "SELECT nro FROM ingresos WHERE empresa = 1 ORDER BY id DESC LIMIT 1";
            $result_query = mysqli_query($con,$query);
            $assoc_n = mysqli_fetch_assoc($result_query);
            $nro = $assoc_n['nro'];
            $nro++;
            
            //REGISTRO DEL INGRESO
            $query = "INSERT INTO ingresos (nro, empresa, cliente, cuenta, metodo_pago, fecha, observaciones, tipo, estatus) VALUES ('$nro', '1', '$cliente', '3', '9', '$fecha', 'Pago Wompi ID: $transactionId', '1', '1')";
            mysqli_query($con,$query);
            
            $query = "SELECT MAX(id) AS id FROM ingresos";
            $result_query = mysqli_query($con,$query);
            $assoc_i = mysqli_fetch_assoc($result_query);
            $id_ingreso = $assoc_i['id'];
            
            //REGISTRO INGRESOS_FACTURA
            $query = "INSERT INTO ingresos_factura (ingreso, factura, pagado, pago) VALUES ('$id_ingreso', '$factura', '0.00', '$saldo')";
            mysqli_query($con,$query);
            
            //AUMENTA EL CONSECUTIVO DE CAJA
            $nro++;
            $query = "UPDATE numeraciones SET caja = '$nro' WHERE empresa = 1";
            mysqli_query($con,$query);
            
            //ASENTAMOS EL MOVIMIENTO DE INGRESO
            $query = "INSERT INTO movimientos (empresa, banco, contacto, tipo, saldo, fecha, modulo, id_modulo, descripcion) VALUES ('1', '3', '$cliente', '1', '$saldo', '$fecha', '1', '$id_ingreso', '$id_ingreso')";
            mysqli_query($con,$query);
            
            //ACTUALIZAMOS LA FACTURA
            $query = "UPDATE factura SET estatus = 0 WHERE codigo = '$reference'";
            mysqli_query($con,$query);
            
            //ACTUALIZAMOS EL CONTRATO
            $query = "UPDATE contracts SET state = 'enabled' WHERE client_id = '$cliente'";
            mysqli_query($con,$query);
            
            //INFO CONTRATO
            $query = "SELECT * FROM contracts WHERE client_id = '$cliente'";
            $result_query = mysqli_query($con,$query);
            $assoc_e = mysqli_fetch_assoc($result_query);
            $contrato = $assoc_e['id'];
            $servidor = $assoc_e['server_configuration_id'];
            $ip = $assoc_e['ip'];
            $servicio = $assoc_e['servicio'];
            
            //HABILITAR EN MK
            $info_mk = "SELECT * FROM mikrotik WHERE id = '$servidor'";
            $result = mysqli_query($con,$info_mk);
            $assoc_mk = mysqli_fetch_assoc($result);
            $mk_puerto_api = $assoc_mk['puerto_api'];
            $mk_usuario = $assoc_mk['usuario'];
            $mk_clave = $assoc_mk['clave'];
            $mk_ip = $assoc_mk['ip'];
            
            if($mk_puerto_api) {
                $API = new RouterosAPI();
                $API->port = $mk_puerto_api;
                    
                if ($API->connect($mk_ip,$mk_usuario,$mk_clave)) {
                    $API->write('/ip/firewall/address-list/print', TRUE);
                    $ARRAYS = $API->read();
                        
                    //BUSCAMOS EL ID POR LA IP DEL CONTRATO
                    $API->write('/ip/firewall/address-list/print', false);
                    $API->write('?address='.$ip, false);
                    $API->write('?list=morosos',false);
                    $API->write('=.proplist=.id');
                    $ARRAYS = $API->read();
                    
                    //REMOVEMOS EL ID DE LA ADDRESS LIST                    
                    if(count($ARRAYS)>0){
                        $API->write('/ip/firewall/address-list/remove', false);
                        $API->write('=.id='.$ARRAYS[0]['.id']);
                        $READ = $API->read();
                    }

                    //AGREGAMOS A IP_AUTORIZADAS#
                    $API->comm("/ip/firewall/address-list/add", array(
                        "address" => $ip,
                        "comment" => $servicio,
                        "list" => 'ips_autorizadas'
                        )
                    );
                }
            }
            
            //ELIMINAMOS REGISTROS DEL CRM
            $query = "DELETE FROM crm WHERE estado IN (0,2,3,6) AND cliente = '$cliente'";
            mysqli_query($con,$query);
            
            $json['success'] = 'true';
            $json['title']   = 'Pago registrado correctamente en la plataforma, siga disfrutando de nuestros servicios.';
            $json['icon']    = 'success';
            echo json_encode($json);
            exit;
        }else{
            $json['success'] = 'false';
            $json['title']   = 'Su pago ya se encuentra asociada a una factura, siga disfrutando de nuestros servicios.';
            $json['icon']    = 'error';
            echo json_encode($json);
            exit;
        }
   }
?>