<?php
namespace App\Framework;

class Request {
    private $data = [];
    private static $file = null;
    private static $_instance = null;

    public function __get($varName){
        if (!array_key_exists($varName,$this->data)) {
            //this attribute is not defined!
        }
            else return $this->data[$varName];
    }
    public function __set($varName,$value){
        $this->data[$varName] = $value;
    }
    public function __construct() {
        $inputs = file_get_contents('php://input');
        
        // Handle post params
        if($inputs !== NULL) {
            if (@$_SERVER['CONTENT_TYPE'] == "application/json") {
                $datas = json_decode($inputs, true);
                foreach ($datas as $key => $value) {
                    $this->{$key} = $value;
                }
            }else {
                $httpMethod = $_SERVER['REQUEST_METHOD'];

                if ($httpMethod == "POST") {
                    $postData = $_POST;
                    if (count($postData) != 0) {
                        foreach ($postData as $key => $value) {
                            $this->{$key} = $value;
                        }
                    }
                }else {
                    $datas = explode("&", $inputs);
                    foreach($datas as $key => $value) {
                        $a = explode("=", $value);
                        $this->{$a[0]} = @$a[1];
                    }
                }
            }
        }

        // Handle get params
        foreach($_GET as $key => $value) {
            if ($key != "lokasiItuUntukDefaultParam") {
                $this->{$key} = $value;
            }
        }
    }
    public function file($name) {
        self::$file = $_FILES[$name];

        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function getFileName() {
        return self::$file['name'];
    }
    public function getFileSize($unit = NULL) {
        $unit = strtolower($unit);
        $size = self::$file['size']; // in bytes

        if ($unit == NULL) {
            return $size;
        } else if ($unit == "kb") {
            return $size / 1024;
        } else if ($unit == "mb") {
            return $size / 1048576;
        }else if ($unit == "gb") {
            return $size / 1073741824;
        }
    }
    public function getFileExtension() {
        $fileName = self::$file['name'];
        if (count($fileName) == 1) {
            $e = explode(".", $fileName);
            return $e[count($e) - 1];
        }
        $toReturn = [];
        foreach ($fileName as $n) {
            $e = explode(".", $n);
            $ext = $e[count($e) - 1];
            array_push($toReturn, $ext);
        }
        return $toReturn;
    }
    public function store($path, $fileName = NULL) {
        $file = self::$file;
        if (count($file['name']) == 1) {
            $fileName = $fileName == NULL ? self::$file['name'] : $fileName;
            return Storage::disk($path)->store('/', self::$file, $fileName);
        }
        $i = 0;
        foreach ($file['name'] as $n) {
            $iPP = $i++;
            $name = $fileName == NULL ? $n : $fileName[$iPP];
            Storage::disk($path)->storeEach('/', self::$file, $iPP, $name);
        }
    }
}