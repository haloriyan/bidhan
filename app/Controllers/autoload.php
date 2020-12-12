<?php

$scan = glob("../app/Controllers/*");
foreach ($scan as $k => $s) {
    // if ($s != "../app/Controllers/autoload.php" && $s != "../app/Controllers/Controller.php") {
    if ($s != "../app/Controllers/autoload.php") {
        include realpath($s);
    }
}