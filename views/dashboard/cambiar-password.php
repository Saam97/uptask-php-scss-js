<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver al Perfil</a>

    <form action="/cambiar-password" method="POST" class="formulario">
        <div class="campo">
            <label for="nombre">PassWord Acutal: </label>
            <input 
                type="password"
                name="password_actual"
                placeholder="Tu password actual"
            >
        </div>

        <div class="campo">
            <label for="nombre">password Nuevo: </label>
            <input 
                type="password"
                name="password_nuevo"
                placeholder="Tu password Nuevo"
            >
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>


<?php include_once __DIR__ . '/footer-dashboard.php'; ?>