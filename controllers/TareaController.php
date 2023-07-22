<?php

namespace Controllers;

use MVC\Router;

class TareaController{

    public static function index(){


    }

    public static function crear(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //respuesa a las consultas de la api
            // $array = [
            //     'respuesta' => true,
            //     'nombre' => 'juan'
            // ];

            echo json_encode($_POST);
        }


    }

    public static function actualizar(){
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
        }
    }

    public static function Eliminar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
        }
    }
}