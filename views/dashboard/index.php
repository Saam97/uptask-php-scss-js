<?php include_once __DIR__ . '/header-dashboard.php'; ?>


<?php if( count($proyectos) === 0 ) { ?>

    <p class="no-proyectos"> No hay proyectos Aun <a href="/crear-proyecto">Comienza Creando Uno</a> </p>
    

<?php } else{  ?>

    <ul class="listado-proyectos">
        <?php foreach($proyectos as $proyec) { ?>

            <li class="proyecto">
                <a href="/proyecto?id=<?php echo $proyec->url ?>">
                    <?php echo $proyec->proyecto ?>
                </a>
            </li>

        <?php } ?>
    </ul>

<?php } ?>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>