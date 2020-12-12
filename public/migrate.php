<?php

function env($name) {
    $file = file_get_contents("../.env");
    $file = explode(PHP_EOL, $file);
    foreach ($file as $f => $config) {
        $c = explode("=", $config);
        if ($c[0] == $name) {
            return $c[1];
        }
    }
    return $file;
}

include '../app/Controllers/Controller.php';

$Controller->migrate();
