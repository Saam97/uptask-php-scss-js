<?php 
        session_start();

    if($_SESSION['login'] === true){
        header('location: /dashboard');
        debuguear($_SESSION);
    }

?>