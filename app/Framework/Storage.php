<?php
namespace App\Framework;

class Storage {
    private static $_instance = null;

    public function disk($directory) {
        // set directory's target
        global $rootPath;
		$rootPath = "../storage/".$directory;
		if (!file_exists($rootPath)) {
			mkdir($rootPath, 0777);
		}

		if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function move($origin, $destination) {
		global $rootPath;
		$file = $rootPath."/".$origin;
		$newFile = "../storage/".$destination;
		return rename($file, $newFile);
    }
    public function store($destination, $file, $overrideName = NULL) {
		global $rootPath;
		$tmpName = $file['tmp_name'];

		$finalDestination = $rootPath."/".$destination;
		if (!file_exists($finalDestination)) {
			mkdir($finalDestination, 0777);
		}

		$name = $overrideName ? $overrideName : $file['name'];
		return move_uploaded_file($tmpName, $finalDestination."/".$name);
    }
    public function storeEach($destination, $file, $i, $overrideName = NULL) {
		global $rootPath;
		$tmpName = $file['tmp_name'][$i];
		
		$finalDestination = $rootPath."/".$destination;
		if (!file_exists($finalDestination)) {
			mkdir($finalDestination, 0777);
		}

		$name = $overrideName ? $overrideName : $file['name'][$i];
		return move_uploaded_file($tmpName, $finalDestination."/".$name);
    }
    public function delete($params) {
        global $rootPath;
        $toDelete = $rootPath."/".$params;
        return unlink($toDelete);
    }
}