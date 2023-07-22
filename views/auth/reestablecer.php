<div class="contenedor reestablecer">

<?php include_once __DIR__ . '/../templates/nombre_sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu Nuevo Password</p>

        <?php include_once __DIR__ . '/../templates/alertas.php' ?>
    
    <?php if($mostrar) { ?>
    <form class="formulario" method="POST"> <!--MANDAMOS A URL ACTUAL, ASI NO PERDEMOS EL TOKEN-->

    <div class="campo"> 
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Tu password">
    </div>

    <input class="boton" type="submit" value="Guardar Password">

    </form>

    <?php } ?>

    <div class="acciones">
        <a href="/crear">Crear Una Cuenta</a>
        <a href="/olvide">¿Olvidaste tu Password?</a>
    </div>

    </div>

</div>