<div class="contenedor crear">

    <?php include_once __DIR__ . '/../templates/nombre_sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Registrate en Uptask</p>

        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        
    <form class="formulario" action="/crear" method="POST">

    <div class="campo"> 
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" value="<?php echo $usuario->nombre ;?>">
    </div>

    <div class="campo"> 
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Tu email" value="<?php echo $usuario->email ;?>">
    </div>

    <div class="campo"> 
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Tu password">
    </div>

    <div class="campo"> 
        <label for="password2">Repite tu Password:</label>
        <input type="password" name="password2" id="password2" placeholder="Repite Tu password">
    </div>

    <input class="boton" type="submit" value="Crear Cuenta">

    </form>

    <div class="acciones">
        <a href="/">Iniciar Sesion</a>
        <a href="/olvide">¿Olvidaste tu Password?</a>
    </div>

    </div>

</div>