<?php

namespace App\Framework;

class Middleware {
    public static function use($name, $except = NULL) {
		$path = "../app/Middleware/".$name.".php";
		if (file_exists($path)) {
			include $path;
			$run = "App\Middleware\\$name";
			$$name = new $run();
			if ($except != "") {
				global $method;
				$queue = [];
				foreach ($except as $key => $x) {
					if ($x == $method) {
						break;
					}else {
						array_push($queue, $key);
					}
				}
				if (count($except) == count($queue)) {
					$$name->handle();
				}
			}else{
				$$name->handle();
			}
		}else {
			die("Middleware not found");
		}
	}
}