<div class="contenedor olvide">

    <?php include_once __DIR__ . '/../templates/nombre_sitio.php' ?>

    <div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <p class="descripcion-pagina">Recupera tu Acceso a Uptask</p>

        
    <form class="formulario" action="/olvide" method="POST">

    <div class="campo"> 
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Tu email" novalidate>
    </div>

    <input class="boton" type="submit" value="Enviar Instrucciones">

    </form>

    <div class="acciones">
        <a href="/">Iniciar Sesion</a>
        <a href="/crear">¿Quieres Registrarte?</a>
    </div>

    </div>

</div>