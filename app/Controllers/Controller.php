<?php
namespace App\Controllers;

include '../app/Framework/autoload.php';
include '../app/Framework/vendor/autoload.php';

use App\Framework\DB;

class Controller {
	private static $_instance = null;

    public function get($index = NULL) {
		global $query,$type,$name;
		if ($query == "") {
			if ($type == "cookie") {
				return $_COOKIE[$name];
			}else if ($type == "session") {
				// 
			}
			return $type;
		}
	}

	public function session($nama) {
		global $type,$name;
		@session_start();
		$type = "session";
		$name = $nama;

		if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
	}
	public function cookie($nama) {
		global $type,$name;
		$type = "cookie";
		$name = $nama;

		if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
	}
	public function set($value) {
		global $type,$name;
		if ($type == "cookie") {
			setcookie($name, $value, time() + 3600, "/");
		}else if ($type == "session") {
			// 
		}
	}
	public function unset() {
		global $type,$name;
		if ($type == "cookie") {
			setcookie($name, '', time() + 1, "/");
		}else if ($type == "session") {
			// 
		}
	}

	public function middleware($name, $except = NULL) {
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
		if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
	}

	public function migrate() {
		$ctrl = new DB();
		$migration = file_get_contents("../migration.json");
		$db = json_decode($migration, true);

		foreach ($db['table'] as $key => $value) {
			$tableName = $key;
			$queryTable = "CREATE TABLE ".$tableName." (";
			foreach ($value as $q => $val) {
				$a = explode(" ", $val);
				$struktur = $a[0];
				$tipe = $a[1];
				$isNull = @$a[3] !== "null" ? "null" : "not null";
				$queryTable .= $struktur." ".$tipe." ".$isNull.", ";
			}
			$queryTable .= " dummy int(1) not null);";
			// echo $queryTable."<br />";
			$ctrl->query($queryTable);
			$delDummy = $ctrl->query("ALTER TABLE {$tableName} DROP dummy");
			echo "Table {$tableName} : $queryTable created <br />";
		}

		foreach ($db['attribute'] as $key => $value) {
			foreach ($value as $kunci => $isi) {
				$t = explode(".", $isi);
				
				$queryAttribute = "ALTER TABLE ".$t[0]." ADD ".$key." KEY ";
				if (strtolower($key) != "foreign") {
					$queryAttribute .= "({$t[1]})";
				}
				if (strtolower($key) == "foreign") {
					$f = explode("=>", $isi);
					$ta[0] = explode(".", $f[0]);
					$ta[1] = explode(".", $f[1]);
					$queryAttribute .= "({$ta[0][1]}) REFERENCES {$ta[1][0]}({$ta[1][1]})";
				}
				$addingAttribute = $ctrl->query($queryAttribute);
				echo "Table {$t[0]} changed : {$queryAttribute} <br />";

				// add auto_increment
				if ($addingAttribute) {
					if (strtolower($key) == "primary") {
						$queryAi = "ALTER TABLE {$t[0]} MODIFY {$t[1]} INTEGER AUTO_INCREMENT";
						$ctrl->query($queryAi);
						echo "Table {$t[0]} added auto increment on {$t[1]} : {$queryAi}<br />";
					}
				}
			}
		}
	}
	public function toIdr($angka) {
		return 'Rp '.strrev(implode('.',str_split(strrev(strval($angka)),3)));
	}
	public function env($name) {
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
	public function generateToken($length) {
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

$Controller = new Controller();
