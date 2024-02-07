<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuarios;
use MVC\Router;


class DashboardController{


    public static function index(Router $router){

        session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::BelongsTo('propietarioid', $id);

        

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
            
        ]);

    }

    public static function crear(Router $router){
        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);

            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                //Generar URL Unica
                $hast = md5(uniqid());
                $proyecto->url = $hast;

                //Almacenar Creador
                $proyecto->propietarioid = $_SESSION['id'];

                //guardar todo
                $proyecto->guardar();
                
                //Redireccionar al Proyecto Creado
                header('location: /proyecto?id='. $proyecto->url);

                
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();

        $token = $_GET['id'];
        if(!$token) header('location: /dashboard');

        //Revisar Los proyectos del Usuario, Solo sus creaciones.
        $proyecto = Proyecto::where('url', $token);
        
        if($proyecto->propietarioid !== $_SESSION['id'] ){//Si id del usuario es diferente a la sesion actual, no es el dueño
            header('location: /dashboard');
        }



        //debuguear($proyecto);
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }


    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuarios::find($_SESSION['id']);
        //debuguear($usuario);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //tener el objeto con los datos nuevos
            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validar_perfil();

            if(empty($alertas)){

                $existeUsuario = Usuarios::where('email', $usuario->email);
                
                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    Usuarios::setAlerta('error','Este correo ya esta registrado');
                    $alertas = $usuario->getAlertas();
                }else{
                    $usuario->guardar();
                    
                    Usuarios::setAlerta('exito','Actualizado correctamente!');
                    $alertas = $usuario->getAlertas();
    
                    //para mostrar el nombre nuevo a la parte superior
                    $_SESSION['nombre'] = $usuario->nombre;
                }


            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = Usuarios::find($_SESSION['id']);

            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();
            
            if(empty($alertas)){
                $resultado = $usuario->comprobar_password();

                if($resultado){
                    $usuario->password = $usuario->password_nuevo;

                    //eliminamos del objto
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //Hasear el password
                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();

                    if($resultado){
                        Usuarios::setAlerta('exito', 'Password Actualizado!');
                        $alertas = $usuario->getAlertas();
                    }

                }else{
                    Usuarios::setAlerta('error', 'Password Incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }

        
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'cambiar password',
            'alertas' => $alertas
        ]);

    }
}