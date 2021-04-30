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

if (strtolower(env('DEBUG_MODE')) == "off") {
	error_reporting(1);
}

global $currentPath;
$role = @$_GET['role'];
$bag = @$_GET['bag'];
if ($role == "" or $bag == "") {
	$currentPath = explode("?", ltrim($_SERVER['REQUEST_URI'], '/'))[0];
}else {
	$currentPath = $role."/".$bag;
}

$lastOpenedRoute = @$_COOKIE['lastOpenedRoute'];
setcookie('lastOpenedRoute', $currentPath, time() + 3600, '/');

function isBase64Encoded($str) {
	if (base64_encode(base64_decode($str)) === $str) {
		return true;
	}
	return false;
}

if ($_GET) {
	global $paramsToView;
	$paramsToView = [];
	foreach ($_GET as $key => $value) {
		if (isBase64Encoded($value)) {
			$value = base64_decode($value);
		}
		if ($key == "errors") {
			$value = json_decode($value, true);
		}
		$paramsToView[$key] = $value;
	}
}

function bindParams() {
	global $paramsToView;
	$params = explode("&", $_SERVER['QUERY_STRING']);
	foreach ($params as $param) {
		$key = explode("=", $param)[0];
		$value = ltrim(substr($param, strlen($key)), "=");
		if (isBase64Encoded($value)) {
			$value = base64_decode($value);
		}
		if ($key == "errors") {
			$value = json_decode($value, true);
		}

		$paramsToView[$key] = $value;
		$_GET[$key] = $value;
	}
	return $params;
}
bindParams();

function base_url() {
    return env('BASE_URL');
}
function route($path = NULL, $params = NULL) {
	global $currentPath;
	if ($params != NULL) {
		$path .= "?";
		foreach ($params as $key => $value) {
			$path .= "$key=$value";
		}
	}
	if ($path != NULL) {
		$baseUrl = substr(base_url(), -1) == "/" ? base_url() : base_url()."/";
		return $baseUrl.$path;
	}
	return $currentPath;
}
function getPath($realPath) {
	$realPath = explode("/", $realPath);
	unset($realPath[count($realPath) - 1]);
	return implode("/", $realPath)."/";
}
function view($viewName, $with = NULL) {
	global $paramsToView,$currentViewPath,$globalWithParams;
	if ($paramsToView) {
		foreach($paramsToView as $k => $v) {
			$$k = $v;
		}
	}
	$viewName = str_replace(".", "/", $viewName);
	$path = '../views/'.$viewName.".php";
	$currentViewPath = getPath(realpath($path));
	if (!file_exists($path)) {
		die("View not found");
	}

	$toReturn = file_get_contents($path);
	if ($with != NULL) {
		preg_match_all('/<?= \\$(.*?) /', $toReturn, $vars);
		foreach ($with as $k => $v) {
			$$k = $v;
			$globalWithParams[$k] = $v;
		}
		global $isSendingMail;
		if ($isSendingMail) {
			foreach ($vars[1] as $key => $var) {
				$toReturn = str_replace('<?= $'.$var.' ?>', $$var, $toReturn);
				$toReturn = str_replace('<?= $'.$var.'; ?>', $$var, $toReturn);
			}
		}
	}
	include $path;
	return $toReturn;
}
function insert($file, $params = NULL) {
	global $currentViewPath,$globalWithParams;
	$path = $currentViewPath.$file.".php";
	if ($globalWithParams) {
		foreach ($globalWithParams as $k => $v) {
			$$k = $v;
		}
	}
	// pass param from insert() caller
	if ($params != NULL) {
		foreach ($params as $k => $v) {
			$$k = $v;
		}
	}
	include $path;
}
function redirect($path, $params = NULL) {
	$baseUrl = substr(base_url(), -1) == "/" ? base_url() : base_url()."/";
	$full = explode("?", $baseUrl.$path)[0];
	if ($params != NULL) {
		$full .= "?isRedirected=1";
		foreach ($params as $key => $value) {
			$full .= "&$key=".base64_encode($value);
		}
	}
	header("location: $full");
}

$routes = require '../config/routes.php';
$queueRoute = [];

function parsePath($path, $toRemove = NULL) {
	$ret = [];
	$indexToRemove = [];
	$processedPath = [];
	$p = explode("/", $path);
	$i = 0;
	foreach ($p as $key => $value) {
		$iPP = $i++;
		if (substr($value, 0, 1) != "{") {
			array_push($ret, $value);
		}
		if ($toRemove == NULL) {
			if (substr($value, 0, 1) == "{") {
				array_push($indexToRemove, $iPP);
			}
		}
		array_push($processedPath, explode("?", $value)[0]);
	}
	if ($toRemove != NULL) {
		$i = 0;
		foreach ($toRemove as $key => $val) {
			array_splice($ret, $val - $i++, 1);
		}
	}
	$url = implode("/", $ret);
	$lastUrl = substr($url, -1);
	if ($lastUrl == "/" or substr($url, 0, 1) == "/") {
		$url = chop($url, "/");
		$url = trim($url, "/");
	}
	return ['url' => $url, 'params' => $indexToRemove, 'path' => $processedPath];
}

$a = 0;
foreach ($routes as $path => $callback) {
	$aPP = $a++;
	$parsedPath = parsePath($path);
	$parsedCurrentPath = parsePath($currentPath, $parsedPath['params']);
	$parsedCurrentPath['url'] = explode("?", $parsedCurrentPath['url'])[0];
	
	if ($parsedCurrentPath['url'] == $parsedPath['url']) {
		if (gettype($callback) == "string") {
			$e = explode(":", $callback);
			$HttpRequestMethod = $e[0];

			$params = $parsedPath['params'];
			$p = explode("/", $currentPath);

			// getting parameter's name
			preg_match_all('/{(.*?)}/', $path, $matches);
			include '../app/Controllers/autoload.php';
			
			$toPass = [];
			foreach ($params as $key => $param) {
				$value = explode("?", $p[$param])[0];
				array_push($toPass, $value);
			}
			array_push($toPass, new App\Framework\Request);

			if ($_SERVER['REQUEST_METHOD'] != $HttpRequestMethod) {
				die("Method not allowed");
			}
			
			$p = explode("@", $e[1]);
			$control = $p[0];
			$method = $p[1];
			$controller = "../app/Controllers/".$control.".php";
			if (file_exists($controller)) {
				// include all controllers
				$run = "App\Controllers\\$control";
				$$control = new $run();

				// deleting parameter when refreshed
				if ($lastOpenedRoute == $currentPath && @$_GET['isRedirected'] == 1) {
					redirect($currentPath);
				}
			
				if(method_exists($$control, $method)) {
					$$control->$method(...$toPass);
				}else {
					die('Function <b>'.$method.'</b> not found');
				}
			}else {
				die("Controller <b>".$control."</b> not found");
			}
			break;
		}else {
			// deleting parameter when refreshed
			if ($lastOpenedRoute == $currentPath && @$_GET['isRedirected'] == 1) {
				redirect($currentPath);
			}
			return $callback();
		}
	}else {
		array_push($queueRoute, $path);
	}
	if (count($queueRoute) == count($routes)) {
		die("404 Route not found");
	}
}
