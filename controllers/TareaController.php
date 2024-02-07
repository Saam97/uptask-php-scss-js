<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;
use MVC\Router;

class TareaController{

    public static function index(){
        session_start();

        //obtener id
        $proyectoid = $_GET['id'];
        
        if(!$proyectoid) header('location: /dasboard');

        //buscar proyecto por id
        $Proyecto = Proyecto::where('url', $proyectoid);

        if(!$Proyecto || $Proyecto->propietarioid !== $_SESSION['id']) header('location: /404');

        //buscar tareas asociadas al id del proyecto
        $tareas = Tarea::BelongsTo('proyectoid', $Proyecto->id);
        
        echo json_encode(['tareas' => $tareas ]);
    }

    public static function crear(){
    
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();//para el id del user
            $proyectoid = $_POST['proyectoid'];
            
            $proyecto = Proyecto::where('url', $proyectoid); //buscar la url  traer info

            // $respuesta = [
            //     'proyectoid' => $_POST['proyectoid']
            // ];

            if(!$proyecto || $proyecto->propietarioid !== $_SESSION['id']){ //en caso de no existir en la db
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Error al agg la tarea'
                    ];
                    echo json_encode($respuesta);
                    return;
            }

            //todo bien
            
            $tarea = new Tarea($_POST);
            $tarea->proyectoid = $proyecto->id;
            $resultado = $tarea->guardar();

            $respuesta = [ 
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea creada correctamente',
                'proyectoid' => $proyecto->id
            ];

                echo json_encode($respuesta);

            //echo json_encode($_POST);
        }


    }

    public static function actualizar(){
        
        //RECIBIR DATOS DE TAREAS.JS Y DAMOS RESPUESTA
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //echo json_encode($_POST); ver lo que enviaron

            //Validar que el proyecto exista, busca
            $proyecto = Proyecto::where('url', $_POST['proyectoid']);

            //ver que encontró
            //echo json_encode(['proyecto' => $proyecto]);

            session_start();
            if(!$proyecto || $proyecto->propietarioid !== $_SESSION['id']){ //en caso de no existir en la db
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Error al actualizar la tarea'
                    ];
                    echo json_encode($respuesta);
                    return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoid = $proyecto->id; //sincronizar, que todo tengan los mismos datos e id


            $resultado = $tarea->guardar();
            if($resultado){
                $respuesta = [ 
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoid' => $proyecto->id,
                    'mensaje' => 'Actualizado correctamente'
                ];

                echo json_encode( ['respuesta' => $respuesta] );
            }
            //echo json_encode(['resultado' => $resultado]); solo puede haber un json encode
        }
    }

    public static function Eliminar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //Validar que el proyecto exista, busca
            $proyecto = Proyecto::where('url', $_POST['proyectoid']);

            //ver que encontró
            //echo json_encode(['proyecto' => $proyecto]);

            session_start();
            if(!$proyecto || $proyecto->propietarioid !== $_SESSION['id']){ //en caso de no existir en la db
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Error al actualizar la tarea'
                    ];
                    echo json_encode($respuesta);
                    return;
            }

            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Eliminado Correctamente',
                'tipo' => 'exito'
            ];

            echo json_encode($resultado );

        }
    }
}