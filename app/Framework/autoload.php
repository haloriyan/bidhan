<?php

$scan = glob("../app/Framework/*");
foreach ($scan as $k => $s) {
    if ($s != "../app/Framework/autoload.php") {
        $framework['a'] = explode("/", $s);
        $framework['e'] = explode(".", $framework['a'][count($framework['a']) - 1]);
        $framework['ext'] = $framework['e'][count($framework['e']) - 1];
        if ($framework['ext'] == "php") {
            include realpath($s);
        }
    }
}