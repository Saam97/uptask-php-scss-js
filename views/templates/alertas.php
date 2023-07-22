<?php
    foreach ($alertas as $key => $alerta): //array alertas, tomamos todo valor $key (exito o error) de $alerta
        foreach ($alerta as $mensaje)://lo que contenga alerta, pasamos el string array mensaje
?>

    <div class="alerta <?php echo $key ?> "> <?php echo $mensaje ?></div>


<?php endforeach;
 endforeach ?>