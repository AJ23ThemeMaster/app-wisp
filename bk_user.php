<?php
    header('Content-Type: application/json');
    date_default_timezone_set('America/Bogota');
    
    if($_POST['option'] == 'OTP'){
        include "include/conexion.php";
        $usuario = $_POST['usuario'];
        
        $query = "SELECT * FROM contactos WHERE id = '$usuario' AND status = 1";
        $result_query = mysqli_query($con,$query);
        $assoc_c = mysqli_fetch_assoc($result_query);
        $id_cliente  = $assoc_c['id'];
        $celular_cliente  = $assoc_c['celular'];
        $email_cliente  = $assoc_c['email'];
        
        if($id_cliente){
            $codigo = substr(str_shuffle('123456789'), 0, 6);
            if($sms_login && $sms_password){
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

                $post['to'] = array('57'.$celular_cliente);
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
            }else{
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
            }

            $json['type']       = 'success';
            $json['id_cliente'] = $id_cliente;
            $json['otp']        = $codigo;
            echo json_encode($json);
            exit;
        }else{
            $json['type'] = 'error';
            $json['title'] = 'ERROR';
            $json['mensaje'] = 'Los datos suministrados son erróneos, intente nuevamente';
            echo json_encode($json);
            exit;
        }
    }
    
    if ((isset($_POST['id_cliente']) && !empty($_POST['id_cliente']))) {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            include "include/conexion.php";
            include "include/routeros_api.class.php";

            $id_cliente = $_POST['id_cliente'];
            $nombre = utf8_decode(mb_strtoupper($_POST['nombre']));
            $nit = $_POST['nit'];
            $celular = $_POST['celular'];
            $email = mb_strtoupper($_POST['email']);
            $direccion = utf8_decode(mb_strtoupper($_POST['direccion']));
            
            //CONTRATOS
            $query = "UPDATE contactos SET nombre = '$nombre', nit = '$nit', celular = '$celular', email = '$email', direccion = '$direccion' WHERE id = '$id_cliente'";
            mysqli_query($con,$query);

            $json['success'] = 'true';
            $json['title']   = mb_strtoupper('Sus datos han sido actualizados satisfactoriamente');
            $json['icon']    = 'success';
            echo json_encode($json);
            exit;
        }else{
            $json['success'] = 'false';
            $json['title']   = mb_strtoupper('Hemos tenido inconvenientes, intente nuevamente o comuniquese con la administración de '.$title);
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
?>
