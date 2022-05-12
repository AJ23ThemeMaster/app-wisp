<?php
    header('Content-Type: application/json');
    date_default_timezone_set('America/Bogota');
    include "include/conexion.php";
    
    if($_POST){
        $fecha = date('Y-m-d');
        $nit_cliente = str_replace("'", "`", $_POST['nit']);
        $pass        = str_replace("'", "`", $_POST['pass']);
        $fecha       = date("Y-m-d h:i:s");

        $sql = "SELECT * FROM integracion WHERE tipo = 'SMS' AND lectura = '1' AND status = '1'";
        $sms = mysqli_fetch_assoc(mysqli_query($con, $sql));

        $sms_login     = $sms['user'];
        $sms_password  = $sms['pass'];
        $sms_api_key   = $sms['api_key'];
        $sms_proveedor = $sms['nombre'];
        
        if (isset($_POST['user-checkbox']) && $_POST['user-checkbox'] == '1')
            $marketing = 1;
        else
            $marketing = 0;
        
        $query = "SELECT * FROM contactos WHERE nit = '$nit_cliente'";
        $result_query = mysqli_query($con,$query);
        $assoc_c = mysqli_fetch_assoc($result_query);
        $id_cliente  = $assoc_c['id'];
        $celular_cliente  = $assoc_c['celular'];
        $email_cliente  = $assoc_c['email'];
        
        if($id_cliente){
            $query = "SELECT * FROM contracts WHERE client_id = '$id_cliente'";
            $result_query = mysqli_query($con,$query);
            $assoc_e = mysqli_fetch_assoc($result_query);
            $id_contrato  = $assoc_e['id'];
            
            if($id_contrato){
                $query = "SELECT * FROM usuarios_app WHERE id_cliente = '$id_cliente'";
                $result_query = mysqli_query($con,$query);
                $assoc_a = mysqli_fetch_assoc($result_query);
                $id_usuario  = $assoc_a['id'];
                
                if($id_usuario){
                    $json['success'] = 'false';
                    $json['icon']    = 'error';
                    $json['text']    = 'La identificación indicada ya está registrada en '.$title;
                    $json['title']   = 'DISCULPE';
                    echo json_encode($json);
                    exit;
                }else{
                    $codigo = substr(str_shuffle('123456789'), 0, 6);
                    if($sms_login && $sms_password && $sms_proveedor == 'Colombia RED'){
                        $hora = date('G');
                        switch ($hora) {
                            case (($hora > 0) AND ($hora < 12)):
                                $mensaje = "Buenos dias, su codigo de verificacion ".$title." es ".$codigo;
                                $mensaje_email = "Buenos dias, su codigo de verificacion ".$title." es";
                            break;
                            case (($hora >= 12) AND ($hora < 19)):
                                $mensaje = "Buenas tardes, su codigo de verificacion ".$title." es ".$codigo;
                                $mensaje_email = "Buenas tardes, su codigo de verificacion ".$title." es";
                            break;
                            case (($hora > 18) AND ($hora <=23 )):
                                $mensaje = "Buenas noches, su codigo de verificacion ".$title." es ".$codigo;
                                $mensaje_email = "Buenas noches, su codigo de verificacion ".$title." es";
                            break;
                        }

                        $post['to'] = array('+57'.$celular_cliente);
                        $post['text'] = $mensaje;
                        $post['from'] = $title_corto;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://masivos.colombiared.com.co/Api/rest/message");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
                        curl_setopt(
                            $ch,
                            CURLOPT_HTTPHEADER,
                            array(
                                "Accept: application/json",
                                "Authorization: Basic " . base64_encode($sms_login . ":" . $sms_password)
                            )
                        );
                        $result = curl_exec($ch);
                        $err  = curl_error($ch);
                        curl_close($ch);

                        if ($err) {
                            $json['success'] = 'false';
                            $json['icon']    = 'error';
                            $json['text']    = $err;
                            $json['title']   = 'DISCULPE HA OCURRIDO UN ERROR';
                            echo json_encode($json);
                            exit;
                        } else {
                            $response = json_decode($result, true);
                            if (isset($response['error'])) {
                                $json['success'] = 'false';
                                $json['icon']    = 'error';
                                $json['text']    = $response['error']['description'];
                                $json['title']   = 'DISCULPE HA OCURRIDO UN ERROR';
                                echo json_encode($json);
                                exit;
                            }else{
                                $query = "INSERT INTO usuarios_app(id_cliente,uid_cliente,user,password,status,marketing,codigo,sms,created_at,updated_at) VALUES ('$id_cliente','$id_cliente','$nit_cliente','$pass','1','$marketing','$codigo','1','$fecha','$fecha')";
                                mysqli_query($con,$query);

                                $to = $email_cliente;
                                $subject = $title.": Código de Verificación";
                                $headers = "MIME-Version: 1.0" . "\r\n";
                                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n" . "From: ".$title." ".$empresa_email."\r\n";

                                $message = '
                                <!DOCTYPE html>
                                <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
                                <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width,initial-scale=1">
                                <meta name="x-apple-disable-message-reformatting">
                                <title></title>
                                <!--[if mso]>
                                <noscript>
                                <xml>
                                <o:OfficeDocumentSettings>
                                <o:PixelsPerInch>96</o:PixelsPerInch>
                                </o:OfficeDocumentSettings>
                                </xml>
                                </noscript>
                                <![endif]-->
                                <style>
                                table, td, div, h1, p {font-family: Arial, sans-serif;}
                                </style>
                                </head>
                                <body style="margin:0;padding:0;">
                                <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                                <tr>
                                <td align="center" style="padding:0;">
                                <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                                <tr>
                                <td align="center" style="padding:0;background:#eeeeee;">
                                <img src="'.$img_logo.'" alt="" width="300" style="height:auto;display:block;" />
                                </td>
                                </tr>
                                <tr>
                                <td style="padding:36px 30px 20px 30px;">
                                <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                <td style="padding:0 0 20px 0;color:#153643;">
                                <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">
                                CÓDIGO OTP DE VERIFICACIÓN
                                </h1>
                                <hr>
                                <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align: justify;">
                                '.$mensaje_email.'
                                </p>
                                <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;color: '.$color.';">
                                '.$codigo.'
                                </h1>
                                <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align: justify;">
                                El código de verificación será válido durante 30 minutos. Por favor, no comparta este código con nadie. Si no inició esta operación, comuníquese con la administración de '.$title.'.
                                </p>
                                </td>
                                </tr>
                                </table>
                                <p style="margin:0 0 12px 0;font-size:12px;line-height:24px;font-family:Arial,sans-serif;text-align: center;">
                                Este correo electrónico es generado automaticamente. No lo responda.
                                </p>
                                </td>
                                </tr>
                                <tr>
                                <td style="padding:30px;background:'.$color.';">
                                <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                <tr>
                                <td style="padding:0;width:100%;" align="center">
                                <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                Copyright © '.$title.' 2022 - Todos los derechos reservados
                                </p>
                                </td>
                                </tr>
                                </table>
                                </td>
                                </tr>
                                </table></td></tr></table></body></html>';

                                mail($to, $subject, $message, $headers);

                                $json['success']     = 'true';
                                $json['codigo']      = $codigo;
                                $json['id_cliente']  = $id_cliente;
                                echo json_encode($json);
                                exit;
                            }
                        }
                    }elseif($sms_login && $sms_password && $sms_proveedor == 'Hablame SMS'){
                        $hora = date('G');
                        switch ($hora) {
                            case (($hora > 0) AND ($hora < 12)):
                                $mensaje = "Buenos dias, su codigo de verificacion ".$title." es ".$codigo;
                                $mensaje_email = "Buenos dias, su codigo de verificacion ".$title." es";
                            break;
                            case (($hora >= 12) AND ($hora < 19)):
                                $mensaje = "Buenas tardes, su codigo de verificacion ".$title." es ".$codigo;
                                $mensaje_email = "Buenas tardes, su codigo de verificacion ".$title." es";
                            break;
                            case (($hora > 18) AND ($hora <=23 )):
                                $mensaje = "Buenas noches, su codigo de verificacion ".$title." es ".$codigo;
                                $mensaje_email = "Buenas noches, su codigo de verificacion ".$title." es";
                            break;
                        }

                        $post['toNumber'] = $celular_cliente;
                        $post['sms'] = $mensaje;

                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api103.hablame.co/api/sms/v3/send/marketing',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',CURLOPT_POSTFIELDS => json_encode($post),
                            CURLOPT_HTTPHEADER => array(
                                'account: '.$sms_login,
                                'apiKey: '.$sms_api_key,
                                'token: '.$sms_password,
                                'Content-Type: application/json'
                            ),
                        ));
                        $result = curl_exec ($curl);
                        $err  = curl_error($curl);
                        curl_close($curl);

                        $response = json_decode($result, true);
                        if(isset($response['error'])){
                            $json['success'] = 'false';
                            $json['icon']    = 'error';
                            $json['text']    = $response['error']['details'];
                            $json['title']   = 'DISCULPE HA OCURRIDO UN ERROR';
                            echo json_encode($json);
                            exit;
                        }else{
                            if (isset($response['error'])) {
                                $json['success'] = 'false';
                                $json['icon']    = 'error';
                                $json['text']    = $response['error']['description'];
                                $json['title']   = 'DISCULPE HA OCURRIDO UN ERROR';
                                echo json_encode($json);
                                exit;
                            }else{
                                $query = "INSERT INTO usuarios_app(id_cliente,uid_cliente,user,password,status,marketing,codigo,sms,created_at,updated_at) VALUES ('$id_cliente','$id_cliente','$nit_cliente','$pass','1','$marketing','$codigo','1','$fecha','$fecha')";
                                mysqli_query($con,$query);

                                $to = $email_cliente;
                                $subject = $title.": Código de Verificación";
                                $headers = "MIME-Version: 1.0" . "\r\n";
                                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n" . "From: ".$title." ".$empresa_email."\r\n";

                                $message = '
                                <!DOCTYPE html>
                                <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
                                <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width,initial-scale=1">
                                <meta name="x-apple-disable-message-reformatting">
                                <title></title>
                                <!--[if mso]>
                                <noscript>
                                <xml>
                                <o:OfficeDocumentSettings>
                                <o:PixelsPerInch>96</o:PixelsPerInch>
                                </o:OfficeDocumentSettings>
                                </xml>
                                </noscript>
                                <![endif]-->
                                <style>
                                table, td, div, h1, p {font-family: Arial, sans-serif;}
                                </style>
                                </head>
                                <body style="margin:0;padding:0;">
                                <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                                <tr>
                                <td align="center" style="padding:0;">
                                <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                                <tr>
                                <td align="center" style="padding:0;background:#eeeeee;">
                                <img src="'.$img_logo.'" alt="" width="300" style="height:auto;display:block;" />
                                </td>
                                </tr>
                                <tr>
                                <td style="padding:36px 30px 20px 30px;">
                                <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                <td style="padding:0 0 20px 0;color:#153643;">
                                <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">
                                CÓDIGO OTP DE VERIFICACIÓN
                                </h1>
                                <hr>
                                <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align: justify;">
                                '.$mensaje_email.'
                                </p>
                                <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;color: '.$color.';">
                                '.$codigo.'
                                </h1>
                                <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align: justify;">
                                El código de verificación será válido durante 30 minutos. Por favor, no comparta este código con nadie. Si no inició esta operación, comuníquese con la administración de '.$title.'.
                                </p>
                                </td>
                                </tr>
                                </table>
                                <p style="margin:0 0 12px 0;font-size:12px;line-height:24px;font-family:Arial,sans-serif;text-align: center;">
                                Este correo electrónico es generado automaticamente. No lo responda.
                                </p>
                                </td>
                                </tr>
                                <tr>
                                <td style="padding:30px;background:'.$color.';">
                                <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                <tr>
                                <td style="padding:0;width:100%;" align="center">
                                <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                Copyright © '.$title.' 2022 - Todos los derechos reservados
                                </p>
                                </td>
                                </tr>
                                </table>
                                </td>
                                </tr>
                                </table></td></tr></table></body></html>';

                                mail($to, $subject, $message, $headers);

                                $json['success']     = 'true';
                                $json['codigo']      = $codigo;
                                $json['id_cliente']  = $id_cliente;
                                echo json_encode($json);
                                exit;
                            }

                        }
                    }else{
                        $query = "INSERT INTO usuarios_app(id_cliente,uid_cliente,user,password,status,marketing,codigo,sms,created_at,updated_at) VALUES ('$id_cliente','$id_cliente','$nit_cliente','$pass','1','$marketing','$codigo','1','$fecha','$fecha')";
                            mysqli_query($con,$query);

                            $to = $email_cliente;
                            $subject = $title.": Código de Verificación";
                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n" . "From: ".$title." ".$empresa_email."\r\n";

                            $message = '
                            <!DOCTYPE html>
                            <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
                            <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width,initial-scale=1">
                            <meta name="x-apple-disable-message-reformatting">
                            <title></title>
                            <!--[if mso]>
                            <noscript>
                            <xml>
                            <o:OfficeDocumentSettings>
                            <o:PixelsPerInch>96</o:PixelsPerInch>
                            </o:OfficeDocumentSettings>
                            </xml>
                            </noscript>
                            <![endif]-->
                            <style>
                            table, td, div, h1, p {font-family: Arial, sans-serif;}
                            </style>
                            </head>
                            <body style="margin:0;padding:0;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                            <tr>
                            <td align="center" style="padding:0;">
                            <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                            <tr>
                            <td align="center" style="padding:0;background:#eeeeee;">
                            <img src="'.$img_logo.'" alt="" width="300" style="height:auto;display:block;" />
                            </td>
                            </tr>
                            <tr>
                            <td style="padding:36px 30px 20px 30px;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                            <td style="padding:0 0 20px 0;color:#153643;">
                            <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">
                            CÓDIGO OTP DE VERIFICACIÓN
                            </h1>
                            <hr>
                            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align: justify;">
                            '.$mensaje_email.'
                            </p>
                            <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;color: '.$color.';">
                            '.$codigo.'
                            </h1>
                            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align: justify;">
                            El código de verificación será válido durante 30 minutos. Por favor, no comparta este código con nadie. Si no inició esta operación, comuníquese con la administración de '.$title.'.
                            </p>
                            </td>
                            </tr>
                            </table>
                            <p style="margin:0 0 12px 0;font-size:12px;line-height:24px;font-family:Arial,sans-serif;text-align: center;">
                            Este correo electrónico es generado automaticamente. No lo responda.
                            </p>
                            </td>
                            </tr>
                            <tr>
                            <td style="padding:30px;background:'.$color.';">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                            <tr>
                            <td style="padding:0;width:100%;" align="center">
                            <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                            Copyright © '.$title.' '.date('Y').' - Todos los derechos reservados
                            </p>
                            </td>
                            </tr>
                            </table>
                            </td>
                            </tr>
                            </table></td></tr></table></body></html>';

                            mail($to, $subject, $message, $headers);

                            $json['success']     = 'true';
                            $json['codigo']      = $codigo;
                            $json['id_cliente']  = $id_cliente;
                            echo json_encode($json);
                            exit;
                    }
                }
            }else{
                $json['success'] = 'false';
                $json['icon']    = 'error';
                $json['text']    = 'La identificación indicada no está relacionada con ningún contrato';
                $json['title']   = 'DISCULPE';
                echo json_encode($json);
                exit;
            }
        }else{
            $json['success'] = 'false';
            $json['icon']    = 'error';
            $json['text']    = 'La identificación indicada no está relacionada con ningún cliente';
            $json['title']   = 'DISCULPE';
            echo json_encode($json);
            exit;
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
