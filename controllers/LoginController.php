<?php

namespace Controllers;

use Clases\Email;
use MVC\Router; 
use Model\Usuario;

class LoginController {
    
    public static function login(Router $router) {
        
        $usuario = new Usuario;
        
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $usuario = new Usuario($_POST);
            
            $alertas = $usuario->validarLogin();
            
            if(empty($alertas)) {
                
                $auth = Usuario::where("email",$usuario->email);
                
                if(!$auth) {
                    
                    Usuario::setAlerta("error","El correo electrónico o la contraseña son incorrectas");
                    
                }else {
                    
                    if($auth->validarPasswordAndToken($usuario->password)) {
                        
                        //Autenticar al usuario
                        session_start();
                        
                        $_SESSION["usuario_id"] = $auth->id;
                        $_SESSION["nombre"] = $auth->nombre . ' ' . $auth->apellido;
                        $_SESSION["email"] = $auth->email;
                        $_SESSION["login"] = true;
                        
                        if($auth->admin) {
                            
                            $_SESSION["admin"] = $auth->admin ?? NULL;
                            header("Location: /admin");
                            exit();
                            
                        }else{
                            
                            header("Location: /client");
                            exit();
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
        $alertas = Usuario::getAlertas();
        
        $router->render("auth/login",[
            "alertas" => $alertas,
            "usuario" => $usuario
        ]);
    }
    
    public static function logout(Router $router) {
        
        //session_start();

        $_SESSION = [];

        header("Location: /");
        exit();
        
    }
    
    public static function forget(Router $router) {
        
        $alertas = [];
        
        $auth = new Usuario;
        
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $auth = new Usuario($_POST);
            
            $alertas = $auth->validarEmail();
            
            if(empty($alertas)) {
                
                $usuario = Usuario::where("email",$auth->email);
                
                if($usuario) {
                    
                    $usuario->crearToken();
                    $usuario->guardar();
                    
                    //Enviar correo electrónico
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarRecuperacion();
                    
                }
                
                Usuario::setAlerta("exito","En caso de que tu correo electrónico sea válido, se ha enviado un correo para recuperar la cuenta.");
                
            }
            
        }
        
        $alertas = Usuario::getAlertas();
        
        $router->render("auth/forget",[
            "alertas" => $alertas,
            "auth" => $auth
        ]);
    }
    
    public static function restore(Router $router) {
        
        $alertas = [];
        $vacio = false;
        
        //Sanitizar entrada
        $token = s($_GET["token"]);
        
        $usuario = Usuario::where("token",$token);
        
        if(empty($usuario)){
            
            //Mostrar mensaje de error
            Usuario::setAlerta("error","Token no valido");
            $vacio = true;
            
        }
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];
            
            if (!$password || !$confirm_password) {
                
                Usuario::setAlerta("error", "La contraseña y la confirmación de la contraseña son obligatorias");
                
            } else {
                
                if ($password !== $confirm_password) {
                    
                    Usuario::setAlerta("error", "Las contraseñas no coinciden");
                    
                } else if (strlen($password) < 6) {
                    
                    Usuario::setAlerta("error", "La contraseña debe contener al menos 6 caracteres");
                    
                } else {
                    
                    // Hashear contraseña y actualizar usuario
                    $usuario->password = $password;
                    $usuario->hashPassword();
                    $usuario->confirmado = 1;
                    $usuario->token = null;
                    
                    // Guardar usuario actualizado
                    if ($usuario->guardar()) {
                        header("Location: /");
                        exit(); // Terminar la ejecución después de redirigir
                    }
                }
            }
        }
        
        $alertas = Usuario::getAlertas();
        
        $router->render("auth/restore",[
            "alertas" => $alertas,
            "vacio" => $vacio
        ]);
        
    }
    
    public static function create_account(Router $router) {
        
        $usuario = new Usuario;
        
        $alertas = Usuario::getAlertas();
        
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validarCrearCuenta();
            
            if(empty($alertas)){
                
                $existe = $usuario->existeUsuario();
                
                if($existe->num_rows) {
                    
                    $alertas = Usuario::getAlertas();
                    
                }else{
                    
                    //Hashear Password
                    $usuario->hashPassword();
                    
                    //Generar un token único
                    $usuario->crearToken();
                    
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    
                    $eviado = $email->enviarConfirmacion();
                    
                    if($eviado) {
                        
                        $resultado = $usuario->guardar();
                        
                        if($resultado) {
                            
                            header("Location: /message");
                            exit();
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
        $router->render("auth/create_account",[
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }
    
    public static function confirm_account(Router $router) {
        
        $alertas = [];
        
        //Sanitizar entrada
        $token = s($_GET["token"]);
        
        $usuario = Usuario::where("token",$token);
        
        if(empty($usuario)){
            
            //Mostrar mensaje de error
            Usuario::setAlerta("error","Token no valido");
            
        }else{
            
            $usuario->confirmado = 1;
            $usuario->token = null;
            $usuario->guardar();
            
            Usuario::setAlerta("exito","Cuenta confirmada correctamente");
            
        }
        
        $alertas = Usuario::getAlertas();
        
        $router->render("auth/confirm_account",[
            "alertas" => $alertas
        ]);
    }
    
    public static function message(Router $router) {
        
        $router->render("auth/message");
        
    }
    
}