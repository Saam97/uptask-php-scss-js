<div class="contenedor login">


<?php include_once __DIR__ . '/../templates/nombre_sitio.php'; 
    include_once __DIR__ . '/../templates/logueado.php'; 
?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesion</p>

        <?php include_once __DIR__ . '/../templates/alertas.php' ?>
        
    <form class="formulario" action="/" method="POST">

    <div class="campo"> 
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Tu email">
    </div>

    <div class="campo"> 
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Tu password">
    </div>

    <input class="boton" type="submit" value="Iniciar Sesion">

    </form>

    <div class="acciones">
        <a href="/crear">Crear Una Cuenta</a>
        <a href="/olvide">¿Olvidaste tu Password?</a>
    </div>

    </div>

</div>