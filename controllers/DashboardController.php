<?php

namespace Controllers;

use Model\Proyecto;
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

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
        ]);
    }
}