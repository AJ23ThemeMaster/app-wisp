<?php
    if ((isset($_POST['id_cliente']) && !empty($_POST['id_cliente']))) {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            include "include/conexion.php";
            include "include/routeros_api.class.php";
            
            $API = new RouterosAPI();
            $data = new StdClass();
            
            $id_cliente = $_POST['id_cliente'];
            $fecha      = date("Y-m-d h:i:s");
            
            //CONTRATOS
                $info_contrato = "SELECT * FROM contracts WHERE client_id = '$id_cliente' AND status = '1' AND state = 'enabled'";
                $result = mysqli_query($con,$info_contrato);
                $assoc_contrato = mysqli_fetch_assoc($result);
                $id_contrato = $assoc_contrato['id'];
                $id_mikrotik = $assoc_contrato['server_configuration_id'];
                $ip = $assoc_contrato['ip'];
                
            //MIKROTIK
                $info_mk = "SELECT puerto_api, usuario, clave, ip FROM mikrotik WHERE id = '$id_mikrotik'";
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
                        $API->write("/ping",false);
                        $API->write("=address=".$ip,false);
                        $API->write("=count=4",true);
                        $READ = $API->read(false);
                        $ARRAY = $API->parseResponse($READ);
                        
                        $API->disconnect();
                        
                        if(count($ARRAY)>0){
                            if($ARRAY[0]["received"]!=$ARRAY[0]["sent"]){
                                $json['success'] = 'false';
                                $json['title']   = mb_strtoupper('Usted no cuenta con servicio de internet llame a la oficina.');
                                $json['icon']    = 'error';
                                echo json_encode($json);
                                exit;
                            }else{
                                $json['success'] = 'true';
                                $json['title']   = mb_strtoupper('Su servicio se encuentra en correcto funcionamiento');
                                $json['icon']    = 'success';
                                echo json_encode($json);
                                exit;
                            }
                        }
                    }else{
                        $json['success'] = 'false';
                        $json['title']   = mb_strtoupper('Hemos tenido inconvenientes, intente nuevamente o comuniquese con la administracion de '.$title);
                        $json['icon']    = 'error';
                        echo json_encode($json);
                        exit;
                    }
                }else{
                    $json['success'] = 'false';
                    $json['icon']    = 'error';
                    $json['text']    = mb_strtoupper('Ha ocurrido un error inesperado, intente de nuevo');
                    $json['title']   = 'DISCULPE';
                    echo json_encode($json);
                    exit;
                }
        }else{
            $json['success'] = 'false';
            $json['icon']    = 'error';
            $json['text']    = mb_strtoupper('Ha ocurrido un error inesperado, intente de nuevo');
            $json['title']   = 'DISCULPE';
            echo json_encode($json);
            exit;
        }
    }else{
    	$json['success'] = 'false';
        $json['icon']    = 'error';
        $json['text']    = mb_strtoupper('Ha ocurrido un error inesperado, intente de nuevo');
        $json['title']   = 'DISCULPE';
        echo json_encode($json);
        exit;
    }
?>