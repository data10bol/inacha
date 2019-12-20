<?php
    echo 'Limpiando cache del sistema';
    echo "<pre>$output</pre>";
    $output = shell_exec('chmod 777 /var/www/inacha.ofep.gob.bo/web/artisan');
    echo "<pre>$output</pre>";
    $output = shell_exec('/var/www/inacha.ofep.gob.bo/web/artisan optimize');
    echo "<pre>$output</pre>";
    $output = shell_exec('chmod 644 /var/www/inacha.ofep.gob.bo/web/artisan');
    echo "<pre>$output</pre>";
    echo 'listo';
?>