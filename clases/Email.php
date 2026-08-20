<?php

namespace Clases;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    
    public $email;
    public $nombre;
    public $token;
    
    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
        
    }
    
    public function enviarConfirmacion() {
        
        //Crear una nueva instancia de PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = $_ENV["EMAIL_HOST"];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV["EMAIL_USERNAME"];
            $mail->Password   = $_ENV["EMAIL_PASSWORD"];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV["EMAIL_PORT"];
            
            // Destinatarios
            //$mail->setFrom("{$correo}","{$nombre}");
            $mail->setFrom('cuentas@salon.com');
            $mail->addAddress('cuentas@salon.com', 'Salón');
            
            // Contenido del correo
            $mail->Subject = 'Confirmar cuenta de Salón';
            
            //setHTML
            $mail->isHTML(true);
            $mail->CharSet = "UTF-8"; //Mensajes con acentos
            
            $contenido = "
                            <html>
                                <head>
                                    <style>
                                        .container {
                                            padding: 20px;
                                            background-color: #f9f9f9;
                                            font-family: Arial, sans-serif;
                                        }
                                        .content {
                                            padding: 20px;
                                        }
                                        .content p {
                                            margin-bottom: 15px;
                                        }
                                        .content strong {
                                            color: #0da6f3;
                                        }
                                        .btn {
                                            display: inline-block;
                                            padding: 8px 16px;
                                            background-color: #0da6f3;
                                            color: #fff;
                                            text-decoration: none;
                                            border-radius: 4px;
                                        }
                                        .btn:hover {
                                            background-color: #0c95db;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <div class='container'>
                                        <div class='content'>
                                            <p><strong>Hola:</strong> " . htmlspecialchars($this->nombre) . "</p>
                                            <p>Has creado tu cuenta en Salón. Por favor, confírmala haciendo clic en el siguiente enlace:</p>
                                            <p><a class='btn' href='".$_ENV["DOMAIN_URL"]."/confirm_account?token=".urlencode($this->token)."'>Confirmar cuenta</a></p>
                                            <p>Si no creaste esta cuenta, puedes ignorar este mensaje.</p>
                                        </div>
                                    </div>
                                </body>
                            </html>
                            ";
            
            $mail->Body    = $contenido;
            //$mail->AltBody = 'Esto es texto alternativo sin HTML';
            
            $mail->send(); //Retorna un booleano
            
            return true;
            
        } catch (Exception $e) {
            
            return false;
            
        }
        
    }
    
    public function enviarRecuperacion() {
        
        //Crear una nueva instancia de PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = $_ENV["EMAIL_HOST"];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV["EMAIL_USERNAME"];
            $mail->Password   = $_ENV["EMAIL_PASSWORD"];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV["EMAIL_PORT"];
            
            // Destinatarios
            //$mail->setFrom("{$correo}","{$nombre}");
            $mail->setFrom('cuentas@salon.com');
            $mail->addAddress('cuentas@salon.com', 'Salón');
            
            // Contenido del correo
            $mail->Subject = 'Restablecer tu contraseña';
            
            //setHTML
            $mail->isHTML(true);
            $mail->CharSet = "UTF-8"; //Mensajes con acentos
            
            $contenido = "
                            <html>
                                <head>
                                    <style>
                                        .container {
                                            padding: 20px;
                                            background-color: #f9f9f9;
                                            font-family: Arial, sans-serif;
                                        }
                                        .content {
                                            padding: 20px;
                                        }
                                        .content p {
                                            margin-bottom: 15px;
                                        }
                                        .content strong {
                                            color: #0da6f3;
                                        }
                                        .btn {
                                            display: inline-block;
                                            padding: 8px 16px;
                                            background-color: #0da6f3;
                                            color: #fff;
                                            text-decoration: none;
                                            border-radius: 4px;
                                        }
                                        .btn:hover {
                                            background-color: #0c95db;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <div class='container'>
                                        <div class='content'>
                                        <p><strong>Hola:</strong> " . htmlspecialchars($this->nombre) . "</p>
                                        <p>Has solicitado restablecer tu contraseña. Por favor, haz clic en el siguiente enlace para proceder:</p>
                                        <p><a class='btn' href='".$_ENV["DOMAIN_URL"]."/restore?token=".urlencode($this->token)."'>Restablecer contraseña</a></p>
                                        <p>Si no realizaste esta solicitud, puedes ignorar este mensaje.</p>
                                    </div>
                                    </div>
                                </body>
                            </html>
                            ";
            
            $mail->Body    = $contenido;
            //$mail->AltBody = 'Esto es texto alternativo sin HTML';
            
            $mail->send(); //Retorna un booleano
            
            
        } catch (Exception $e) {
            
            
            
        }
        
    }
    
}