<?php
namespace Model;

class Usuarios extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];


    // public $id; NO ES NECESARIO, EL ERROR ES DE INTERPRETACION POR PARTE DE VS
    // public $nombre;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? null;//aux para comparar password
        $this->password_actual = $args['password_actual'] ?? null;
        $this->password_nuevo = $args['password_nuevo'] ?? null;
        
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    public function validarNuevacuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre del usuario es obligatorio'; 
        }

        if(!$this->email){
            self::$alertas['error'][] = 'El email del usuario es obligatorio'; 
        }

        if(!$this->password){
            self::$alertas['error'][] = 'El password No puede ir Vacio'; 
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El password debe tener al Menos 6 Caracteres'; 
        }

        if($this->password !== $this->password2){
            self::$alertas['error'][] = 'Las Contraseñas deben ser iguales'; 
        }

        return self::$alertas;

    }

    //validar login
    public function validarlogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email del usuario es obligatorio'; 
        }

        if(!$this->password){
            self::$alertas['error'][] = 'El password No puede ir Vacio'; 
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'Ingresa un Email Valido'; 
        };

        return self::$alertas;
    }

    public function nuevo_password() : array{

        if(!$this->password_actual){
            self::$alertas['error'][] = 'El password No puede ir Vacio'; 
        }

        if(strlen($this->password_nuevo) < 6){
            self::$alertas['error'][] = 'El password debe tener al Menos 6 Caracteres'; 
        }        

        if(!$this->password_nuevo){
            self::$alertas['error'][] = 'El password No puede ir Vacio'; 
        }

        return self::$alertas;
    }

    //Comprobar el Password
    public function comprobar_password() : bool{ //retorna bool
        return password_verify($this->password_actual, $this->password);
    }

    public function hashPassword() : void{
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }


    public function crearToken() : void{
        $this->token = uniqid();
    }


    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email del usuario es obligatorio'; 
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'Ingresa un Email Valido'; 
        };

        return self::$alertas;
    }

    public function validarPassword(){

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El password debe tener al Menos 6 Caracteres'; 
        }

        if(!$this->password){
            self::$alertas['error'][] = 'El password No puede ir Vacio'; 
        }

        return self::$alertas;
    }

    public function validar_perfil(){

        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre del usuario es obligatorio'; 
        }

        if(!$this->email){
            self::$alertas['error'][] = 'El email del usuario es obligatorio'; 
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'Ingresa un Email Valido'; 
        };

        return self::$alertas;
    }


}


