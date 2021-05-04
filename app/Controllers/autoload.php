<?php

$scan = glob("../app/Controllers/*");
foreach ($scan as $k => $s) {
    if ($s != "../app/Controllers/autoload.php") {
        include realpath($s);
    }
}

$scanModels = glob("../app/Models/*");
foreach ($scanModels as $k => $s) {
    if ($s != "../app/Models/autoload.php") {
        include realpath($s);
    }
}