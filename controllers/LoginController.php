<?php

namespace Controllers;

use Classes\Email;
use Model\Usuarios;
use MVC\Router;

class LoginController{


    public static function login(Router $router){
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuarios($_POST);

            //validar
            $alertas = $auth->validarlogin();

            if(empty($alertas)){

                //comprobar usuario existe

                $usuario = Usuarios::where('email', $auth->email);

                if(!$usuario || !$usuario->confirmado){
                    Usuarios::setAlerta('error', 'ESTE USUARIO NO EXISTE O NO ESTÁ CONFIRMADO');
                }else{
                    //Usuario existe
                    if( password_verify($_POST['password'], $usuario->password)){
                        //Iniciamos session
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        header('location: /dashboard');

                    }else{
                        Usuarios::setAlerta('error', 'Password Incorrecto');
                    }

                }

            
            }
        }

        $alertas = Usuarios::getAlertas();
        //render a la vista
        $router->render('auth/login', [
            'titulo' => 'iniciar Sesion',
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        session_start();

        $_SESSION = [];

        header('location: /');
    }


    public static function crear(Router $router){
        
        $usuario = new Usuarios();
        $alertas = [];


        if($_SERVER['REQUEST_METHOD'] === 'POST'){
           $usuario->sincronizar($_POST);

           $alertas = $usuario->validarNuevacuenta();

           if(empty($alertas)){
            $existeUsuario = Usuarios::where('email', $usuario->email);

            if($existeUsuario){
             Usuarios::setAlerta('error', 'ESTE USUARIO YA ESTA REGISTRADO');
            }else{
                //hasspasword
                $usuario->hashPassword();

                //eliminar PASSWORD2 del obj
                unset($usuario->password2);

                //Generar Token
                $usuario->crearToken();

                //Guardar en la Db
                $resultado = $usuario->guardar();

                //enviar Email
                $email = new Email($usuario->email,$usuario->nombre, $usuario->token);
                $email->enviarConfirmacion();

                if($resultado){
                    header('location: /mensaje');
                }
            }
           } 


           
        }

        $alertas = Usuarios::getAlertas();
        //render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router){
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuarios($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alrtas)){

                $usuario = Usuarios::where('email', $usuario->email);//buscar
                
                if($usuario && $usuario->confirmado === '1'){
                    unset($usuario->password2);

                    //Generar Token
                    $usuario->crearToken();

                    //Actualizar Usuario
                    $usuario->guardar();

                    //enviar Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Imprimir alerta
                    Usuarios::setAlerta('exito', 'Intrucciones Enviadas a tu correo Electronico');


                }else{
                    //No existe
                    Usuarios::setAlerta('error', 'El Usuario No Existe');
                }

            }
        }


        $alertas = Usuarios::getAlertas();
        $router->render('auth/olvide', [
            'titulo' => 'Recuperar Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router){
        
        $mostrar = true;
        $alertas = [];
        $token = s($_GET['token']);

        if(!$token) header('location: /');

        $usuario = Usuarios::where('token', $token);
        
        if(empty($usuario)){
            Usuarios::setAlerta('error', 'Token No valido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //añadir nuevo pass
            $usuario->sincronizar($_POST);
            unset($usuario->password2);
            
            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                //hasear
                $usuario->hashPassword();

                //quitar token
                $usuario->token = null;

                //guardar
                $usuario->guardar();

                header('location: /');

                
            }
        }


        $alertas = Usuarios::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }
    
    public static function mensaje(Router $router){
      
        
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }

    public static function confirmar(Router $router){
        
        $token = s($_GET['token']); //obtener token de la url, sanitizar

        $usuario = Usuarios::where('token', $token); //buscar user por token

        if(empty($usuario)){
            Usuarios::setAlerta('error', 'TOKEN NO VALIDO');
        }else{
            unset($usuario->password2);
            $usuario->confirmado = 1;
            $usuario->token =null;

            $usuario->guardar();

            Usuarios::setAlerta('exito', 'Cuenta Comprobada correctamente');
        }
        

        if(!$token){
            header('location: /');
        }
        
        $alertas = Usuarios::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma Tu Cuenta',
            'alertas' => $alertas
        ]);
        
    }
}